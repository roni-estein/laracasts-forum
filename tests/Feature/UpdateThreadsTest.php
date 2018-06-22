<?php

namespace Tests\Feature;

use Tests\DBTestCase;

class UpdateThreadsTest extends DBTestCase
{


    /** @test */
    public function a_thread_can_be_updated_by_an_authorized_user()
    {
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
//        $thread = create('App\Thread');

        $this->patch($thread->path(), [
            'title' => 'Changed Title',
            'body' => 'Changed Body.'
        ]);

        $thread = $thread->fresh();

        $this->assertEquals('Changed Body.', $thread->body);
        $this->assertEquals('Changed Title', $thread->title);
    }


    /** @test */
    public function unauthorized_users_may_not_update_threads()
    {
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread');

        $this->patch($thread->path(), [
            'title' => 'Changed Title',
            'body' => 'Changed Body.'
        ])->assertStatus(403);

        $thread = $thread->fresh();

        $this->assertNotEquals('Changed Body.', $thread->body);
        $this->assertNotEquals('Changed Title', $thread->title);
    }

    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);


        $this->patch($thread->path(), [
            'title' => 'Changed Title',
        ])->assertSessionHasErrors(['body']);

        $this->patch($thread->path(), [
            'body' => 'Changed Body.'
        ])->assertSessionHasErrors(['title']);

    }
}