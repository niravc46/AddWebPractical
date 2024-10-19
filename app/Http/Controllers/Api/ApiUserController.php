<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiUserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->search;

            $users = User::where('id', '!=', Auth::id());

            if ($search) {
                $users = $users->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }
            $users = $users->paginate(10);

            return UserResource::collection($users)
                ->additional(['message' => 'Users retrieved successfully', 'status' => 200])
                ->response()->setStatusCode(200);
        } catch (Exception $e) {
            Log::info("ApiUserController->index" . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return (new UserResource($user))
                ->additional(['message' => 'User retrieved successfully', 'status' => 200])
                ->response()->setStatusCode(200);
        } catch (Exception $e) {
            Log::info("ApiUserController->show" . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
