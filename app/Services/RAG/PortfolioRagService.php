<?php

namespace App\Services\RAG;

use App\Models\AiProvider;
use App\Services\AI\AIProviderFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

/**
 * Retrieval-Augmented Generation (RAG) orchestrator for the portfolio AI Copilot.
 * Combines semantic vector cosine similarity (when embedding APIs are available)
 * with UTF-8 / Vietnamese-folded BM25 lexical ranking, then invokes the active
 * configured LLM provider (`AIProviderFactory`) grounded strictly in top-K DB chunks.
 */
class PortfolioRagService
{
    private const TOP_K = 5;

    /**
     * Vietnamese & English stopwords excluded from lexical keyword weighting.
     */
    private const STOPWORDS = [
        'the', 'and', 'for', 'with', 'about', 'what', 'how', 'when', 'where', 'who', 'can', 'you',
        'are', 'does', 'did', 'this', 'that', 'from', 'have', 'has', 'was', 'were', 'his', 'her',
        'cua', 'cho', 'voi', 'nhu', 'the', 'nao', 'la', 'gi', 'co', 'khong', 'duoc', 've', 'va',
        'hay', 'toi', 'ban', 'anh', 'thien', 'minh', 'trong', 'nhung', 'cac', 'mot', 'tai', 'o',
    ];

    /**
     * Answer a recruiter/visitor query using real RAG retrieval + active LLM provider.
     *
     * @param  array<int, array{role: string, text: string}>  $history
     * @return array<string, mixed>
     */
    public function ask(string $query, string $locale = 'en', array $history = []): array
    {
        $startedAt = hrtime(true);
        $cleanQuery = trim($query);
        $lang = $locale === 'vi' ? 'vi' : 'en';

        $chunks = PortfolioCorpusBuilder::buildChunks();
        $provider = $this->resolveActiveProvider();

        // 1. Retrieve Top-K grounded chunks via Hybrid Vector + Lexical BM25
        $retrieval = $this->retrieveTopChunks($cleanQuery, $chunks, $provider);
        $topChunks = $retrieval['chunks'];
        $retrievalMethod = $retrieval['method'];
        $matchScore = $retrieval['match_score'];

        // 2. Generate grounded reply via active LLM provider
        $sources = $this->formatSources($topChunks);

        if ($provider) {
            try {
                $adapter = AIProviderFactory::make($provider);
                $prompts = $this->buildGroundedPrompts($cleanQuery, $topChunks, $lang, $history);

                $llmResult = $adapter->generateChatReply(
                    $provider,
                    $prompts['system'],
                    $prompts['user'],
                    null,
                    750,
                    0.3
                );

                if (!empty($llmResult['success']) && !empty($llmResult['text'])) {
                    return [
                        'success' => true,
                        'answer' => trim((string) $llmResult['text']),
                        'mode' => 'llm-rag',
                        'retrieval_method' => $retrievalMethod,
                        'provider' => $provider->name,
                        'provider_type' => $provider->provider_type,
                        'model' => $llmResult['model_used'] ?: $provider->model,
                        'match_score' => $matchScore,
                        'latency_ms' => $this->elapsedMs($startedAt),
                        'tokens' => [
                            'prompt' => (int) ($llmResult['prompt_tokens'] ?? 0),
                            'completion' => (int) ($llmResult['completion_tokens'] ?? 0),
                            'total' => (int) ($llmResult['total_tokens'] ?? 0),
                        ],
                        'sources' => $sources,
                    ];
                }

                $fallbackReason = (string) ($llmResult['error_code'] ?: 'LLM_UNAVAILABLE');
            } catch (Throwable $e) {
                $fallbackReason = 'LLM_RUNTIME_FALLBACK';
            }
        } else {
            $fallbackReason = 'NO_ACTIVE_PROVIDER';
        }

        // 3. Honest retrieval-backed synthesis if LLM is not configured or temporarily unavailable
        return [
            'success' => true,
            'answer' => $this->buildRetrievalFallback($cleanQuery, $topChunks, $lang),
            'mode' => 'retrieval-fallback',
            'retrieval_method' => $retrievalMethod,
            'provider' => $provider?->name,
            'provider_type' => $provider?->provider_type,
            'model' => null,
            'match_score' => $matchScore,
            'latency_ms' => $this->elapsedMs($startedAt),
            'tokens' => ['prompt' => 0, 'completion' => 0, 'total' => 0],
            'fallback_reason' => $fallbackReason,
            'sources' => $sources,
        ];
    }

