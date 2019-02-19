<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meme extends Model
{
    //
    protected $table = "memes";

    protected $fillable = ['code','title','type','images','tags','post_section'];

}
