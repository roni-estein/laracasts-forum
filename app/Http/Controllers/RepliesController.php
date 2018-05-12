<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($channel_id, Thread $thread)
    {

        $this->validate(request(), ['body' => 'required']);

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);


        if (request()->expectsJson()) {
            return $reply->load('owner');

        }

        return back()->with('flash', 'Your reply has been left');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply Deleted'], 204);
        }
        return back()->with('flash', 'Your reply was removed');
    }

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate($request, [
            'body' => 'required',
        ]);

        $reply->body = $request->body;
        $reply->save();

        return response(['message' => 'Reply Updated'], 204);
        //return back()->with('flash', 'Reply Updated');
    }


}
