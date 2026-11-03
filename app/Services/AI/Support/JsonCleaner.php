<?php

namespace App\Services\AI\Support;

class JsonCleaner
{
    /**
     * Robustly decode JSON returned by LLMs:
     * - Strips UTF-8 BOM and outer markdown code fences (without touching inner code blocks)
     * - Extracts outermost JSON object `{...}`
     * - Escapes raw control characters (\n, \r, \t) inside string literals
     * - Repairs invalid JSON backslash escapes inside code/HTML strings (e.g. PHP namespaces `App\Services`, `\$var`, `\d+`, `\Users`)
     * - Strips trailing commas outside string literals
     * - Repairs unescaped inner double quotes inside HTML attributes as a last-resort fallback
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

        // 2. Strip outer markdown code fences ONLY when anchored at the start of the response
        $cleaned = $text;
        if (preg_match('/^\s*```(?:json)?\s*([\s\S]*?)\s*```\s*$/i', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
            $decoded = json_decode($cleaned, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        } elseif (preg_match('/^\s*```(?:json)?\s*/i', $cleaned)) {
            $cleaned = preg_replace('/^\s*```(?:json)?\s*/i', '', $cleaned);
            $cleaned = preg_replace('/\s*```\s*$/', '', $cleaned);
            $cleaned = trim($cleaned);
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

        // 4. Repair invalid backslash escapes, raw control chars inside strings, and trailing commas outside strings
        $repaired = self::repairJsonStructureAndStrings($cleaned, false);
        $decoded = json_decode($repaired, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // 5. Last-resort fallback: also escape unescaped double quotes inside string values (e.g. HTML attributes)
        $repairedQuotes = self::repairJsonStructureAndStrings($cleaned, true);
        $decoded = json_decode($repairedQuotes, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return null;
    }

    /**
     * Scan JSON text character-by-character to repair string-literal issues without corrupting inner code blocks.
     */
    protected static function repairJsonStructureAndStrings(string $json, bool $escapeInnerQuotes): string
    {
        $len = strlen($json);
        $out = '';
        $inString = false;

        for ($i = 0; $i < $len; $i++) {
            $ch = $json[$i];

            if ($inString) {
                if ($ch === '\\') {
                    $next = ($i + 1 < $len) ? $json[$i + 1] : '';

                    // Valid RFC 8259 single-char escapes: " \ / b f n r t
                    if ($next !== '' && str_contains('"\\/bfnrt', $next)) {
                        $out .= '\\' . $next;
                        $i++;
                        continue;
                    }

                    // Valid RFC 8259 unicode escape: \uXXXX (4 hex digits)
                    if ($next === 'u' && ($i + 5 < $len) && ctype_xdigit(substr($json, $i + 2, 4))) {
                        $out .= substr($json, $i, 6);
                        $i += 5;
                        continue;
                    }

                    // Backslash followed by raw newline/CR: escape newline properly
                    if ($next === "\n" || $next === "\r") {
                        $out .= '\\\\';
                        continue;
                    }

                    // Invalid escape sequence (e.g. App\Services, Illuminate\Http, \$var, \d+, \Users):
                    // Escape the literal backslash so JSON remains valid
                    $out .= '\\\\';
                    continue;
                }

                if ($ch === '"') {
                    if ($escapeInnerQuotes && !self::isLikelyClosingQuote($json, $i + 1, $len)) {
                        $out .= '\\"';
                        continue;
                    }

                    $out .= '"';
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
                    $out .= '"';
                    continue;
                }

                // Strip trailing comma outside strings when followed only by whitespace and `}` or `]`
                if ($ch === ',') {
                    $j = $i + 1;
                    while ($j < $len && ($json[$j] === ' ' || $json[$j] === "\t" || $json[$j] === "\n" || $json[$j] === "\r")) {
                        $j++;
                    }
                    if ($j < $len && ($json[$j] === '}' || $json[$j] === ']')) {
                        continue;
                    }
                }

                $out .= $ch;
            }
        }

        return $out;
    }

    /**
     * Check whether a quote at position ($nextIndex - 1) is followed (after optional whitespace)
     * by a valid JSON token delimiter (`:`, `,`, `}`, `]`) or end-of-input.
     */
    protected static function isLikelyClosingQuote(string $json, int $nextIndex, int $len): bool
    {
        $j = $nextIndex;
        while ($j < $len && ($json[$j] === ' ' || $json[$j] === "\t" || $json[$j] === "\n" || $json[$j] === "\r")) {
            $j++;
        }

        if ($j >= $len) {
            return true;
        }

        $nextNonWs = $json[$j];
        return $nextNonWs === ':' || $nextNonWs === ',' || $nextNonWs === '}' || $nextNonWs === ']';
    }
}
