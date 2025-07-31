<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostMail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check()) { // if user is not logged in, redirect to login page
            return to_route('login');
        }
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'content' => ['required', 'min:10'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public'); // Store the thumbnail if provided
        auth()->user()->posts()->create($validated); // associate the post with the authenticated user
 
        Mail::to(auth()->user()->email)->send(new PostMail(['name' => '', 'title' => $validated['title']])); // Send email to the user

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id); //find the post based on the id if not -> 404

        return view('posts.show', ['post' => $post]); // ['post' => $post] so that we have it avaible it the view posts.show
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $response = Gate::authorize('update', $post); // check if the user is authorized to update the post
        if ($response->allowed()) {
            return view('posts.edit', ['post' => $post]); // or we can do it in the same way as in the show method
        } else {
            echo $response->message();
        }
        return to_route('posts.show', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post); // check if the user is authorized to update the post
        $validated = $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'content' => ['required', 'min:10'],
            'thumbnail' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            File::delete(storage_path('app/public/' . $post->thumbnail)); // Delete the old thumbnail if it exists
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            unset($validated['thumbnail']); // if no thumbnail is provided, remove it from the validated data
        }

        $post->update($validated);
        return to_route('posts.show', ['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $response = Gate::authorize('delete', $post); // Check if the user is authorized to delete the post
        if ($response->allowed()) {
            if ($post->thumbnail) {
                File::delete(storage_path('app/public/' . $post->thumbnail));
            }
            $post->delete();
            return to_route('posts.index');
        } else {
            echo $response->message();
        }

        return to_route('posts.show', ['post' => $post]);
    }
}
