<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;

class LikeController extends Controller
{
    //
    public function insert(Request $request){
        
        $obj = Like::where("meme_id",$request->meme_id)
                   ->where("user_id",$request->user_id)->first();
        if(!$obj){
            $obj = new Like();
            $obj->user_id = $request->user_id;
            $obj->meme_id = $request->meme_id;
            $obj->like = $request->like;
            $obj->save();
        }else{
            $obj = Like::where("meme_id",$request->meme_id)
                         ->where("user_id",$request->user_id)
                         ->update(['like' => $request->like]);
        }
        
        $totalLike = Like::where("meme_id",$request->meme_id)->where("like",true)->count();
        $totalDislike = Like::where("meme_id",$request->meme_id)->where("like",false)->count();
        
        $data = ['result' => 1,
            'data' => $obj,
            'total_like' => $totalLike,
            'total_dislike' => $totalDislike
        ];
        return response($data,200);
    }
    
}
