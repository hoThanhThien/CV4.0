<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use App\Observers\PortfolioContentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        if (config('app.env') === 'production' || env('APP_ENV') === 'production' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        foreach ([
            Project::class,
            Certificate::class,
            BlogPost::class,
            Skill::class,
            Experience::class,
            Technology::class,
        ] as $modelClass) {
            $modelClass::observe(PortfolioContentObserver::class);
        }
    }
}
