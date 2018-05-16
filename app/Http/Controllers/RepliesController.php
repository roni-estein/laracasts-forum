<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
use App\Thread;
use Illuminate\Http\Request;

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
    public function store($channel_id, Thread $thread, Spam $spam)
    {

        try {
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response("Sorry your reply can't be posted at this time", 422);
        }

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

    public function update(Request $request, Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        try {
            $this->validateReply();

            $reply->body = $request->body;
            $reply->save();

        } catch (\Exception $e) {
            return response("Sorry your reply can't be posted at this time", 422);
        }
        return response(['message' => 'Reply Updated'], 204);
        //return back()->with('flash', 'Reply Updated');
    }

    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);
        resolve(Spam::class)->detect(request('body'));
    }

}
