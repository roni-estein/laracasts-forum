<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tests\Unit\USerTest;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {


        collect($event->reply->mentionedUsers())
            ->map(function ($name){
                return User::whereName($name)->first();
            })
            ->filter()
            ->each(function ($user) use ($event){
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
