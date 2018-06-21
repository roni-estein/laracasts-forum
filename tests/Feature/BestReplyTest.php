<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\DBTestCase;

class BestReplyTest extends DBTestCase
{
    /** @test */
    public function a_user_can_mark_which_reply_is_best()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id],2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('replies.best.store',['reply' => $replies[1]]));

        $this->assertTrue($replies[1]->fresh()->isBest());
        $this->assertFalse($replies[0]->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id],2);

        $this->assertFalse($replies[1]->isBest());
//        dd(auth()->id());
        $this->signIn();

        $this->postJson(route('replies.best.store',['reply' => $replies[1]]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());

    }

    /** @test */
    public function if_a_best_reply_is_deleted_the_thread_is_updated_to_reflect_that()
    {

        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $reply->thread->markBestReply($reply);

        //delete the best reply
        $this->deleteJson(route('replies.destroy', ['reply' => $reply]));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }


}