<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function insert(Request $request){

        $comment = new Comment();
        $comment->meme_id = $request->input("meme_id");
        $comment->user_id = $request->input("user_id");
        $comment->messages = $request->input("messages");

        if($request->has("comment_id")){
            $comment->comment_id = $request->input("comment_id");
        }

        if($comment->save()){
            $data = ['result' => 1,
                'data' => $comment
            ];
            return response()->json($data,200);
        }
        $data = ['result' => 0,
            'data' => null
        ];
        return response()->json($data,401);
    }

    public function fetch(Request $request){

        $comments = Comment::orderBy("id","desc");
        if($request->has("comment_id")){
            $comments = $comments->where("comment_id", $request->input("comment_id"));
            $comments = $comments->orderBy("id","asc");
        }
        if($request->has("meme_id")){
            $comments = $comments->where("meme_id", $request->input("meme_id"));
        }
        $comments = $comments->get();

        $data = ['result' => 1,
            'data' => $comments
        ];
        return response()->json($data,200);
    }

    public function find(Request $request){
        $comment = Comment::find($request->input("id"));

        $data = ['result' => 1,
            'data' => $comment
        ];
        return response()->json($data,200);
    }

}
