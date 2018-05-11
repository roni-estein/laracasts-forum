<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    //

    public function show(User $user)
    {


//        return $activites;
        return view('profiles.show',[
            'profileUser' => $user,
            'activities' => Activity::feed($user),
//            'threads' => $user->threads()->paginate(5),
        ]);
    }
}
