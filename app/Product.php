<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function card()
    {
        return $this->hasMany('App\Card');
    }
}
