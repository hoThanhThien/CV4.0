<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'github_url', 'demo_url', 'featured', 'order'
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skill');
    }

    public function technologies()
    {
        return $this->skills();
    }

    /**
     * Clean readable description excerpt without raw seed metadata prefixes.
     */
    public function getCleanDescriptionAttribute(): string
    {
        $desc = (string) $this->description;
        $lines = preg_split('/\r\n|\r|\n/', $desc);
        $cleanLines = [];
        $foundBody = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                continue;
            }

            if (!$foundBody) {
                if (str_contains($trimmed, '|') || preg_match('/^(Developer|Fullstack|Backend|Frontend|Back End|Front End|Mobile|Engineer)/i', $trimmed)) {
                    continue;
                }
                if (preg_match('/^\+?\s*Project Description:?\s*(.*)$/i', $trimmed, $m)) {
                    $foundBody = true;
                    if (!empty($m[1])) {
                        $cleanLines[] = trim(ltrim($m[1], '+ '));
                    }
                    continue;
                }
                $foundBody = true;
            }

            if (preg_match('/^(\+?\s*Tech:|\+?\s*Deployed:|\+?\s*Deploy:|https?:\/\/)/i', $trimmed)) {
                break;
            }

            $cleaned = trim(ltrim($trimmed, '+ '));
            if ($cleaned !== '') {
                $cleanLines[] = $cleaned;
            }
        }

        if (!empty($cleanLines)) {
            return implode("\n", $cleanLines);
        }

        return trim(ltrim($desc, '+ '));
    }

    /**
     * First concise summary sentence for cards and previews.
     */
    public function getSummaryAttribute(): string
    {
        $clean = $this->clean_description;
        $lines = preg_split('/\r\n|\r|\n/', $clean);
        return trim($lines[0] ?? '');
    }

    /**
     * Array of clean achievement / engineering bullet points.
     *
     * @return array<int, string>
     */
    public function getHighlightsAttribute(): array
    {
        $clean = $this->clean_description;
        $lines = preg_split('/\r\n|\r|\n/', $clean);
        $items = [];
        foreach ($lines as $line) {
            $t = trim($line);
            if ($t !== '') {
                $items[] = $t;
            }
        }
        return $items;
    }
}
