<?php

namespace App\Http\Middleware;

use App\Services\AI\Support\AiSchemaManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        // Automatically ensure AI Blog Studio database tables & columns exist on production/hosting
        if (!AiSchemaManager::isReady()) {
            AiSchemaManager::ensureMigrated();
        }

        return $next($request);
    }
}
