<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;
use App\Thread;
use App\Trending;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;


/**
 * Class ThreadController
 * @package App\Http\Controllers
 */
class ThreadsController extends Controller
{
    /**
     * @var Trending
     */
    protected $trending;
    /**
     * ThreadController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('must-be-confirmed')->only(['store']);

        $this->trending = new Trending;
    }


    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     *
     * @return mixed
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        // Hack for ordering test

        if( request()->wantsJson()){
            return $threads;
        }
        return view('threads.index')->withThreads($threads)->withTrending($this->trending->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {

        request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required',$recaptcha],
        ]);

        $thread = Thread::create([
            'title' => $request->title,
            'channel_id' => $request->channel_id,
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        if ($request->wantsJson()){
            return response($thread,201);
        }

        return redirect($thread->path())->with('flash','Your thread has been published!');
    }

    public function update(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
        'title' => 'required|spamfree',
        'body' => 'required|spamfree',

        ]));

        return $thread;

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($channel_id, $slug)
    {

        $thread = Thread::whereSlug($slug)->first();

        if(auth()->check()){
            auth()->user()->read($thread);
        }

        $this->trending->push($thread);
        $thread->increment('visits');

        return view('threads.show')->withThread($thread);
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int $id
//     * @return Response
//     */
//    public function edit($id)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if( request()->wantsJson()){
            return \response('ok', 204);
        }

        return redirect('/threads');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::filter($filters)->latest();

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->paginate(5);
        return $threads;
    }
}
