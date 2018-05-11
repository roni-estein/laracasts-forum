<?php

namespace Tests\Unit;

use App\Activity;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Tests\DBTestCase;

class ActivityTest extends DBTestCase
{
    /** @test */
    public function a_user_keeps_state()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');


        $this->assertEquals(auth()->id(), $user->id);
        $this->assertEquals(auth()->id(), Thread::testStateRetention());

    }


    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread',
        ]);

        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply');




        $this->assertDatabaseHas('activities', [
            'type' => 'created_reply',
            'user_id' => auth()->id(),
            'subject_id' => $reply->id,
            'subject_type' => 'App\Reply',
        ]);

        $activity = Activity::find(2);
        $this->assertEquals($activity->subject->id, $reply->id);
        $this->assertEquals(Activity::count(), 2);
    }

    /** @test */
    public function a_it_records_activity_when_a_reply_is_favorited()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $reply->favorite();

        $this->assertDatabaseHas('activities', [
            'type' => 'created_favorite',
            'user_id' => auth()->id(),
            'subject_id' => 1,
            'subject_type' => 'App\Favorite',
        ]);

    }

    /** @test */
    public function it_removes_activity_when_a_reply_is_deleted()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $reply->favorite();

        $this->assertDatabaseHas('activities', [
            'type' => 'created_favorite',
            'user_id' => auth()->id(),
            'subject_id' => 1,
            'subject_type' => 'App\Favorite',
        ]);

        $reply->delete();

        $this->assertDatabaseMissing('activities', [
            'type' => 'created_favorite',
            'user_id' => auth()->id(),
            'subject_id' => 1,
            'subject_type' => 'App\Favorite',
        ]);
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();
        //Given two threads,
        create('App\Thread',['user_id' => auth()->id()], 2);

        //dd(User::first());
        //one created now and one a week ago
        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);
        //When getting the feed,


        $feed = Activity::feed(auth()->user());

        //assert they are both in the collection

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }


}