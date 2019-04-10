<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    protected $table = "likes";

    protected $fillable = ['meme_id','user_id','like'];
}
