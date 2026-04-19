<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EduBrailleService
{
    /**
     * Send Braille chunks to an EduBraille device endpoint.
     *
     * @param string $deviceEndpoint The device endpoint URL
     * @param array $brailleChunks Array of Braille chunks to send
     * @return array Response from the device
     */
    public function sendToDevice(string $deviceEndpoint, array $brailleChunks): array
    {
        try {
            $response = Http::timeout(30)
                ->post($deviceEndpoint, [
                    'chunks' => $brailleChunks,
                    'total_chunks' => count($brailleChunks),
                    'timestamp' => now()->toIso8601String(),
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Braille content sent successfully to device.',
                    'data' => $response->json(),
                ];
            }

            Log::error('EduBraille device request failed', [
                'endpoint' => $deviceEndpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Device rejected the request. Status: ' . $response->status(),
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('EduBraille device connection failed', [
                'endpoint' => $deviceEndpoint,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to connect to device: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Validate a device endpoint URL.
     */
    public function validateEndpoint(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parsed = parse_url($url);
        return in_array($parsed['scheme'] ?? '', ['http', 'https']);
    }
}