    /**
     * Resolve the default active AI provider configured in Admin Studio.
     */
    protected function resolveActiveProvider(): ?AiProvider
    {
        return AiProvider::where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->first();
    }

    /**
     * Score corpus chunks using dense vector cosine similarity (when available) + BM25 lexical matching.
     *
     * @param  array<int, array<string, mixed>>  $chunks
     * @return array{chunks: array<int, array<string, mixed>>, method: string, match_score: float}
     */
    protected function retrieveTopChunks(string $query, array $chunks, ?AiProvider $provider): array
    {
        if (empty($chunks)) {
            return ['chunks' => [], 'method' => 'bm25-lexical', 'match_score' => 0.0];
        }

        $lexicalScores = $this->computeLexicalScores($query, $chunks);
        $vectorScores = $this->computeVectorScores($query, $chunks, $provider);

        $usedVectors = $vectorScores !== null;
        $scored = [];

        foreach ($chunks as $idx => $chunk) {
            $lex = $lexicalScores[$idx] ?? 0.0;
            $vec = $usedVectors ? ($vectorScores[$idx] ?? 0.0) : 0.0;
            $combined = $usedVectors ? (0.65 * $vec + 0.35 * $lex) : $lex;

            $chunk['score'] = round($combined, 4);
            $chunk['vector_score'] = round($vec, 4);
            $chunk['lexical_score'] = round($lex, 4);
            $scored[] = $chunk;
        }

        usort($scored, fn (array $a, array $b) => $b['score'] <=> $a['score']);

        $top = array_slice($scored, 0, self::TOP_K);
        $topRaw = (float) ($top[0]['score'] ?? 0.0);
        $matchPct = $topRaw > 0.0 ? round(min(99.2, $topRaw * 100.0), 1) : 0.0;

        return [
            'chunks' => $top,
            'method' => $usedVectors ? 'hybrid-vector' : 'bm25-lexical',
            'match_score' => $matchPct,
        ];
    }

    /**
     * Compute normalized TF-IDF/BM25 lexical relevance scores across folded tokens + titles.
     *
     * @param  array<int, array<string, mixed>>  $chunks
     * @return array<int, float>
     */
    protected function computeLexicalScores(string $query, array $chunks): array
    {
        $queryTokens = $this->tokenize($query);
        if (empty($queryTokens)) {
            return array_fill(0, count($chunks), 0.1);
        }

        $docTokensList = [];
        $titleTokensList = [];
        $docFreq = [];

        foreach ($chunks as $idx => $chunk) {
            $tokens = $this->tokenize(($chunk['title'] ?? '') . ' ' . ($chunk['content'] ?? ''));
            $titleTokens = $this->tokenize((string) ($chunk['title'] ?? ''));

            $docTokensList[$idx] = $tokens;
            $titleTokensList[$idx] = array_flip($titleTokens);

            foreach (array_unique($tokens) as $term) {
                $docFreq[$term] = ($docFreq[$term] ?? 0) + 1;
            }
        }

        $totalDocs = max(1, count($chunks));
        $avgLen = max(1.0, array_sum(array_map('count', $docTokensList)) / $totalDocs);
        $uniqueQuery = array_values(array_unique($queryTokens));

        $rawScores = [];
        $maxPossible = 0.0001;

        foreach ($uniqueQuery as $term) {
            $df = $docFreq[$term] ?? 0;
            $idf = log(($totalDocs - $df + 0.5) / ($df + 0.5) + 1.0);
            $maxPossible += $idf * 2.5;
        }

        foreach ($chunks as $idx => $chunk) {
            $tokens = $docTokensList[$idx];
            $docLen = max(1, count($tokens));
            $freq = array_count_values($tokens);
            $score = 0.0;

            foreach ($uniqueQuery as $term) {
                $tf = $freq[$term] ?? 0;
                $df = $docFreq[$term] ?? 0;
                $idf = log(($totalDocs - $df + 0.5) / ($df + 0.5) + 1.0);

                if ($tf > 0) {
                    $k1 = 1.4;
                    $b = 0.65;
                    $bm25 = ($tf * ($k1 + 1.0)) / ($tf + $k1 * (1.0 - $b + $b * ($docLen / $avgLen)));
                    $score += $idf * $bm25;

                    if (isset($titleTokensList[$idx][$term])) {
                        $score += $idf * 0.65;
                    }
                }
            }

            $rawScores[$idx] = min(1.0, $score / $maxPossible);
        }

        return $rawScores;
    }

