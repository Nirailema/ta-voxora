<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Device;
use App\Services\BrailleConverter;
use App\Services\EduBrailleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrailleController extends Controller
{
    public function __construct(
        protected BrailleConverter $brailleConverter,
        protected EduBrailleService $eduBrailleService
    ) {}

    public function index(int $documentId): View
    {
        $document = Document::where('user_id', auth()->id())
            ->where('status', 'ready')
            ->findOrFail($documentId);

        $devices = Device::where('status', 'active')->get();
        $chunkSizes = $this->brailleConverter->supportedChunkSizes();
        $defaultChunkSize = 20;

        $previewText = $document->preview_text ?? '';
        $previewChunks = $this->brailleConverter->convert($previewText, $defaultChunkSize);

        return view('user.braille.index', compact(
            'document',
            'devices',
            'chunkSizes',
            'defaultChunkSize',
            'previewChunks'
        ));
    }

    public function convert(Request $request, int $documentId): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'chunk_size' => ['required', 'integer', 'in:5,20'],
        ]);

        $document = Document::where('user_id', auth()->id())
            ->where('status', 'ready')
            ->findOrFail($documentId);

        $chunkSize = (int) $request->chunk_size;
        $fullText = $document->preview_text ?? '';
        $chunks = $this->brailleConverter->convert($fullText, $chunkSize);

        return response()->json([
            'success' => true,
            'chunks' => $chunks,
            'total' => count($chunks),
            'chunk_size' => $chunkSize,
        ]);
    }

    public function sendToDevice(Request $request, int $documentId): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'device_id' => ['required', 'exists:devices,id'],
            'chunks' => ['required', 'array'],
            'chunk_index' => ['required', 'integer', 'min:0'],
        ]);

        $document = Document::where('user_id', auth()->id())
            ->where('status', 'ready')
            ->findOrFail($documentId);

        $device = Device::findOrFail($request->device_id);

        $result = $this->eduBrailleService->sendToDevice(
            $device->endpoint_url,
            $request->chunks
        );

        return response()->json($result);
    }
}
