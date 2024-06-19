<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


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

    
    public function updateStatus(Request $request, Post $post): RedirectResponse
    {
        $post->isValid = $request->input('isValid');
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post status updated successfully.');
    }

    public function manageIndex(): View|Factory|Application
    {
        return view('post.managepost');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    

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
     * @return void
     */
    public function store(CreatePostRequest $request): void
    {
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
        // $this->authorize('viewAny', auth()->user());

        return view('livewire.validation.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     *
     * @return void
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            // Add any other validation rules
        ]);

        $post->update($request->all());

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     *
     * @return void
     */
    public function destroy(Post $post)
    {
        // $this->authorize('viewAny', auth()->user());
    
        try {
            $post->delete();
        } catch (QueryException $e) {
            // Handle the exception, e.g., show an error message
            return back()->withError('Cannot delete this user due to related records.');
        }
    
        // Redirect somewhere after deletion
        return redirect()->route('admin.validate-posts');
    }
}
