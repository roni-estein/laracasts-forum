<?php

namespace Tests\Unit;

use App\Activity;
use App\Thread;
use Carbon\Carbon;
use Tests\DBTestCase;

class UserTest extends DBTestCase
{
    /** @test */
    public function a_user_cn_fetch_their_most_recent_reply()
    {
        $user = create('App\User');
//        $reply = create('App\Reply');
        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);

    }

    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $user = create('App\User', ['avatar_path' => '/avatars/me-test.jpg']);
        $this->assertEquals(asset('/avatars/me-test.jpg'), $user->avatar());

        $user2 = create('App\User');
        $this->assertEquals(asset('/avatars/me-test.jpg'), $user->avatar());
    }


}