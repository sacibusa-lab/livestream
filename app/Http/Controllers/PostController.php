<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'body'            => ['required', 'string'],
            'featured_image'  => ['nullable', 'url', 'max:2048'],
            'category'        => ['nullable', 'string', 'max:100'],
            'is_published'    => ['nullable', 'boolean'],
            'published_at'    => ['nullable', 'date'],
        ]);

        $validated['slug']         = BlogPost::generateSlug($validated['title']);
        $validated['category']     = $validated['category'] ?? 'News';
        $validated['is_published'] = $request->has('is_published') ? true : false;
        $validated['published_at'] = $validated['published_at'] ?? now();

        BlogPost::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(int $id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, int $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'body'            => ['required', 'string'],
            'featured_image'  => ['nullable', 'url', 'max:2048'],
            'category'        => ['nullable', 'string', 'max:100'],
            'is_published'    => ['nullable', 'boolean'],
            'published_at'    => ['nullable', 'date'],
        ]);

        // Regenerate slug only if title changed
        if ($validated['title'] !== $post->title) {
            $validated['slug'] = BlogPost::generateSlug($validated['title'], $post->id);
        }

        $validated['category']     = $validated['category'] ?? 'News';
        $validated['is_published'] = $request->has('is_published') ? true : false;
        $validated['published_at'] = $validated['published_at'] ?? $post->published_at;

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(int $id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