    /**
     * Compute cosine similarities using cached document vectors and live query embedding.
     *
     * @param  array<int, array<string, mixed>>  $chunks
     * @return array<int, float>|null
     */
    protected function computeVectorScores(string $query, array $chunks, ?AiProvider $provider): ?array
    {
        if (!$provider) {
            return null;
        }

        try {
            $adapter = AIProviderFactory::make($provider);
            $providerKey = $provider->provider_type . ':' . ($provider->id ?: 0);

            $chunkVectors = [];
            $missingIndices = [];
            $missingTexts = [];
            $cacheKeys = [];

            foreach ($chunks as $idx => $chunk) {
                $hash = sha1((string) $chunk['content']);
                $key = 'rag_vec_v1_' . sha1($providerKey . '|' . $chunk['id'] . '|' . $hash);
                $cacheKeys[$idx] = $key;

                $cachedVec = Cache::get($key);
                if (is_array($cachedVec) && !empty($cachedVec)) {
                    $chunkVectors[$idx] = $cachedVec;
                } else {
                    $missingIndices[] = $idx;
                    $missingTexts[] = $chunk['title'] . "\n" . Str::limit((string) $chunk['content'], 1200);
                }
            }

            // Embed query + any uncached chunks in compact batches (skip if huge)
            $inputsToEmbed = array_merge([$query], array_slice($missingTexts, 0, 24));
            $vectors = $adapter->embedTexts($provider, $inputsToEmbed);

            if (!is_array($vectors) || empty($vectors[0])) {
                return null;
            }

            $queryVector = $vectors[0];

            $returnedMissingCount = count($vectors) - 1;
            for ($i = 0; $i < $returnedMissingCount; $i++) {
                if (isset($missingIndices[$i]) && !empty($vectors[$i + 1])) {
                    $origIdx = $missingIndices[$i];
                    $chunkVectors[$origIdx] = $vectors[$i + 1];
                    Cache::put($cacheKeys[$origIdx], $vectors[$i + 1], now()->addHours(12));
                }
            }

            if (empty($chunkVectors)) {
                return null;
            }

            $scores = [];
            foreach ($chunks as $idx => $chunk) {
                $docVec = $chunkVectors[$idx] ?? null;
                $scores[$idx] = is_array($docVec) ? max(0.0, min(1.0, $this->cosineSimilarity($queryVector, $docVec))) : 0.0;
            }

            return $scores;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Build strict RAG system and user prompts citing retrieved DB chunks `[S1]..[SK]`.
     *
     * @param  array<int, array<string, mixed>>  $topChunks
     * @param  array<int, array{role: string, text: string}>  $history
     * @return array{system: string, user: string}
     */
    protected function buildGroundedPrompts(string $query, array $topChunks, string $lang, array $history): array
    {
        $langInstruction = $lang === 'vi'
            ? 'Reply naturally and concisely in Vietnamese.'
            : 'Reply naturally and concisely in English.';

        $system = implode("\n", [
            "You are Thien's Portfolio RAG AI Assistant helping technical recruiters and engineering managers evaluate Ho Thanh Thien (Hồ Thành Thiện).",
            $langInstruction,
            'STRICT GROUNDING RULES:',
            '1. Answer ONLY using facts explicitly present in the RETRIEVED SOURCES below ([S1], [S2], ...).',
            '2. Include inline citations like [S1] or [S2] matching the supporting sources you used.',
            '3. Do NOT fabricate numbers, job titles, skills, dates, or project claims outside the sources.',
            '4. Keep the response focused (2-5 crisp sentences or short bullet points) directly answering the user question.',
            '5. If the question cannot be answered from the retrieved sources, honestly say it is not covered in the portfolio records and share hothanhthien119@gmail.com for direct follow-up.',
        ]);

        $contextBlocks = [];
        foreach ($topChunks as $idx => $chunk) {
            $ref = 'S' . ($idx + 1);
            $contextBlocks[] = "[{$ref}] Title: {$chunk['title']} | Type: {$chunk['type']} | URL: {$chunk['url']}\n{$chunk['content']}";
        }

        $historyLines = [];
        foreach (array_slice($history, -4) as $turn) {
            $role = ($turn['role'] ?? 'user') === 'assistant' ? 'Assistant' : 'User';
            $text = trim(strip_tags((string) ($turn['text'] ?? '')));
            if ($text !== '') {
                $historyLines[] = "{$role}: " . Str::limit($text, 280);
            }
        }

        $userParts = [
            "RETRIEVED SOURCES:\n" . implode("\n\n", $contextBlocks),
        ];
        if (!empty($historyLines)) {
            $userParts[] = "RECENT CONVERSATION:\n" . implode("\n", $historyLines);
        }
        $userParts[] = "CURRENT QUESTION:\n{$query}";

        return [
            'system' => $system,
            'user' => implode("\n\n---\n\n", $userParts),
        ];
    }

    /**
     * Format clean citation metadata returned to the frontend UI.
     *
     * @param  array<int, array<string, mixed>>  $topChunks
     * @return array<int, array{ref: string, type: string, title: string, url: string, score: float}>
     */
    protected function formatSources(array $topChunks): array
    {
        $sources = [];
        foreach ($topChunks as $idx => $chunk) {
            $sources[] = [
                'ref' => 'S' . ($idx + 1),
                'type' => (string) $chunk['type'],
                'title' => (string) $chunk['title'],
                'url' => (string) $chunk['url'],
                'score' => round(((float) ($chunk['score'] ?? 0.0)) * 100, 1),
            ];
        }

        return $sources;
    }

    /**
     * Honest retrieval-grounded synthesis when LLM API is not configured or rate-limited.
     *
     * @param  array<int, array<string, mixed>>  $topChunks
     */
    protected function buildRetrievalFallback(string $query, array $topChunks, string $lang): string
    {
        if (empty($topChunks) || (float) ($topChunks[0]['score'] ?? 0.0) <= 0.0) {
            return $lang === 'vi'
                ? "Mình chưa hiểu truy vấn \"{$query}\" hoặc chưa tìm thấy từ khóa khớp trong hồ sơ (hiện đang chạy ở chế độ dự phòng). Bạn hãy thử hỏi về: dự án TourRist RAG AI, GPA UTH, kinh nghiệm Enmasys / AIPower, Spring Boot, Laravel hoặc liên hệ hothanhthien119@gmail.com."
                : "I could not match \"{$query}\" to any portfolio record (currently in retrieval fallback mode). Try asking about TourRist RAG AI, UTH GPA, Enmasys / AIPower experience, Spring Boot, Laravel, or direct contact info.";
        }

        $snippets = [];
        foreach (array_slice($topChunks, 0, 2) as $idx => $chunk) {
            $ref = 'S' . ($idx + 1);
            $cleanLine = trim(preg_replace('/\s+/', ' ', (string) $chunk['content']));
            $snippets[] = "[{$ref}] " . Str::limit($cleanLine, 210);
        }

        $prefix = $lang === 'vi'
            ? "Kết quả truy xuất trực tiếp từ dữ liệu Portfolio (chế độ dự phòng khi chưa kết nối LLM cho câu hỏi \"{$query}\"):\n• "
            : "Direct retrieval from live Portfolio database (fallback mode without live LLM completion for \"{$query}\"):\n• ";

        return $prefix . implode("\n• ", $snippets);
    }

    /**
     * Normalize & tokenize English + Vietnamese text (keeping accent-folded equivalence).
     *
     * @return array<int, string>
     */
    protected function tokenize(string $text): array
    {
        $folded = $this->foldVietnamese(mb_strtolower($text, 'UTF-8'));
        $parts = preg_split('/[^a-z0-9\+\#\.]+/', $folded, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($parts)) {
            return [];
        }

        $tokens = [];
        foreach ($parts as $token) {
            $token = trim($token, '.');
            if ($token === '' || strlen($token) < 2) {
                continue;
            }
            if (!in_array($token, self::STOPWORDS, true)) {
                $tokens[] = $token;
            }
        }

        return $tokens;
    }

    protected function foldVietnamese(string $text): string
    {
        static $map = [
            'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
            'đ' => 'd',
        ];

        return strtr($text, $map);
    }

    /**
     * @param  array<int, float>  $a
     * @param  array<int, float>  $b
     */
    protected function cosineSimilarity(array $a, array $b): float
    {
        $len = min(count($a), count($b));
        if ($len === 0) {
            return 0.0;
        }

        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < $len; $i++) {
            $va = (float) $a[$i];
            $vb = (float) $b[$i];
            $dot += $va * $vb;
            $normA += $va * $va;
            $normB += $vb * $vb;
        }

        if ($normA <= 0.0 || $normB <= 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    protected function elapsedMs(int $startedAtNs): int
    {
        return (int) max(1, round((hrtime(true) - $startedAtNs) / 1_000_000));
    }
}
