<?php

namespace Tests\Feature;
use Tests\DBTestCase;

class MentionUsersTest extends DBTestCase
{
    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {

        //GIVEN WE HAVE A USER JOHN WHO IS SIGNED IN
        $john = create('App\User', ['name' => 'John']);
        $this->signIn($john);
        //AND ANOTHER USER JANE
        $jane = create('App\User', ['name' => 'Jane']);
        //IF WE HAVE A THREAD
        $thread = create('App\Thread');

        $this->assertCount(0, $jane->notifications);
        //AND JOHN DOE REPLIES TO THAT THREAD AND MENTIONS JANE
        $reply = raw('App\Reply', [
            'body' => '@Jane is mentioned'
        ]);

        $this->post($thread->path() . '/replies', $reply);
        //THEN JANE SHALL BE NOTIFIED
        $this->assertCount(1, $jane->fresh()->notifications);
    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        $john = create('App\User', ['name' => 'John']);
        $jane = create('App\User', ['name' => 'Jane']);
        $rick = create('App\User', ['name' => 'Rick']);

        $this->assertCount(1, $this->json('get','/api/users', ['name'=>'r'])->json());
        $this->assertCount(2, $this->json('get','/api/users', ['name'=>'j'])->json());
        $this->assertCount(1, $this->json('get','/api/users', ['name'=>'jo'])->json());
        $this->assertCount(0, $this->json('get','/api/users', ['name'=>'jor'])->json());

    }

}