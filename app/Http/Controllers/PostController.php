<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Events\PostCreated;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(): View|Factory|Application
    {
        return view('post.manage');
    }

    public function create(): View|Factory|Application
    {
        return view('post.create');
    }

    public function store(CreatePostRequest $request)
    {
        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);

        event(new PostCreated($post));

        Log::channel('user_actions')->info('Post Created', [
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'title' => $post->title,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post): View|Factory|Application
    {
        return view('post.show', ['post' => $post]);
    }

    public function edit(Post $post): View|Factory|Application
    {
        return view('post.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        Log::channel('user_actions')->info('Post Updated', [
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'title' => $post->title,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        Log::channel('user_actions')->info('Post Deleted', [
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}