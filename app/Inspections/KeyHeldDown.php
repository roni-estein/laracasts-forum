<?php

namespace App\Inspections;

use Exception;
use Illuminate\Database\Eloquent\Model;

class KeyHeldDown extends Model
{
    
    public function detect($body)
    {
        if(preg_match('/(.)\\1{4,}/',$body)){
            throw new Exception('Your Reply Contains Spam.');
        };
    }
}
