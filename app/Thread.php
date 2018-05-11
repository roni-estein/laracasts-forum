<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    public static function boot()
    {
        parent::boot();

        //thread always deletes its replies when being deleted.
        //use the each higher order function because each reply must be triggered on the model
        //to cascade to replies. Better use case would be DB cascade deleting.
        static::deleting(function ($thread){
            $thread->replies->each->delete();
        });

        static::addGlobalScope('replyCount',function ($builder){
            $builder->withCount('replies');
        });





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
        $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
