<?php

namespace Tests\Feature;

use Tests\DBTestCase;

class ParticipateInForumTest extends DBTestCase
{


    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {


        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply');


        $this->post($thread->path().'/replies', $reply->toArray());

//     replies are now loaded by vue so we cant see them in a php only load state
//        $this->get($thread->path())
//            ->assertSee($reply->body);

        $this->assertDatabaseHas('replies',['body' => $reply->body]);
    }

    /** @test */
    public function an_unauthenticated_user_may_not_participate_in_forum_threads()
    {
        $this->withExceptionHandling();
        $this->post('/threads/some-channel/1/replies', [])
        ->assertRedirect('/login');

    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply',['body' =>null]);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');


    }

    /** @test */
    public function a_unauthorized_user_cannot_delete_a_reply()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply', ['user_id' => 1]);

        $this->delete('replies/'. $reply->id)
            ->assertRedirect('login');


        $this->signIn();
        //this will be a different user
        $this->delete('replies/'. $reply->id)
            ->assertStatus(403);

    }

    /** @test */
    public function an_authorized_user_can_delete_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete('replies/'. $reply->id)
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies',['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function a_unauthorized_user_cannot_update_a_reply()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply', ['user_id' => 1]);

        $updatedText = 'you have been changed.';
        $this->patch('replies/'. $reply->id, ['body' => $updatedText])
            ->assertRedirect('login');


        $this->signIn();
        //this will be a different user
        $this->patch('replies/'. $reply->id, ['body' => $updatedText])
            ->assertStatus(403);

    }

    /** @test */
    public function an_authorized_user_can_update_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedText = 'you have been changed.';
        $this->patch('replies/'. $reply->id, ['body' => $updatedText]);

        $this->assertDatabaseHas('replies',['id' => $reply->id, 'body' => $updatedText]);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->expectException(\Exception::class);

        $this->post($thread->path().'/replies', $reply->toArray());

    }


}
