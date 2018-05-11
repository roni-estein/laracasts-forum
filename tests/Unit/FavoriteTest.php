<?php

namespace Tests\Unit;

use Tests\DBTestCase;


class FavoriteTest extends DBTestCase
{
    /** @test */
    public function a_guest_cannot_favorite_a_reply()
    {
        $this->expectException('Illuminate\Database\QueryException');

        $reply = create('App\Reply');

        $reply->favorite()->create();
   }


    /** @test */
    public function an_authenticated_user_can_favorite_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('/replies/' . $reply->id . '/favorites');

        $this->assertDatabaseHas('favorites',['user_id' => auth()->id(), 'favorited_id' => $reply->id, 'favorited_type' => 'App\Reply']);

    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('/replies/' . $reply->id . '/favorites');
        $this->assertCount(1, $reply->favorites);


        $this->delete('/replies/' . $reply->id . '/favorites');
        $this->assertCount(0, $reply->fresh()->favorites);

        $this->assertDatabaseHas('replies',['id' => $reply->id]);
        $this->assertDatabaseHas('threads',['id' => $reply->thread->id]);

        $this->assertDatabaseMissing('favorites',['user_id' => auth()->id(), 'favorited_id' => $reply->id, 'favorited_type' => 'App\Reply']);

    }

    /** @test */
    public function all_favorites_are_deleted_when_its_reply_is_deleted()
    {
        $author = create('App\User');
        $this->signIn($author);


        $reply = create('App\Reply', ['user_id' => $author->id]);
        $reply->favorite();


        $notTheAuthor = create('App\User');
        $this->signIn($notTheAuthor);
        $reply->favorite();

        $this->assertDatabaseHas('favorites',['user_id' => $author->id, 'favorited_id' => $reply->id, 'favorited_type' => 'App\Reply']);
        $this->assertDatabaseHas('favorites',['user_id' => $notTheAuthor->id, 'favorited_id' => $reply->id, 'favorited_type' => 'App\Reply']);


        $reply->delete();
        $this->assertDatabaseHas('threads',['id' => $reply->thread->id]);
        $this->assertDatabaseMissing('replies',['id' => $reply->id]);


        $this->assertDatabaseMissing('favorites',['user_id' => $author->id, 'favorited_id' => $reply->id, 'favorited_type' => 'App\Reply']);
        $this->assertDatabaseMissing('favorites',['user_id' => $notTheAuthor->id, 'favorited_id' => $reply->id, 'favorited_type' => 'App\Reply']);

    }
    
}