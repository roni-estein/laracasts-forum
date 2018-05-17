<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\AddReplyRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;
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
    public function store($channel_id, Thread $thread, AddReplyRequest $form)
    {
//        return $form->persist($thread);

        $reply =  $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        // Inspect the body of the reply for username mentions
        preg_match_all('/\@([^\s\.]+)/', $reply->body,$matches);
        $names = $matches[1];
//        dd($names);
        // And then for each mentioned user send notification
        foreach ($names as $name){
            $user = User::whereName($name)->first();

            if($user){

                $user->notify(new YouWereMentioned($reply));
            }
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

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required|spamfree']);

        $reply->body = $request->body;
        $reply->save();


        return response(['message' => 'Reply Updated'], 204);
        //return back()->with('flash', 'Reply Updated');
    }


}
