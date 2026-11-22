<?php

namespace App\Models\Concerns;

/**
 * Shared bilingual (EN | VI) attribute resolution & sanitization across CMS models.
 */
trait HasBilingualTranslations
{
    /**
     * Automatically cast `translations` JSON column to array when trait is used.
     */
    public function initializeHasBilingualTranslations(): void
    {
        $this->mergeCasts(['translations' => 'array']);
    }

    /**
     * Fetch a non-empty translation string for a specific field & locale, or null if absent.
     */
    public function translationValue(string $field, string $locale): ?string
    {
        $translations = is_array($this->translations) ? $this->translations : [];
        $val = $translations[$locale][$field] ?? null;

        if (is_string($val) && trim($val) !== '') {
            return trim($val);
        }

        return null;
    }

    /**
     * Resolve a field in the target or active locale, gracefully falling back to the base attribute.
     */
    public function localized(string $field, ?string $locale = null): mixed
    {
        $targetLocale = $locale ?: app()->getLocale();
        $translated = $this->translationValue($field, $targetLocale);

        if ($translated !== null) {
            return $translated;
        }

        return $this->getAttribute($field);
    }

    public function getLocalizedTitleAttribute(): string
    {
        return (string) $this->localized('title');
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        return (string) $this->localized('description');
    }

    /**
     * Clean incoming `translations[en|vi][field]` form/API payloads, stripping empty values.
     *
     * @param  mixed  $raw
     * @param  array<int, string>  $allowedFields
     * @return array<string, array<string, string>>|null
     */
    public static function sanitizeTranslations(mixed $raw, array $allowedFields): ?array
    {
        if (!is_array($raw)) {
            return null;
        }

        $clean = [];
        foreach (['en', 'vi'] as $locale) {
            if (empty($raw[$locale]) || !is_array($raw[$locale])) {
                continue;
            }

            foreach ($allowedFields as $field) {
                $val = $raw[$locale][$field] ?? null;
                if (is_string($val) && trim($val) !== '') {
                    $clean[$locale][$field] = trim($val);
                }
            }
        }

        return empty($clean) ? null : $clean;
    }
}
