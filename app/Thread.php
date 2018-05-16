<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];
    public static function boot()
    {
        parent::boot();

        //thread always deletes its replies when being deleted.
        //use the each higher order function because each reply must be triggered on the model
        //to cascade to replies. Better use case would be DB cascade deleting.
        static::deleting(function ($thread){
            $thread->replies->each->delete();
        });

//        static::addGlobalScope('replyCount',function ($builder){
//            $builder->withCount('replies');
//        });





//        static::addGlobalScope('creator',function ($builder){
//            $builder->with('creator');
//        });
    }


    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * Get the replies record associated with the Thread.
     */
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    /**
     * Get the channel that owns the Thread.
     */
    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    /**
     * Get the creator that owns the Thread.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function addReply($reply)
    {
        $reply =  $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->delete ([
            'user_id' => $userId ?: auth()->id(),
        ]);
    }


    /**
     * Get the subscriptions records associated with the Thread.
     */
    public function subscriptions()
    {
        return $this->hasMany('App\ThreadSubscription');
    }
    
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where(['user_id'=>auth()->id()])->exists();
    }

    /**
     * @param $reply
     */
    protected function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);

    }
}
