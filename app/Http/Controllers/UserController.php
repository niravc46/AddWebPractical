<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * View all users
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('id', '!=', Auth::id());

        if ($search) {
            $users = $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        $users = $users->paginate(5);
        return view('users.index', compact('users', 'search'));
    }

    /**
     * view user.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            if (Auth::user()->hasRole('Author')) {
                return redirect()->route('users.index')->with('error', 'Unauthorized access');
            }
            return view('users.view', compact('user'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
    }
}
