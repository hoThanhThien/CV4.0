<?php

namespace App\Observers;

use App\Support\PortfolioCache;
use Illuminate\Database\Eloquent\Model;

/**
 * Clears public portfolio caches automatically whenever CMS models change.
 */
class PortfolioContentObserver
{
    public function saved(Model $model): void
    {
        PortfolioCache::forgetAll();
    }

    public function deleted(Model $model): void
    {
        PortfolioCache::forgetAll();
    }
}
