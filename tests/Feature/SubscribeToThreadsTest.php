<?php

namespace Tests\Feature;

use Tests\DBTestCase;
use Throwable;

class SubscribeToThreadsTest extends DBTestCase
{

    protected $thread;

//    public function setUp()
//    {
//        parent::setUp();
//
//        $this->thread = create('App\Thread');
//
//    }

    /** @test */
    public function a_authenticated_user_can_subscribe_to_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1,$thread->subscriptions);

    }

    /** @test */
    public function a_authenticated_user_can_unsubscribe_from_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1,$thread->subscriptions);

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0,$thread->fresh()->subscriptions);

    }


}
