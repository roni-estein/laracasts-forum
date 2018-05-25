<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try{
            User::whereConfirmationToken(request('token'))
                ->firstOrFail()
                ->confirm(request('token'));
        }catch (\Exception $e){

            return redirect('/threads')->with('flash', 'Unknown Token');
        }

        return redirect('/threads')->with('flash', 'Your account has been confirmed!');


    }
}
