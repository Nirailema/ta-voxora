<?php

namespace App\Services;

/**
 * Braille conversion service.
 *
 * Converts text to Braille patterns.
 * Implementation to be provided in a future iteration.
 *
 * Current: Placeholder stub that returns ASCII representation.
 */
class BrailleConverter
{
    /**
     * Braille alphabet mapping (English/Latin).
     */
    protected array $brailleMap = [
        'a' => '⠁', 'b' => '⠃', 'c' => '⠉', 'd' => '⠙',
        'e' => '⠑', 'f' => '⠋', 'g' => '⠛', 'h' => '⠓',
        'i' => '⠊', 'j' => '⠚', 'k' => '⠅', 'l' => '⠇',
        'm' => '⠍', 'n' => '⠝', 'o' => '⠕', 'p' => '⠏',
        'q' => '⠟', 'r' => '⠗', 's' => '⠎', 't' => '⠞',
        'u' => '⠥', 'v' => '⠧', 'w' => '⠺', 'x' => '⠭',
        'y' => '⠽', 'z' => '⠵',
        '1' => '⠼⠁', '2' => '⠼⠃', '3' => '⠼⠉', '4' => '⠼⠙',
        '5' => '⠼⠑', '6' => '⠼⠋', '7' => '⠼⠛', '8' => '⠼⠓',
        '9' => '⠼⠊', '0' => '⠼⠚',
        ' ' => ' ',
        '.' => '⠼⠲', ',' => '⠐',
        ';' => '⠰', '!' => '⠆', '?' => '⠦',
        '(' => '⠦', ')' => '⠖',
        '-' => '⠤', ':' => '⠱', "'" => '⠄',
    ];

    /**
     * Convert text to Braille.
     *
     * @param string $text The text to convert
     * @param int $chunkSize Chunk size in characters (5 or 20 as per EduBraille spec)
     * @return array Array of chunks with original text and Braille representation
     */
    public function convert(string $text, int $chunkSize = 20): array
    {
        $chunks = [];
        $length = mb_strlen($text);
        $position = 0;
        $chunkIndex = 0;

        while ($position < $length) {
            $chunkText = mb_substr($text, $position, $chunkSize);
            $chunks[] = [
                'index' => $chunkIndex,
                'text' => $chunkText,
                'braille' => $this->textToBraille($chunkText),
                'length' => mb_strlen($chunkText),
            ];
            $position += $chunkSize;
            $chunkIndex++;
        }

        return $chunks;
    }

    protected function textToBraille(string $text): string
    {
        $braille = '';
        $chars = preg_split('//u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $char) {
            $braille .= $this->brailleMap[$char] ?? $char;
        }

        return $braille;
    }

    /**
     * Get supported chunk sizes.
     */
    public function supportedChunkSizes(): array
    {
        return [5, 20];
    }
}
