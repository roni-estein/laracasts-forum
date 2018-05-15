<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($user)
    {
        return auth()->user()->unreadNotifications;
    }
    public function destroy($user, $notificationId)
    {

        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }




}
