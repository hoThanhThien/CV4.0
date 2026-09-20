<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'company', 'position', 'description', 'start_date', 'end_date', 'current', 'order'
    ];

    protected $casts = [
        'current' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getLocalizedPositionAttribute(): string
    {
        return __($this->position);
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        return __($this->description);
    }

    public function getFormattedDateAttribute(): string
    {
        $isVi = app()->getLocale() === 'vi';
        $start = $isVi ? $this->start_date->format('m/Y') : $this->start_date->format('M Y');

        if ($this->current) {
            $end = __('Present');
        } elseif ($this->end_date) {
            $end = $isVi ? $this->end_date->format('m/Y') : $this->end_date->format('M Y');
        } else {
            $end = '?';
        }

        return "{$start} — {$end}";
    }

    public function getTechTagsAttribute(): array
    {
        if (str_contains($this->company, 'Caro')) {
            return ['ReactJS', 'Real-time Logic', 'Frontend Optimization'];
        }
        if (str_contains($this->company, 'Laundry')) {
            return ['PHP / Laravel', 'RESTful API', 'RBAC', 'Database'];
        }
        if (str_contains($this->company, 'AIPower')) {
            return ['PHP', 'WordPress', 'PostgreSQL', 'JavaScript'];
        }
        return [];
    }
}
