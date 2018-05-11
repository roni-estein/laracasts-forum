<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use App\User;
use Tests\DBTestCase;


class ThreadTest extends DBTestCase
{

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

        $this->assertCount(1,$this->thread->replies);
    }

    /** @test */
    public function e()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $this->assertEquals("/threads/{$this->thread->channel->slug}/{$this->thread->id}",$this->thread->path());
    }
}
