<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    use Favoritable, RecordsActivity;

    protected $guarded = [];

    protected $appends = ['favoritesCount','isFavorited', 'isBest'];

    protected $with = ['owner', 'favorites'];


    public static function boot()
    {
        parent::boot();

        static::deleted(function ($reply){
            $reply->thread->decrement('replies_count');
        });

        static::created(function ($reply){
            $reply->thread->increment('replies_count');
        });
    }

    /**
     * Get the owner record associated with the User.
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the thread that owns the Reply.
     */
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers(){
        preg_match_all('/@([\w\-]+)/', $this->body,$matches);
        return $matches[1];

    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/','<a href="/profiles/$1">$0</a>',$body);
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

}
