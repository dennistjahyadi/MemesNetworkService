<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    //
    public function insert(Request $request){
        
        $userId = $request->user_id;
        $memeId = $request->meme_id;
        $like = $request->like;
        
        
    }
    
}
