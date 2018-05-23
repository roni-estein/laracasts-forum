<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([]);
    }

    public function store()
    {
        $this->validate(request(),[
            'avatar' => 'required|image'
        ]);

        auth()->user()->update([
           'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);


        return response(['message'=>'ok'],  204);

    }

}
