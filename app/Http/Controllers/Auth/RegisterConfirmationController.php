<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        $user = User::whereConfirmationToken(request('token'))->first();

        if( ! $user ){
            return redirect('/threads')->with('flash', 'Unknown Token');
        }

        $user->confirm(request('token'));
        return redirect('/threads')->with('flash', 'Your account has been confirmed!');

    }
}
