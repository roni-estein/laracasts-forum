<?php

namespace Tests\Unit;

use App\Activity;
use App\Thread;
use Carbon\Carbon;
use Tests\DBTestCase;

class USerTest extends DBTestCase
{
    /** @test */
    public function a_user_cn_fetch_their_most_recent_reply()
    {
        $user = create('App\User');
//        $reply = create('App\Reply');
        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);


    }


}