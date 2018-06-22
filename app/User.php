<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function getRouteKeyName()
    {
        return 'name';  //eventually username
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email'
    ];

    protected $casts =[
      'confirmed' => 'boolean',
    ];

    public function isAdmin()
    {
        return in_array($this->email, ['john@doe.com']);
    }
    /**
     * Get the threads records associated with the User.
     */
    public function threads()
    {
        return $this->hasMany('App\Thread');
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($threadId)
    {
        return sprintf("users.%s.visits.%s", $this->id, $threadId);
    }

    public function read($thread)
    {
//        dd($thread);
        cache()->forever($this->visitedThreadCacheKey($thread->id), Carbon::now());
    }

    /**
     * Get the last reply record associated with the User.
     */
    public function lastReply()
    {
        return $this->hasOne('App\Reply')->latest();
    }

    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ?? '/images/avatars/generic-avatar.png');
    }

    public function confirm($token)
    {
        if ($token == $this->confirmation_token){
           $this->confirmed = true;
           $this->confirmation_token = null;
           $this->save();
        }

        return $this;
    }
}
