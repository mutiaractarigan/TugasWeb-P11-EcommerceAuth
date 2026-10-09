<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    private array $rules = [
        'title' => 'required|min:5|max:255',
        'body' => 'required|min:20',
    ];

    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        Gate::authorize('create', Post::class);

        return view('posts.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Post::class);

        $post = $request->user()->posts()->create($request->validate($this->rules));

        return redirect()->route('posts.show', $post)->with('success', 'Post berhasil dibuat.');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $post->update($request->validate($this->rules));

        return redirect()->route('posts.show', $post)->with('success', 'Post berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus.');
    }
}
