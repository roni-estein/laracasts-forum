<?php

namespace Tests\Feature;

use Tests\DBTestCase;

class FavouritesTest extends DBTestCase
{

    /** @test */
    public function a_non_authenticated_user_cannot_favorite_a_reply()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }


    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);


        // look at ->$this->assertDatabaseHas()
    }

    /** @test */
    public function a_user_can_only_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $this->post('replies/' . $reply->id . '/favorites');
        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }


}
