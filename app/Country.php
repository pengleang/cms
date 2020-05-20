<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function posts(){
        return $this->hasManyThrough('App\Model\post', 'App\user');//default is similar to the below
        //return $this->hasManyThrough('App\Model\post', 'App\user', 'country_id' ,'user_id');
    }
}
