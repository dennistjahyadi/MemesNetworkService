<?php

namespace App\Http\Controllers;

use App\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function store(Request $request){

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

    public function index(Request $request){

        $limit = 10;
        $offset = 0;

        if($request->has("offset")){
            $offset = $request->offset;
        }

        $comments = Comment::select(['comments.*','users.username as created_by'])
                             ->join('users','comments.user_id','=','users.id')
                             ->orderBy("id","desc");
        $comments = $comments->limit($limit)->offset($offset);

        if($request->has("comment_id")){
            $comments = $comments->where("comment_id", $request->input("comment_id"));
            $comments = $comments->orderBy("id","desc");
        }
        if($request->has("meme_id")){
            $comments = $comments->where("meme_id", $request->input("meme_id"));
        }
        if($request->has("user_id")){
            $comments = $comments->where("user_id", $request->input("user_id"));
        }

        $comments = $comments->get();


        $data = ['result' => 1,
            'data' => $comments,
            'current_datetime' => Carbon::now()->toDateTimeString()
        ];
        return response()->json($data,200);
    }

    public function indexByUserId(Request $request){

        $limit = 10;
        $offset = 0;

        if($request->has("offset")){
            $offset = $request->offset;
        }

        $comments = Comment::select(['comments.*','users.username as created_by','memes.code','memes.title','memes.type','memes.images','memes.tags','memes.post_section'])
            ->join('users','comments.user_id','=','users.id')
            ->join('memes','comments.meme_id','=','memes.id')
            ->orderBy("id","desc");
        $comments = $comments->limit($limit)->offset($offset);

        if($request->has("user_id")){
            $comments = $comments->where("user_id", $request->input("user_id"));
        }

        $comments = $comments->get();

        $data = ['result' => 1,
            'data' => $comments,
            'current_datetime' => Carbon::now()->toDateTimeString()
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
