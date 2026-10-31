<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BlogAutomation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content_config_id',
        'frequency',
        'execution_time',
        'timezone',
        'day_of_week',
        'day_of_month',
        'articles_per_run',
        'publishing_mode',
        'interval_minutes',
        'start_date',
        'end_date',
        'next_run_at',
        'last_run_at',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'day_of_month' => 'integer',
        'articles_per_run' => 'integer',
        'interval_minutes' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Compute the next run timestamp in UTC based on configuration and current time.
     */
    public function computeNextRunAt(?Carbon $referenceNow = null): ?Carbon
    {
        $tz = $this->timezone ?: 'Asia/Ho_Chi_Minh';
        $now = ($referenceNow ?: Carbon::now($tz))->copy()->setTimezone($tz);

        // Check date range
        if ($this->end_date && $now->toDateString() > $this->end_date->toDateString()) {
            return null; // Past end date
        }

        $timeParts = explode(':', $this->execution_time ?: '08:00:00');
        $hour = (int)($timeParts[0] ?? 8);
        $minute = (int)($timeParts[1] ?? 0);
        $second = (int)($timeParts[2] ?? 0);

        $candidate = $now->copy()->setTime($hour, $minute, $second);

        switch ($this->frequency) {
            case 'daily':
                if ($candidate->lte($now)) {
                    $candidate->addDay();
                }
                break;

            case 'weekly':
                $targetDow = $this->day_of_week !== null ? (int)$this->day_of_week : Carbon::MONDAY;
                // Next occurrence of this day of week
                if ($candidate->dayOfWeek !== $targetDow || $candidate->lte($now)) {
                    $candidate->next($targetDow)->setTime($hour, $minute, $second);
                }
                break;

            case 'monthly':
                $targetDom = $this->day_of_month ? min(28, (int)$this->day_of_month) : 1;
                $candidate->day = $targetDom;
                if ($candidate->lte($now)) {
                    $candidate->addMonthNoOverflow()->setDay($targetDom)->setTime($hour, $minute, $second);
                }
                break;

            default:
                if ($candidate->lte($now)) {
                    $candidate->addDay();
                }
                break;
        }

        // Check if candidate is before start_date
        if ($this->start_date && $candidate->toDateString() < $this->start_date->toDateString()) {
            $candidate->setDate($this->start_date->year, $this->start_date->month, $this->start_date->day);
            $candidate->setTime($hour, $minute, $second);
            if ($candidate->lte($now)) {
                $candidate->addDay();
            }
        }

        // Convert back to UTC for standard database storage
        return $candidate->setTimezone('UTC');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDueForRun($query)
    {
        return $query->where('is_active', true)
                     ->whereNotNull('next_run_at')
                     ->where('next_run_at', '<=', now());
    }

    /**
     * Relationships
     */
    public function contentConfig()
    {
        return $this->belongsTo(AiContentConfig::class, 'content_config_id');
    }

    public function runs()
    {
        return $this->hasMany(BlogAutomationRun::class, 'automation_id');
    }
}
