<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\DBTestCase;

class ProfilesTest extends DBTestCase
{
    protected $profileUser;

    public function setUp()
    {
        parent::setUp();
        $this->profileUser = create(User::class);

    }

    /** @test */
    public function a_user_has_a_profile()
    {

        $this->get("/profiles/{$this->profileUser->name}")
            ->assertSee($this->profileUser->name);
    }

    /** @test */
    public function profiles_display_all_threads_by_a_given_user()
    {

        $this->signIn();
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $this->get('/profiles/'. auth()->user()->name )
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

}