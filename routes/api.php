<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DocsController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\BlogPostController;
use App\Http\Controllers\Api\V1\SkillController;
use App\Http\Controllers\Api\V1\ExperienceController;
use App\Http\Controllers\Api\V1\ContactController;

/*
|--------------------------------------------------------------------------
| API Documentation (Swagger UI & OpenAPI Spec)
|--------------------------------------------------------------------------
| Access at: /api/docs and /api/docs/openapi.json
*/
Route::get('/docs', [DocsController::class, 'index'])->name('api.docs');
Route::get('/docs/openapi.json', [DocsController::class, 'openapi'])->name('api.docs.openapi');

/*
|--------------------------------------------------------------------------
| API Version 1 Routes (/api/v1/*)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // === Authentication ===
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });

    // === Projects (Public & Protected CRUD) ===
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    });

    // === Blog Posts (Public & Protected CRUD) ===
    Route::get('/blog', [BlogPostController::class, 'index']);
    Route::get('/blog/{slug}', [BlogPostController::class, 'show']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/blog', [BlogPostController::class, 'store']);
        Route::put('/blog/{id}', [BlogPostController::class, 'update']);
        Route::delete('/blog/{id}', [BlogPostController::class, 'destroy']);
    });

    // === Skills & Experiences ===
    Route::get('/skills', [SkillController::class, 'index']);
    Route::get('/experiences', [ExperienceController::class, 'index']);

    // === Contact Submission ===
    Route::post('/contact', [ContactController::class, 'store']);
});
