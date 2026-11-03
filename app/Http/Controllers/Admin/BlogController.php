<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\BlogPost;
use App\Services\AI\BlogImageGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(10);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:4096',
            'og_image' => 'nullable|image|max:4096',
            'cover_image_prompt' => 'nullable|string|max:1500',
            'published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog', 'public');
        }
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('blog/og', 'public');
        }

        $validated['published'] = $request->boolean('published');
        $validated['status'] = $validated['published'] ? BlogPost::STATUS_PUBLISHED : BlogPost::STATUS_DRAFT;
        $validated['slug'] = BlogPost::generateSlug($validated['title']);
        $validated['published_at'] = $validated['published'] ? now() : null;

        $post = BlogPost::create($validated);

        if ($request->boolean('auto_generate_ai_image') && !$request->hasFile('image')) {
            app(BlogImageGenerator::class)->generateForPost($post, null, null, null, $validated['cover_image_prompt'] ?? null);
        }

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully!');
    }

    public function show($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.blog.show', compact('post'));
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $providers = AiProvider::active()->get();
        return view('admin.blog.edit', compact('post', 'providers'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:4096',
            'og_image' => 'nullable|image|max:4096',
            'cover_image_prompt' => 'nullable|string|max:1500',
            'published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image && !str_starts_with($post->image, 'http')) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('blog', 'public');
        } else {
            unset($validated['image']);
        }

        if ($request->hasFile('og_image')) {
            if ($post->og_image && !str_starts_with($post->og_image, 'http')) {
                Storage::disk('public')->delete($post->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('blog/og', 'public');
        } else {
            unset($validated['og_image']);
        }

        $wasPublished = $post->published;
        $validated['published'] = $request->boolean('published');
        $validated['status'] = $validated['published'] ? BlogPost::STATUS_PUBLISHED : BlogPost::STATUS_DRAFT;

        if (!$wasPublished && $validated['published']) {
            $validated['published_at'] = now();
        } elseif (!$validated['published']) {
            $validated['published_at'] = null;
        }

        $post->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully!');
    }

    public function generateImage(Request $request, $id, BlogImageGenerator $imageGenerator)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'ai_provider_id' => 'nullable|exists:ai_providers,id',
            'image_model' => 'nullable|string|max:100',
            'image_style' => 'nullable|in:modern_tech,3d_isometric,minimalist_gradient,editorial_illustration,cyberpunk_neon',
            'custom_prompt' => 'nullable|string|max:1500',
        ]);

        $provider = !empty($validated['ai_provider_id']) ? AiProvider::find($validated['ai_provider_id']) : null;
        $result = $imageGenerator->generateForPost(
            $post,
            $provider,
            $validated['image_model'] ?? null,
            $validated['image_style'] ?? 'modern_tech',
            $validated['custom_prompt'] ?? null
        );

        if ($request->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        if ($post->image && !str_starts_with($post->image, 'http')) {
            Storage::disk('public')->delete($post->image);
        }
        if ($post->og_image && $post->og_image !== $post->image && !str_starts_with($post->og_image, 'http')) {
            Storage::disk('public')->delete($post->og_image);
        }

        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully!');
    }
}
