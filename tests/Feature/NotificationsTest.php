<?php

namespace Tests\Feature;

use Tests\DBTestCase;

class NotificationsTest extends DBTestCase
{

    /** @test */
    public function a_user_is_notified_when_replies_are_added_to_subscribed_thread()
    {
        $this->signIn();
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
        $this->signIn();

        $user = auth()->user();
        $thread = create('App\Thread')->subscribe();

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'A new reply',
        ]);

        $response = $this->getJson("/profiles/{$user}/notifications/")->json();

        $this->assertCount(1, $response);


    }



    /** @test */
    public function a_user_can_mark_unread_notifications_as_read()
    {
        $this->signIn();

        $user = auth()->user();
        $thread = create('App\Thread')->subscribe();

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'A new reply',
        ]);

        $this->assertCount(1,auth()->user()->unreadNotifications);

        $notificationId = auth()->user()->unreadNotifications->first()->id;

        $this->delete( "/profiles/{$user}/notifications/{$notificationId}");

        $this->assertCount(0,auth()->user()->fresh()->unreadNotifications);


    }


}