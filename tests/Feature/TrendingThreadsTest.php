<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Support\Facades\Redis;
use Tests\DBTestCase;

class TrendingThreadsTest extends DBTestCase
{
    public function setUp()
    {
        parent::setUp();

        Trending::reset();
    }

    /** @test */
    public function it_increments_a_thread_score_each_time_it_is_read()
    {

        $trending = new Trending;

        $this->assertEmpty($trending->get());
        $thread = create('App\Thread');

        $this->get($thread->path());
        $this->assertCount(1, $trending->get());

        $this->assertEquals($thread->title, $trending->get()[0]->title);


    }

}