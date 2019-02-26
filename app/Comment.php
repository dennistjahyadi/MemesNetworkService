<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = "comments";

    protected $fillable = ['meme_id','user_id','messages','comment_id'];

}
