<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id','title', 'content'];
    public function tags(){
        return $this->morphToMany('App\Tag', 'taggable');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function photos(){
        return $this->morphMany('App\Photo', 'imageable');
    }

}
