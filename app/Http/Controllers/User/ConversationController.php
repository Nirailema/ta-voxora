<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Document;
use App\Models\Message;
use App\Services\RagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConversationController extends Controller
{
    public function __construct(protected RagService $ragService) {}

    public function show(int $documentId): View
    {
        $document = Document::where('user_id', auth()->id())
            ->where('status', 'ready')
            ->findOrFail($documentId);

        // Get or create conversation
        $conversation = Conversation::firstOrCreate(
            ['user_id' => auth()->id(), 'document_id' => $documentId],
            ['user_id' => auth()->id(), 'document_id' => $documentId]
        );

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return view('user.conversation.index', compact('document', 'conversation', 'messages'));
    }

    public function ask(Request $request, int $documentId): \Illuminate\Http\Response
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $document = Document::where('user_id', auth()->id())
            ->where('status', 'ready')
            ->findOrFail($documentId);

        if (empty($document->collection_id)) {
            return response()->json(['error' => 'Dokumen belum siap untuk ditanyakan.'], 400);
        }

        $userQuery = trim($request->message);

        // Get or create conversation
        $conversation = Conversation::firstOrCreate(
            ['user_id' => auth()->id(), 'document_id' => $documentId],
            ['user_id' => auth()->id(), 'document_id' => $documentId]
        );

        // Save user message
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $userQuery,
        ]);

        try {
            // Get conversation history
            $history = $conversation->messages()
                ->where('id', '!=', $userMessage->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
                ->toArray();

            // Embed user query
            $queryEmbedding = $this->ragService->embedText($userQuery);

            // Retrieve relevant chunks
            $chunks = $this->ragService->retrieveRelevantChunks(
                $document->collection_id,
                $queryEmbedding,
                5
            );

            // Generate answer
            $answer = $this->ragService->generateAnswer($chunks, $history, $userQuery);

            // Save assistant message
            $assistantMessage = Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => $answer,
            ]);

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $assistantMessage->id,
                    'role' => 'assistant',
                    'content' => $answer,
                    'created_at' => $assistantMessage->created_at->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('RAG query failed', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Maaf, terjadi kesalahan saat memproses pertanyaan Anda. Silakan coba lagi.',
            ], 500);
        }
    }

    public function exportToWord(int $documentId): StreamedResponse
    {
        $document = Document::where('user_id', auth()->id())
            ->where('status', 'ready')
            ->findOrFail($documentId);

        $conversation = Conversation::where('user_id', auth()->id())
            ->where('document_id', $documentId)
            ->firstOrFail();

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Title
        $section->addTitle('VOXARA - Transkrip Percakapan', 1);
        $section->addText("Dokumen: {$document->title}", ['size' => 12, 'italic' => true]);
        $section->addText("Tanggal: {$conversation->created_at->format('d F Y')}", ['size' => 11]);
        $section->addTextBreak(2);

        // Messages
        foreach ($messages as $msg) {
            $role = $msg->role === 'user' ? 'Anda' : 'VOXARA (AI)';
            $section->addText("[{$role}] {$msg->created_at->format('H:i')}", [
                'bold' => true,
                'size' => 11,
                'color' => $msg->role === 'assistant' ? '1B4F72' : '1A1A2E',
            ]);
            $section->addText($msg->content, ['size' => 11]);
            $section->addTextBreak();
        }

        $filename = 'voxara_' . preg_replace('/[^a-zA-Z0-9]/', '_', $document->title) . '_' . now()->format('Ymd_His') . '.docx';

        return new StreamedResponse(function () use ($phpWord) {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function clearConversation(int $documentId): \Illuminate\Http\RedirectResponse
    {
        $conversation = Conversation::where('user_id', auth()->id())
            ->where('document_id', $documentId)
            ->firstOrFail();

        $conversation->messages()->delete();

        return back()->with('success', 'Riwayat percakapan telah dihapus.');
    }
}
