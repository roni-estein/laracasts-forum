<?php

namespace Tests\Unit;

use Tests\DBTestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends DBTestCase
{

    /** @test */
    public function a_channel_consists_of_threads()
    {
        $channel = create('App\Channel');
        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }

}
