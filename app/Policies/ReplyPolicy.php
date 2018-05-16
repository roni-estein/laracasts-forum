<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    public function create(User $user)
    {
//        $lastReply = $user->fresh()->lastReply;
//        if(! $lastReply) return true;
//        return ! $lastReply->wasJustPublished();
        return   (! optional($user->fresh()->lastReply)->wasJustPublished()) ?? true;
    }
}
