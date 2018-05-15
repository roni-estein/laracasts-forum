<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{

    protected $guarded = [];

    /**
     * Get the user that owns the ThreadSubscription.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function notify($reply)
    {
        $this->user->notify(new ThreadWasUpdated($this, $reply));
    }

}
