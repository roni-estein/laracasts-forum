<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = [];



    use RecordsActivity;

    public function favorited()
    {
        return $this->morphTo();
    }

}
