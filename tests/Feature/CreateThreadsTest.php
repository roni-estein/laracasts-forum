<?php

namespace Tests\Feature;

use App\Activity;
use Tests\DBTestCase;

class CreateThreadsTest extends DBTestCase
{

    /** @test */
    public function guests_may_not_create_new_threads()
    {

        $this->withExceptionHandling();

        //opening create sends you to login - unauthenticated
        $this->get('/threads/create')
            ->assertRedirect('/login');

        //trying to submit a post request to the endpoint send you back to login - unauthenticated
        $this->post('/threads', [])
            ->assertRedirect('/login');



    }

    /** @test */
    public function authenticated_users_must_first_confirm_their_email_address_before_creating_theads()
    {

        $this->publishThread()->assertRedirect('/threads')->assertSessionHas('flash', 'You must confirm your email address.');

    }


    /** @test */
    public function an_authenticated_user_can_create_new_form_threads()
    {
        //given a singed in user
        $this->signIn();
        $thread = make('App\Thread');

        //when we hit an endpoint to create a new thread

        $response = $this->post('/threads', $thread->toArray());
        //then when we visit the threads page we should see a new thread
//        dd($response->headers->get('location'));

//        since we need to perform the post action, we can't use the create before.
//        In the application the post action will store the new data and redirect to the page with the primary id.
//        so the success test has to read that page to determine success.

        $this->get($response->headers->get('location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);

    }


    /** @test */
    public function a_guest_cannot_see_the_create_thread_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');

    }


    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {

        create('App\Channel');

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');


        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_guest_cannnot_delete_a_thread()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');
    }


    /** @test */
    public function an_unauthorized_user_cannnot_delete_a_thread()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertStatus(403);
    }


    /** @test */
    public function an_authorized_user_can_delete_their_threads()
    {

        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());

    }

    /** @test */
    public function threads_may_only_be_deleted_by_those_who_have_permission()
    {

        $thread = create('App\Thread');

        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->json('DELETE', $thread->path());

    }

    private function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread',$overrides);

        return $this->post('/threads', $thread->toArray());
    }

}
