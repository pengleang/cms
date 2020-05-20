<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public function imageable(){ //main table
        return $this->morphTo();
    }
}
