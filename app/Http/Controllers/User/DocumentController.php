<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\DocumentProcessor;
use App\Services\RagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DocumentController extends Controller
{
    public function __construct(
        protected DocumentProcessor $processor,
        protected RagService $ragService
    ) {}

    public function showUpload(): View
    {
        return view('user.upload');
    }

    public function upload(Request $request): JsonResponse|View|RedirectResponse
    {
        $request->validate([
            'document' => [
                'required',
                'file',
                'mimes:pdf,docx',
                'max:10240',
            ],
        ], [
            'document.required' => 'Silakan pilih file untuk diunggah.',
            'document.mimes' => 'File harus berupa PDF atau DOCX.',
            'document.max' => 'Ukuran file maksimal adalah 10MB.',
        ]);

        $file = $request->file('document');
        $mimeType = $file->getMimeType();
        $originalFilename = $file->getClientOriginalName();

        // Store file
        $storagePath = $file->store('documents', 'local');
        $document = Document::create([
            'user_id' => Auth::id(),
            'title' => pathinfo($originalFilename, PATHINFO_FILENAME),
            'original_filename' => $originalFilename,
            'file_path' => $storagePath,
            'status' => 'processing',
        ]);

        try {
            // Pre-check: verify document has extractable text
            if ($mimeType === 'application/pdf' && $this->processor->isPdfImageBased($storagePath)) {
                throw new \Exception('Dokumen PDF ini merupakan hasil scan (gambar). Ekstraksi teks tidak tersedia untuk dokumen scan. Silakan gunakan PDF berbasis teks atau dokumen DOCX.');
            }

            if ($mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' && !$this->processor->isDocxHasText($storagePath)) {
                throw new \Exception('Dokumen DOCX tidak mengandung teks yang dapat diekstrak. Pastikan dokumen mengandung teks, bukan hanya gambar.');
            }

            // Extract text
            $rawText = $this->processor->extractText($storagePath, $mimeType);

            // Sanitize
            $sanitizedText = $this->processor->sanitizeText($rawText);

            if (empty(trim($sanitizedText))) {
                throw new \Exception('Tidak dapat mengekstrak teks dari dokumen. Pastikan dokumen mengandung teks yang dapat dibaca.');
            }

            // Infer title
            $title = $this->processor->inferTitle($sanitizedText, $originalFilename);

            // Chunk text
            $chunks = $this->processor->chunkText($sanitizedText);

            // Store in ChromaDB
            $collectionId = 'doc_' . $document->id . '_' . Str::random(8);
            $this->ragService->storeChunks($collectionId, $chunks);

            // Update document
            $document->update([
                'title' => $title,
                'preview_text' => mb_substr($sanitizedText, 0, 200),
                'status' => 'ready',
                'collection_id' => $collectionId,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'document_id' => $document->id,
                    'title' => $title,
                    'preview_text' => mb_substr($sanitizedText, 0, 200),
                    'message' => 'Dokumen berhasil diproses!',
                ]);
            }

            return redirect()
                ->route('user.conversation.show', $document->id)
                ->with('success', 'Dokumen berhasil diproses!');

        } catch (\Exception $e) {
            Log::error('Document processing failed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            $document->update(['status' => 'error']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses dokumen: ' . $e->getMessage(),
                ], 500);
            }

            return back()
                ->with('error', 'Gagal memproses dokumen: ' . $e->getMessage())
                ->withInput();
        }
    }
}
