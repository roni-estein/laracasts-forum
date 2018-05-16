<?php

namespace App\Inspections;

use Illuminate\Database\Eloquent\Model;

class Spam extends Model
{
    protected $inspections =[
        InvalidKeywords::class,
        KeyHeldDown::class,
    ];


    public function detect($body)
    {
        foreach($this->inspections as $inspection){
            app($inspection)->detect($body);
        }

        return false;
    }
}
