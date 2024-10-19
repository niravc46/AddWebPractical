<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $post_id = $notification->data['post_id'];
        $notification->markAsRead();

        // return redirect()->back()->with('success', 'Notification marked as read.');
        return redirect()->route('posts.show', $post_id)->with('success', 'Notification marked as read.');
    }
}
