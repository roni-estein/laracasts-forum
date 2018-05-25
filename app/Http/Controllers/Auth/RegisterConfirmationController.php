<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        User::whereConfirmationToken(request('token'))
            ->firstOrFail()
            ->confirm(request('token'));

        return redirect('/threads')->with('flash', 'Your account has been confirmed!');


    }
}
