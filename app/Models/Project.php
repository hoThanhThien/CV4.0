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
        if (preg_match('/\+Project Description:?\s*(.*?)(?=\+Tech:|\+Deployed:|\+Deploy:|https?:\/\/|$)/is', $desc, $matches)) {
            $cleaned = trim($matches[1]);
            if (!empty($cleaned)) {
                return preg_replace('/^\+\s*/', '', $cleaned);
            }
        }

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
                if (preg_match('/^\+Project Description:?\s*(.*)$/i', $trimmed, $m)) {
                    $foundBody = true;
                    if (!empty($m[1])) {
                        $cleanLines[] = trim($m[1]);
                    }
                    continue;
                }
                $foundBody = true;
            }

            if (preg_match('/^(\+Tech:|\+Deployed:|\+Deploy:|https?:\/\/)/i', $trimmed)) {
                break;
            }

            $cleanLines[] = ltrim($trimmed, '+ ');
        }

        if (!empty($cleanLines)) {
            return implode("\n", $cleanLines);
        }

        return trim($desc);
    }
}
