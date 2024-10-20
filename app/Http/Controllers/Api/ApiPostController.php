<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Notifications\PostPublished;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ApiPostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            $author = $request->query('author');
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');

            $query = Post::query();

            if ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            }

            if ($author) {
                $query->where('user_id', $author);
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $posts = $query->paginate(10);

            return PostResource::collection($posts)
                ->additional(['message' => 'Posts retrieved successfully', 'status' => 200])
                ->response()->setStatusCode(200);
        } catch (Exception $e) {
            Log::info("ApiPostController->index" . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a single post
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            return (new PostResource($post))
                ->additional(['message' => 'Post retrieved successfully', 'status' => 200])
                ->response()->setStatusCode(200);
        } catch (Exception $e) {
            Log::info("ApiPostController->show" . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            // Create the post
            $post = Post::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'user_id' => Auth::id(),
            ]);

            // Handle file uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('post_images', 'public');
                    $post->images()->create(['image_path' => $imagePath]);
                }
            }

            // Notify all users
            $users = User::where('id', '!=', Auth::id())->get();
            Notification::send($users, new PostPublished($post));

            DB::commit();
            return (new PostResource($post))
                ->additional(['message' => 'Post created successfully', 'status' => 201])
                ->response()->setStatusCode(201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info("ApiPostController->store" . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(StorePostRequest $request, $id)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $post = Post::findOrFail($id);

            if (Auth::id() !== $post->user_id && !Auth::user()->hasRole('Admin')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            // Update the post
            $post->update($validated);


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
            DB::commit();
            return (new PostResource($post))
                ->additional(['message' => 'Post updated successfully', 'status' => 200])
                ->response()->setStatusCode(200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info("ApiPostController->update" . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update post'
            ], 500);
        }
    }

    // Delete a post
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $post = Post::findOrFail($id);
            if (Auth::id() !== $post->user_id && !Auth::user()->hasRole('Admin')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $post->delete();
            DB::commit();
            return response()->json([
                'message' => 'Post deleted successfully'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
