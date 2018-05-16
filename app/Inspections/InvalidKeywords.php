<?php

namespace App\Inspections;

use Exception;
use Illuminate\Database\Eloquent\Model;

class InvalidKeywords extends Model
{
    protected $keywords = [
        'yahoo customer support',
    ];

    public function detect($body)
    {
        foreach ($this->keywords as $keyword){
            if(stripos($body, $keyword) !== false){
                throw new Exception('Your Reply Contains Spam.');
            }
        }

    }
}
