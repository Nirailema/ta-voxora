<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChromaService
{
    protected string $host;

    public function __construct()
    {
        $this->host = config('services.chroma.host', 'http://localhost:8000');
    }

    public function createCollection(string $name): array
    {
        $response = Http::post("{$this->host}/api/v1/collections", [
            'name' => $name,
            'get_or_create' => true,
        ]);

        if ($response->failed()) {
            Log::error('ChromaDB createCollection failed', ['name' => $name, 'error' => $response->body()]);
            throw new \Exception('Failed to create ChromaDB collection: ' . $response->body());
        }

        return $response->json();
    }

    public function addEmbeddings(string $collectionId, array $ids, array $embeddings, array $documents, array $metadatas = []): bool
    {
        $payload = [
            'ids' => $ids,
            'embeddings' => $embeddings,
            'documents' => $documents,
        ];

        if (!empty($metadatas)) {
            $payload['metadatas'] = $metadatas;
        }

        $response = Http::post("{$this->host}/api/v1/collections/{$collectionId}/add", $payload);

        if ($response->failed()) {
            Log::error('ChromaDB addEmbeddings failed', ['collectionId' => $collectionId, 'error' => $response->body()]);
            throw new \Exception('Failed to add embeddings to ChromaDB: ' . $response->body());
        }

        return true;
    }

    public function queryCollection(string $collectionId, array $queryEmbeddings, int $topK = 5, array $where = []): array
    {
        $payload = [
            'query_embeddings' => $queryEmbeddings,
            'n_results' => $topK,
            'include' => ['documents', 'metadatas', 'distances'],
        ];

        if (!empty($where)) {
            $payload['where'] = $where;
        }

        $response = Http::post("{$this->host}/api/v1/collections/{$collectionId}/query", $payload);

        if ($response->failed()) {
            Log::error('ChromaDB queryCollection failed', ['collectionId' => $collectionId, 'error' => $response->body()]);
            throw new \Exception('Failed to query ChromaDB: ' . $response->body());
        }

        return $response->json();
    }

    public function deleteCollection(string $name): bool
    {
        $response = Http::delete("{$this->host}/api/v1/collections/{$name}");

        if ($response->failed()) {
            Log::warning('ChromaDB deleteCollection failed', ['name' => $name, 'error' => $response->body()]);
            return false;
        }

        return true;
    }

    public function collectionExists(string $name): bool
    {
        $response = Http::get("{$this->host}/api/v1/collections/{$name}");

        return $response->successful();
    }
}
