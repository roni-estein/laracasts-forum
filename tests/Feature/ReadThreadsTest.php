<?php

namespace Tests\Feature;

use App\Reply;

use Tests\DBTestCase;
use Throwable;

class ReadThreadsTest extends DBTestCase
{

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');

    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_a_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');


        $this->get('/threads/'. $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);

    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $userWithThreads = create('App\User');
        $userWithoutThreads = create('App\User');

        $thread1 = create('App\Thread', ['user_id' => $userWithThreads->id]);
        $thread2 = create('App\Thread', ['user_id' => $userWithThreads->id]);
        $thread3 = create('App\Thread', ['user_id' => $userWithoutThreads->id]);
        $thread4 = create('App\Thread');

        $this->get('/threads?by='.$userWithThreads->name)
            ->assertSee($thread1->title)
            ->assertSee($thread2->title)
            ->assertDontSee($thread3->title)
            ->assertDontSee($thread4->title);

    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // Given we have a thread with 2 replies, 3 replies, and 0 replies respectively
        $threadWithTwoReplies = create('App\Thread');
        create(Reply::class,['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread');
        create(Reply::class,['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;
        // When I filter all threads by popularity

        $response = $this->getJson('threads/?popular=1')->json();
        // they should be returned in order from most to least replies

        $this->assertEquals([ 3, 2, 0] , array_column($response, 'replies_count'));
    }


    /** @test */
    public function a_user_can_filter_threads_by_popularity_without_json_hack()
    {
        // Given we have a thread with 2 replies, 3 replies, and 0 replies respectively
        $threadWithTwoReplies = create('App\Thread');
        create(Reply::class,['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread');
        create(Reply::class,['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;
        // When I filter all threads by popularity

        $response = $this->get('threads?popular=1');

        // AN AWESOME WAY TO GET DATA FROM THE PAGE VIEW!!!
        // dd($response->baseResponse->original->getData()['threads']->pluck('replies_count')->toArray());

        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];
        // they should be returned in order from most to least replies

        $this->assertEquals([3,2,0], $threadsFromResponse->pluck('replies_count')->toArray());
    }

    /** @test */
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $reply = create('App\Reply');

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response);

    }

//TODO check against repo for this one.
//    /** @test */
//    public function a_user_can_request_all_replies_for_a_given_thread()
//    {
//        $thread = create('App\Thread');
//        create(Reply::class,['thread_id' => $thread->id], 2);
//
//        $response = $this->getJson($thread->path() .'/replies')->json();
//
////        dd($response);
//
//        $this->assertCount(1,$response['data']);
//    }


}
