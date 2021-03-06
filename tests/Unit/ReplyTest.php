<?php

namespace Tests\Unit;

use App\Reply;
use Carbon\Carbon;
use Tests\DBTestCase;


class ReplyTest extends DBTestCase
{

    /** @test */
    public function it_has_an_owner()
    {
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    public function a_reply_knows_if_it_was_just_published()
    {
        $justPublished = create('App\Reply', ['created_at' => Carbon::now()]);
        $publishedEarlier = create('App\Reply', ['created_at' => Carbon::now()->subSeconds(61)]);

        $this->assertTrue($justPublished->wasJustPublished());
        $this->assertFalse($publishedEarlier->wasJustPublished());

    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_reply_body()
    {
        $reply = make('App\Reply',[
            'body' => '@JohnDoe is cool, but @JaneDoe is cooler',
        ]);

        $this->assertArraySubset(['JohnDoe', 'JaneDoe'],$reply->mentionedUsers());

        preg_match_all('/\@([^\s\.]+)/', $reply->body,$matches);
        $names = $matches[1];

    }

    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_with_anchor_text()
    {
        $reply = make('App\Reply',[
            'body' => 'Hello @JaneDoe.',
        ]);

        $this->assertEquals('Hello <a href="/profiles/JaneDoe">@JaneDoe</a>.', $reply->body);


    }

    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);
        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    public function a_reply_body_is_sanitized_automatically() {
        $reply = make(Reply::class, ['body' => "<script>alert('bad')</script><p>this is ok</p>"]);
        $this->assertEquals('<p>this is ok</p>', $reply->body);
    }

}
