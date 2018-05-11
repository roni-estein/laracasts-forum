<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the threads records associated with the Channel.
     */
    public function threads()
    {
        return $this->hasMany('App\Thread');
    }
}
