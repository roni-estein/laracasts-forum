<?php

namespace Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Tests\DBTestCase;

class NotificationsTest extends DBTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->signIn();


    }

    /** @test */
    public function a_user_is_notified_when_replies_are_added_to_subscribed_thread()
    {

        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(0,auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'A new reply',
        ]);

        $this->assertCount(0,auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Another new reply',
        ]);

        $this->assertCount(1,auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications_as_read()
    {
        create(DatabaseNotification::class);

        $this->assertCount(1, $this->getJson("/profiles/" .auth()->user()->name. "/notifications/")->json());
    }



    /** @test */
    public function a_user_can_mark_unread_notifications_as_read()
    {


        $user = auth()->user();
        create(DatabaseNotification::class);

        $this->assertCount(1,$user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete( "/profiles/{$user}/notifications/{$notificationId}");

        $this->assertCount(0,$user->fresh()->unreadNotifications);


    }


}