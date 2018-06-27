<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
        $search = request('q');

        $threads = Thread::search($search)->paginate(25);

        if(request()->expectsJson()) {
            return $threads->items();
        }

        return view('threads.index')->withThreads($threads)->withTrending($trending->get());
    }
}
