<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('post.manage');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePostRequest $request)
    {
        // Validate and create the post
        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => auth()->id(), // assuming posts are associated with a user
        ]);

        // Log the creation of the post
        Log::channel('user_actions')->info('Post Created', [
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'title' => $post->title,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     *
     * @return void
     */
    public function show(Post $post): void
    {
        // You can add your logic here if needed
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     *
     * @return Application|Factory|View
     */
    public function edit(Post $post): View|Factory|Application
    {
        return view('post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Update the post
        $post->update($validated);

        // Log the update
        Log::channel('user_actions')->info('Post Updated', [
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'title' => $post->title,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        // Delete the post
        $post->delete();

        // Log the deletion
        Log::channel('user_actions')->info('Post Deleted', [
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Post deleted successfully!');
    }
}
