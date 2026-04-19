<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class DocumentProcessor
{
    public function extractText(string $filePath, string $mimeType): string
    {
        $fullPath = storage_path("app/{$filePath}");

        if (!file_exists($fullPath)) {
            throw new \Exception("File not found: {$fullPath}");
        }

        return match ($mimeType) {
            'application/pdf' => $this->extractFromPdf($fullPath),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => $this->extractFromDocx($fullPath),
            default => throw new \Exception("Unsupported file type: {$mimeType}"),
        };
    }

    protected function extractFromPdf(string $path): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($path);
        $text = $pdf->getText();

        // Remove null bytes and normalize
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);

        return $text;
    }

    protected function extractFromDocx(string $path): string
    {
        $phpWord = IOFactory::load($path, 'Word2007');
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text .= $this->extractTextFromElement($element) . "\n";
            }
        }

        return $text;
    }

    protected function extractTextFromElement($element): string
    {
        $text = '';

        if (method_exists($element, 'getText')) {
            $text = $element->getText();
        }

        // Handle nested elements
        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $text .= $this->extractTextFromElement($child) . ' ';
            }
        }

        return $text;
    }

    public function sanitizeText(string $text): string
    {
        // Remove multiple whitespace
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // Remove multiple newlines
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        // Fix common encoding issues
        $text = preg_replace('/[\xC2\xA0]/', ' ', $text); // non-breaking space
        $text = preg_replace('/[\xE2\x80\x93]/', '-', $text); // en dash
        $text = preg_replace('/[\xE2\x80\x94]/', '--', $text); // em dash
        $text = preg_replace('/[\xE2\x80\x98\xE2\x80\x99]/', "'", $text); // smart quotes
        $text = preg_replace('/[\xE2\x80\x9C\xE2\x80\x9D]/', '"', $text); // smart double quotes

        // Remove header/footer patterns (common page numbers, headers)
        $text = preg_replace('/^(?:Halaman\s+\d+|Page\s+\d+)\s*$/imu', '', $text);
        $text = preg_replace('/\b(?:Copyright|©|®™)\b/iu', '', $text);

        // Normalize unicode
        $text = normalizer_normalize($text, \Normalizer::FORM_C) ?: $text;

        // Trim
        $text = trim($text);

        return $text;
    }

    public function chunkText(string $text, int $chunkSize = 500, int $overlap = 50): array
    {
        $chunks = [];
        $length = mb_strlen($text);

        if ($length <= $chunkSize) {
            return [['text' => $text, 'index' => 0]];
        }

        $position = 0;
        $index = 0;

        while ($position < $length) {
            $start = $position;
            $end = min($position + $chunkSize, $length);

            // Try to break at sentence or paragraph boundary
            if ($end < $length) {
                // Look for sentence end
                $searchRange = mb_substr($text, $end, 200);
                $sentenceEnd = mb_strrpos($searchRange, '. ');
                if ($sentenceEnd !== false && $sentenceEnd < 150) {
                    $end += $sentenceEnd + 2;
                } else {
                    // Look for paragraph
                    $paraEnd = mb_strrpos($searchRange, "\n\n");
                    if ($paraEnd !== false && $paraEnd < 150) {
                        $end += $paraEnd + 2;
                    } else {
                        // Look for space boundary
                        $spacePos = mb_strrpos(mb_substr($text, $position, $chunkSize), ' ');
                        if ($spacePos !== false) {
                            $end = $position + $spacePos;
                        }
                    }
                }
            }

            $chunkText = trim(mb_substr($text, $start, $end - $start));
            if (!empty($chunkText)) {
                $chunks[] = [
                    'text' => $chunkText,
                    'index' => $index,
                ];
            }

            $index++;
            $position = max($position + $chunkSize - $overlap, $end);
        }

        return $chunks;
    }

    public function inferTitle(string $text, string $originalFilename): string
    {
        // Try to extract title from first heading-like line
        $lines = explode("\n", trim($text));
        $firstLine = trim($lines[0] ?? '');

        // If first line looks like a title (short, capitalized)
        if (!empty($firstLine) && mb_strlen($firstLine) <= 100 && mb_strlen($firstLine) >= 3) {
            // Check if it has mixed case (not all uppercase or all lowercase)
            if (preg_match('/[a-z].*[A-Z]|[A-Z].*[a-z]/', $firstLine)) {
                return $firstLine;
            }
        }

        // Fallback: clean up original filename
        $name = pathinfo($originalFilename, PATHINFO_FILENAME);
        $name = preg_replace('/[_-]+/', ' ', $name);
        $name = preg_replace('/\b(pdf|docx?|doc)\b/i', '', $name);
        return trim(ucfirst($name)) ?: 'Dokumen Tanpa Judul';
    }

    /**
     * NOTE: For future MathReader integration for math notation (e.g., LaTeX, MathML).
     * Reference: https://github.com/AIDASLab/MathReader
     * This processor handles text extraction; math notation processing
     * can be integrated here in a future iteration.
     */
}
