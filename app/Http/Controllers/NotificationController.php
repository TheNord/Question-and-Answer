<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt');
    }

    public function notificationCounts()
    {
        return [
            //'read' => NotificationResource::collection(auth()->user()->readNotifications->sortByDesc('created_at')->splice(0,3)),
            'unread' => NotificationResource::collection(auth()->user()->unreadNotifications->sortByDesc('created_at')),
        ];
    }

    public function markAsRead(Request $request)
    {
        auth()->user()->notifications->where('id', $request->id)->markAsRead();
    }

    public function markAsReadAll()
    {
        auth()->user()->notifications->markAsRead();
    }
}
