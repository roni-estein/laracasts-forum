<?php

namespace Tests\Unit;

use App\Activity;
use Carbon\Carbon;
use Tests\DBTestCase;

class HelpersTest extends DBTestCase
{
    /** @test */
    public function a_create_object_helper_creates_an_object()
    {
        $thread = create('App\Thread');
        $this->assertDatabaseHas('threads', ['id' => $thread->id, 'body' => $thread->body]);

        $reply = create('App\Reply');
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $reply->body]);
    }

    /** @test */
    public function a_signIn_helper_keeps_state()
    {
        $user = create('App\User');
        $this->signIn($user);

        $this->assertEquals(auth()->id(), $user->id);

        $thread = create('App\Thread');
        $this->assertDatabaseHas('threads', ['id' => $thread->id, 'body' => $thread->body]);

        $this->assertEquals(auth()->id(), $user->id);

    }


}