<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RagService
{
    protected ChromaService $chroma;
    protected string $llmApiUrl;
    protected string $llmApiKey;
    protected string $llmModel;
    protected string $embeddingUrl;
    protected string $embeddingModel;

    public function __construct(ChromaService $chroma)
    {
        $this->chroma = $chroma;
        $this->llmApiUrl = config('services.llm.api_url', env('LLM_API_URL', ''));
        $this->llmApiKey = config('services.llm.api_key', env('LLM_API_KEY', ''));
        $this->llmModel = config('services.llm.model', env('LLM_MODEL', 'gpt-4o-mini'));
        $this->embeddingUrl = config('services.embedding.url', env('EMBEDDING_API_URL', ''));
        $this->embeddingModel = config('services.embedding.model', env('EMBEDDING_MODEL', 'text-embedding-3-small'));
    }

    public function embedText(string $text): array
    {
        if (empty($text)) {
            return [];
        }

        $response = Http::withToken($this->llmApiKey)
            ->timeout(30)
            ->post($this->embeddingUrl, [
                'model' => $this->embeddingModel,
                'input' => $text,
            ]);

        if ($response->failed()) {
            Log::error('Embedding API failed', ['error' => $response->body()]);
            throw new \Exception('Embedding API failed: ' . $response->body());
        }

        $data = $response->json();
        return $data['data'][0]['embedding'] ?? [];
    }

    public function storeChunks(string $collectionId, array $chunks): void
    {
        $this->chroma->createCollection($collectionId);

        $ids = [];
        $embeddings = [];
        $documents = [];
        $metadatas = [];

        foreach ($chunks as $index => $chunk) {
            $ids[] = "chunk_{$index}";
            $documents[] = $chunk['text'];

            $embedding = $this->embedText($chunk['text']);
            $embeddings[] = $embedding;
            $metadatas[] = ['index' => $index, 'chunk_size' => strlen($chunk['text'])];
        }

        $this->chroma->addEmbeddings($collectionId, $ids, $embeddings, $documents, $metadatas);
    }

    public function retrieveRelevantChunks(string $collectionId, array $queryEmbedding, int $topK = 5): array
    {
        $results = $this->chroma->queryCollection($collectionId, [$queryEmbedding], $topK);

        if (empty($results['documents']) || empty($results['documents'][0])) {
            return [];
        }

        $chunks = [];
        foreach ($results['documents'][0] as $i => $doc) {
            $chunks[] = [
                'text' => $doc,
                'distance' => $results['distances'][0][$i] ?? null,
            ];
        }

        return $chunks;
    }

    public function generateAnswer(array $chunks, array $conversationHistory, string $userQuery): string
    {
        $context = '';
        foreach ($chunks as $i => $chunk) {
            $context .= "[Dokumen {$i}] " . $chunk['text'] . "\n\n";
        }

        $systemPrompt = <<<EOT
Kamu adalah asisten pembelajaran yang membantu pengguna memahami dokumen STEM/math tingkat SMP-SMA.
Gunakan informasi dari dokumen yang diberikan untuk menjawab pertanyaan pengguna.
Jika informasi tidak cukup dalam dokumen, katakan dengan jujur bahwa kamu tidak menemukan jawabannya dalam dokumen.
Selalu jawab dalam Bahasa Indonesia.
Jawab dengan jelas, terstruktur, dan ramah untuk siswa.
EOT;

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'system', 'content' => "Konteks dokumen:\n{$context}"],
        ];

        foreach ($conversationHistory as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userQuery];

        $response = Http::withToken($this->llmApiKey)
            ->timeout(60)
            ->post($this->llmApiUrl, [
                'model' => $this->llmModel,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

        if ($response->failed()) {
            Log::error('LLM API failed', ['error' => $response->body()]);
            throw new \Exception('LLM API failed: ' . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? '';
    }
}
