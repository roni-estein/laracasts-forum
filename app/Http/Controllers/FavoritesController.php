<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
//        dd('here 1');
        $reply->favorite();

        if (request()->expectsJson()) return response(['message' => "favorited reply: {$reply->id}",204]);

        return back();
    }

    public function destroy(Reply $reply)
    {
//        dd('here 1');
        $reply->unfavorite();
        if (request()->expectsJson()) return response(['message' => "unfavorited reply: {$reply->id}",204]);
        return back();
    }
}
