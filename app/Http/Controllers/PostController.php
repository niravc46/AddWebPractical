<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\PostImage;
use App\Models\User;
use App\Notifications\PostPublished;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * View all posts
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $author = $request->input('author');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $authors = User::whereHas('roles', function ($query) {
            $query->where('name', 'Author');
        })->get();

        $query = Post::query();

        // Search by title or content
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        if ($author) {
            $query->where('user_id', $author);
        }

        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Order by latest updated posts and paginate
        $posts = $query->orderBy('updated_at', 'desc')->paginate(5);

        return view('posts.index', compact('posts', 'search', 'authors', 'author', 'startDate', 'endDate'));
    }
    /**
     * view post create form
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $validated = $request->validated();
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'content' => $request->content,
                'created_by_role' => Auth::user()->hasRole('Admin') === true ? 'Admin' : 'Author'
            ]);

            //  Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('public/posts');
                    PostImage::create([
                        'post_id' => $post->id,
                        'image_path' => Storage::url($path)
                    ]);
                }
            }
            // Notify all users
            $users = User::where('id', '!=', Auth::id())->get();

            Notification::send($users, new PostPublished($post));

            return redirect()->route('posts.index')->with('success', 'Post created successfully.');
        } catch (Exception $e) {
            Log::error('Post create failed: ' . $e->getMessage());
            return redirect()->route('posts.index')->with('error', 'An error occurred while creating the post.');
        }
    }

    /**
     * view post.
     */
    public function show(Request $request, $id)
    {
        try {
            $post = Post::with('images')->findOrFail($id);
            return view('posts.view', compact('post'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('posts.index')->with('error', 'Post not found');
        }
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {

        try {
            $post = Post::with('images')->findOrFail($id);
            if (Auth::user()->hasRole('Author') && $post->user_id != Auth::id()) {
                return redirect()->route('posts.index')->with('error', 'Unauthorized access');
            }
            return view('posts.edit', compact('post'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('posts.index')->with('error', 'Post not found');
        }
    }

    /**
     * Edit post.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            // Check User authorization
            if (Auth::user()->hasRole('Author') && $post->user_id != Auth::id()) {
                return redirect()->route('posts.index')->with('error', 'Unauthorized access');
            }

            $validated = $request->validated();

            // Update Post
            $post->update([
                'title' => $request->input('title'),
                'content' => $request->input('content')
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                // Delete old images
                $oldImages = PostImage::where('post_id', $post->id)->get();
                foreach ($oldImages as $oldImage) {
                    Storage::delete('public/posts/' . basename($oldImage->image_path));
                    $oldImage->delete();
                }

                // Store new images
                foreach ($request->file('images') as $image) {
                    $path = $image->store('public/posts');
                    PostImage::create([
                        'post_id' => $post->id,
                        'image_path' => Storage::url($path)
                    ]);
                }
            }

            return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Post update failed: ' . $e->getMessage());
            return redirect()->route('posts.index')->with('error', 'An error occurred while updating the post.');
        }
    }

    /**
     * Delete post.
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            // Author can only delete their own posts
            if (Auth::user()->hasRole('Author') && $post->user_id != Auth::id()) {
                return redirect()->route('posts.index')->with('error', 'Unauthorized access');
            }
            // Delete the post and associated images
            $post->images()->delete();
            $post->delete();
            return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('posts.index')->with('error', 'Post not found');
        }
    }
}
