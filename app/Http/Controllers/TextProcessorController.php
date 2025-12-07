<?php

namespace App\Http\Controllers;

use InvalidArgumentException;

class TextProcessorController extends Controller
{
    /**
     * @param  array{min_length?: int, max_length?: int}  $options
     * @return array{original_text: string, character_count: int, word_count: int, sentence_count: int, uppercase_text: string, lowercase_text: string, reversed_text: string}
     */
    public function analyzeText(string $text, array $options = []): array
    {
        if (trim($text) === '') {
            throw new InvalidArgumentException('El texto no puede estar vacío.');
        }

        $minLength = $options['min_length'] ?? 1;
        $maxLength = $options['max_length'] ?? 10000;

        if ($minLength < 1) {
            throw new InvalidArgumentException('La longitud mínima debe ser mayor a 0.');
        }

        if ($maxLength < $minLength) {
            throw new InvalidArgumentException('La longitud máxima debe ser mayor o igual a la mínima.');
        }

        $textLength = mb_strlen($text);

        if ($textLength < $minLength || $textLength > $maxLength) {
            throw new InvalidArgumentException("El texto debe tener entre {$minLength} y {$maxLength} caracteres.");
        }

        $characterCount = mb_strlen($text);
        $wordCount = str_word_count($text);
        $sentenceCount = preg_match_all('/[.!?]+/', $text);

        return [
            'original_text' => $text,
            'character_count' => $characterCount,
            'word_count' => $wordCount,
            'sentence_count' => $sentenceCount,
            'uppercase_text' => mb_strtoupper($text),
            'lowercase_text' => mb_strtolower($text),
            'reversed_text' => strrev($text),
        ];
    }
}
