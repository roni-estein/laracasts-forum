<?php

namespace Tests\Feature;

use Tests\DBTestCase;

class LockThreadsTest extends DBTestCase
{
    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $thread->lock();


        $this->post($thread->path() .'/replies',[
            'body' => 'Test Body',
            'user_id' => create('App\User')->id

        ])->assertStatus(422);


    }

    /** @test */
    public function a_non_administrator_can_not_lock_a_thread()
    {
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread');

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function an_administrator_can_lock_any_thread()
    {

        $admin = factory('App\User')->states('administrator')->create();
        $this->signIn($admin)->withExceptionHandling();

        $thread = create('App\Thread');

        $this->post(route('locked-threads.store', $thread))->assertStatus(201);
//
//        $this->assertTrue($thread->fresh()->locked);

    }




}