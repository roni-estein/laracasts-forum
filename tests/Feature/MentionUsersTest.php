<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Notification;
use Tests\DBTestCase;

class MentionUsersTest extends DBTestCase
{
    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {

        //GIVEN WE HAVE A USER JOHN WHO IS SIGNED IN
        $john = create('App\User', ['name' => 'John']);
        $this->signIn($john);
        //AND ANOTHER USER JANE
        $jane = create('App\User', ['name' => 'Jane']);
        //IF WE HAVE A THREAD
        $thread = create('App\Thread');

        $this->assertCount(0, $jane->notifications);
        //AND JOHN DOE REPLIES TO THAT THREAD AND MENTIONS JANE
        $reply = raw('App\Reply', [
//            'thread_id' => $thread->id,
//            'user_id' => $john->id,
            'body'=>'@Jane is mentioned'
        ]);

        $this->post($thread->path() .'/replies', $reply);
        //THEN JANE SHALL BE NOTIFIED
        $this->assertCount(1, $jane->fresh()->notifications);

    }

}