<?php

namespace App\Services\AI\Support;

class JsonCleaner
{
    /**
     * Robustly decode JSON returned by LLMs (strips markdown fences, prose wrappers,
     * trailing commas, and unescaped control characters inside string literals).
     */
    public static function decode(?string $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        // Remove UTF-8 BOM if present
        $text = preg_replace('/^\xEF\xBB\xBF/', '', trim($raw));
        if ($text === '') {
            return null;
        }

        // 1. Fast path: valid JSON out of the box
        $decoded = json_decode($text, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // 2. Strip markdown code fences (```json ... ``` or ``` ... ```)
        $cleaned = $text;
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/i', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
            $decoded = json_decode($cleaned, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // 3. Extract outermost JSON object {...}
        $firstBrace = strpos($cleaned, '{');
        $lastBrace = strrpos($cleaned, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $cleaned = substr($cleaned, $firstBrace, $lastBrace - $firstBrace + 1);
            $decoded = json_decode($cleaned, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // 4. Repair unescaped control chars inside JSON string literals & trailing commas
        $repaired = self::escapeControlCharsInStrings($cleaned);
        $repaired = preg_replace('/,\s*([\]}])/', '$1', $repaired);

        $decoded = json_decode($repaired, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return null;
    }

    /**
     * Escape raw newline/tab/control characters that appear unescaped inside JSON double-quoted strings.
     */
    protected static function escapeControlCharsInStrings(string $json): string
    {
        $len = strlen($json);
        $out = '';
        $inString = false;
        $escaped = false;

        for ($i = 0; $i < $len; $i++) {
            $ch = $json[$i];

            if ($inString) {
                if ($escaped) {
                    $out .= $ch;
                    $escaped = false;
                    continue;
                }
                if ($ch === '\\') {
                    $out .= $ch;
                    $escaped = true;
                    continue;
                }
                if ($ch === '"') {
                    $out .= $ch;
                    $inString = false;
                    continue;
                }

                $ord = ord($ch);
                if ($ord < 0x20) {
                    $out .= match ($ch) {
                        "\n" => '\\n',
                        "\r" => '\\r',
                        "\t" => '\\t',
                        "\b" => '\\b',
                        "\f" => '\\f',
                        default => sprintf('\\u%04x', $ord),
                    };
                    continue;
                }

                $out .= $ch;
            } else {
                if ($ch === '"') {
                    $inString = true;
                }
                $out .= $ch;
            }
        }

        return $out;
    }
}

