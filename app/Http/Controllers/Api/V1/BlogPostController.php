<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * List all published blog posts (or all for admin)
     */
    public function index(Request $request)
    {
        $query = BlogPost::query();

        // If not authenticated, only show published posts
        if (!$request->user('sanctum')) {
            $query->published();
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        $posts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'last_page' => $posts->lastPage(),
            ],
        ]);
    }

    /**
     * Get a specific blog post by slug or ID
     */
    public function show(Request $request, $slugOrId)
    {
        $query = BlogPost::query();

        if (is_numeric($slugOrId)) {
            $query->where('id', $slugOrId);
        } else {
            $query->where('slug', $slugOrId);
        }

        // Guests can only see published posts
        if (!$request->user('sanctum')) {
            $query->where('published', true);
        }

        $post = $query->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'image_url' => $post->image ? asset('storage/' . $post->image) : asset('images/og-image.webp'),
                'published' => (bool) $post->published,
                'published_at' => $post->published_at,
                'reading_time_minutes' => ceil(str_word_count(strip_tags($post->content)) / 200),
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ],
        ]);
    }

    /**
     * Create a new blog post (Admin Only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'published' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : BlogPost::generateSlug($validated['title']);
        $published = $validated['published'] ?? true;

        $post = BlogPost::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'published' => $published,
            'published_at' => $published ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully',
            'data' => $post,
        ], 201);
    }

    /**
     * Update an existing blog post (Admin Only)
     */
    public function update(Request $request, $id)
    {
        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $id,
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'published' => 'nullable|boolean',
        ]);

        if (isset($validated['title']) && empty($validated['slug'])) {
            $validated['slug'] = BlogPost::generateSlug($validated['title']);
        }

        if (isset($validated['published']) && $validated['published'] && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog post updated successfully',
            'data' => $post,
        ]);
    }

    /**
     * Delete a blog post (Admin Only)
     */
    public function destroy($id)
    {
        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog post deleted successfully',
        ]);
    }
}
