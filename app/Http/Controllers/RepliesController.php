<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\AddReplyRequest;
use App\Reply;
use App\Thread;


class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }


    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($channel_id, Thread $thread, AddReplyRequest $form)
    {
//        return $form->persist($thread);

        if ($thread->locked) {
            return response('Thread is locked.', 422);
        }

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);


        return $reply->load('owner');
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

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate(['body' => 'required|spamfree']);

        $reply->body = request('body');
        $reply->save();


        return response(['message' => 'Reply Updated'], 204);
        //return back()->with('flash', 'Reply Updated');
    }


}
