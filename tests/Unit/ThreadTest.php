<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Tests\DBTestCase;


class ThreadTest extends DBTestCase
{

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);

    }

    /** @test */
    public function a_thread_has_a_creator()
    {

        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_have_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function e()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function a_thread_has_a_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->slug}",
            $this->thread->path());
    }

    /** @test */
    public function a_thread_can_be_susbcribed_to()
    {
        // Given we have a thread and an authenticated user
        $this->signIn();
        $thread = create('App\Thread');

        // when a user subscriber to the thread
        $thread->subscribe($userId = auth()->id());

        // we should be able to get all the threads he user has subscribed to

        $this->assertEquals(1, $thread->subscriptions()->where(['user_id' => auth()->id()])->count());

        $thread->unsubscribe($userId = auth()->id());

        $this->assertEquals(0, $thread->subscriptions()->where(['user_id' => auth()->id()])->count());

    }

    /** @test */
    public function it_knows_if_a_user_is_subscribed_to_it()
    {
//        $notTheAuthor = create('App\User');

//        dd($this->thread->creator->id);
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedTo);
        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribedTo);

        $this->signIn();

        $this->assertFalse($this->thread->fresh()->isSubscribedTo);
    }


    /** @test */
    public function a_thread_notifies_all_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'user_id' => create('App\User')->id,
                'body' => 'Foobar'
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        // a laracasts thing, I don't like this but to get away from using so many auth()->user() calls
        tap(auth()->user(), function ($user) use ($thread) {

            $this->assertTrue($thread->hasUpdatesFor($user));
            //simulate that the user visited the thread
            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));

        });


    }

    /** @test */
    public function a_thread_can_be_locked()
    {
        $this->assertFalse($this->thread->locked);

        $this->thread->lock();

        $this->assertTrue($this->thread->locked);
    }


}
