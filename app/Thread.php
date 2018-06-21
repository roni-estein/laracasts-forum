<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        //thread always deletes its replies when being deleted.
        //use the each higher order function because each reply must be triggered on the model
        //to cascade to replies. Better use case would be DB cascade deleting.
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
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
        return "/threads/{$this->channel->slug}/{$this->slug}";
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
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

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
        $this->subscriptions()->delete([
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
        return $this->subscriptions()->where(['user_id' => auth()->id()])->exists();
    }

    public function hasUpdatesFor($user = null)
    {

        $user = $user ?: auth()->user();
        //Look in cache for proper key
        $key = $user->visitedThreadCacheKey($this->id);

        //Compare that carbon instance with the last updated timestamp for the thread
        return $this->updated_at > cache($key);

    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($slug)
    {

        $slug = str_slug($slug);

        if (static::whereSlug($slug)->exists()) {
            $slug = $slug . '-' . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply($reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
        return $this;
    }

    public function lock()
    {
        $this->update(['locked' => true]);
        return $this;
    }
}
