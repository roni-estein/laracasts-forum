<?php

namespace App;

trait Favoritable
{

    public static function bootFavoritable()
    {
        if(auth()->guest()) return;

        static::deleting(function($model){
            $model->favorites->each->delete();
        });
    }
    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {

        $attributes = ['user_id' => auth()->id()];
//        var_dump($this->favorites()->where($attributes)->first());
//        exit();

//        dd($attributes);
        $this->favorites()->where($attributes)->get()->each->delete();
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }
}