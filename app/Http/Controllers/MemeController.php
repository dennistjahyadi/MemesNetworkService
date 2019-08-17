<?php

	namespace App\Http\Controllers;

	use App\Meme;
	use App\Section;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\DB;
	use Carbon\Carbon;

	class MemeController extends Controller
	{
		//
		public function insert(Request $request){
			$post = json_decode($request->input("post"),true);
			$code = $post["id"];
			$title = $post["title"];
			$type = $post["type"];
			$tags = json_encode($post["tags"]);
			$postSection = $post["postSection"]["name"];
			$images = $post["images"];

			$memes = Meme::where("code",$code)->get();
			if(count($memes)>0){
				return 0;
			}
			//        foreach ($images as $key => $value){
			//            $imageUrl = $images[$key]["url"];
			//            // downloading images
			//            $url = $imageUrl;
			//            $contents = file_get_contents($url);
			//            $name = substr($url, strrpos($url, '/') + 1);
			//            Storage::disk('public_sources')->put($name, $contents);
			//            // ------------
			//            $images[$key]["url"] = $name;
			//        }

			$meme = new Meme();
			$meme->code = $code;
			$meme->title = $title;
			$meme->type = $type;
			$meme->tags = $tags;
			$meme->post_section = $postSection;
			$meme->images = json_encode($images);
			$meme->save();

			$sections = Section::where("name",$postSection)->get();
			if(count($sections)==0){
				$section = new Section();
				$section->name = $postSection;
				$section->save();
			}

			return 'success '.$code.'  ,   '.$title.'<br/>';
		}

		public function insert2($param){
			$post = $param;
			$code = $post["id"];
			$title = $post["title"];
			$type = $post["type"];
			$tags = json_encode($post["tags"]);
			$postSection = $post["postSection"]["name"];
			$images = $post["images"];

			$memes = Meme::where("code",$code)->get();
			if(count($memes)>0){
				return 0;
			}
			//        foreach ($images as $key => $value){
			//            $imageUrl = $images[$key]["url"];
			//            // downloading images
			//            $url = $imageUrl;
			//            $contents = file_get_contents($url);
			//            $name = substr($url, strrpos($url, '/') + 1);
			//            Storage::disk('public_sources')->put($name, $contents);
			//            // ------------
			//            $images[$key]["url"] = $name;
			//        }

			$meme = new Meme();
			$meme->code = $code;
			$meme->title = $title;
			$meme->type = $type;
			$meme->tags = $tags;
			$meme->post_section = $postSection;
			$meme->images = json_encode($images);
			$meme->save();

			$sections = Section::where("name",$postSection)->get();
			if(count($sections)==0){
				$section = new Section();
				$section->name = $postSection;
				$section->save();
			}

		}

		function fetch()
		{
			$url = "https://9gag.com/v1/group-posts/group/default/type/hot";
			$nextCursor = "";
			$totalWanted = 10;
			$totalScrapped = 0;

			$theUrl = $url;


			for($a=0;$a<=10;$a++){
				if($nextCursor!=""){
					$theUrl = $url."?".$nextCursor;
				}
				$client = new \GuzzleHttp\Client();
				$response = $client->request('GET', $theUrl);
				$result = json_decode($response->getBody()); // convert string to json object
				$posts = json_encode($result->data->posts);
				$posts = json_decode($posts,true);
				$nextCursor = $result->data->nextCursor;

				if($a==4){
					for($i=0;$i<count($posts);$i++){
						$post = $posts[$i];
						$this->insert2($post);
					}
				}

			}



		}

		public function index(Request $request){
			$limit = 20;
			$offset = 0;
			$userId = 0;

			if($request->has("offset")){
				$offset = $request->offset;
			}
			if($request->has("user_id")){
				$userId = $request->user_id;
			}

			if($offset==0){
				$theMeme = Meme::limit(1)->orderBy('memes.id','desc')->get()[0];
				$createdAt = Carbon::parse($theMeme->created_at);
				$now =  Carbon::now();

				$totalDuration = $now->diffInMinutes($createdAt);
				if($totalDuration>10){
					$this->fetch();
				}
				else{
				}
			}



			$memes = Meme::select("memes.*",
            DB::raw("(SELECT count(likes.meme_id) FROM likes
			WHERE likes.meme_id = memes.id and likes.like = 1) as total_like"),
            DB::raw("(SELECT count(likes.meme_id) FROM likes
			WHERE likes.meme_id = memes.id and likes.like = 0) as total_dislike"),
            DB::raw("(SELECT count(comments.meme_id) FROM comments
			WHERE comments.meme_id = memes.id) as total_comment"),
            DB::raw("(select likes.like from likes 
			WHERE likes.meme_id = memes.id and likes.user_id = ".$userId.") as is_liked"));

			$memes = $memes->limit($limit)->offset($offset);

			if($request->has("type")){
				$memes = $memes->where("memes.type",$request->input("type"));
			}
			if($request->has("post_section")){
				$memes = $memes->where("memes.post_section",$request->input("post_section"));
			}

			$memes = $memes->orderBy("memes.id","desc");

			$memes = $memes->get();

			$shuffled = $memes->shuffle();

			$shuffled->all();


			return response($shuffled->all(),200);
		}

		public function indexLikedByUser(Request $request){
			$limit = 20;
			$offset = 0;

			if($request->has("offset")){
				$offset = $request->offset;
			}

			$memes = Meme::select("memes.*",
            DB::raw("(SELECT count(likes.meme_id) FROM likes
			WHERE likes.meme_id = memes.id and likes.like = 1) as total_like"),
            DB::raw("(SELECT count(likes.meme_id) FROM likes
			WHERE likes.meme_id = memes.id and likes.like = 0) as total_dislike"),
            DB::raw("(SELECT count(comments.meme_id) FROM comments
			WHERE comments.meme_id = memes.id) as total_comment"),
            DB::raw("(select likes.like from likes 
			WHERE likes.meme_id = memes.id and likes.user_id = ".$request->user_id.") as is_liked"),
			DB::raw("(select likes.created_at from likes 
			WHERE likes.meme_id = memes.id and likes.user_id = ".$request->user_id.") as likes_at")
			);

			$memes = $memes->limit($limit)->offset($offset);

			if($request->has("type")){
				$memes = $memes->where("memes.type",$request->input("type"));
			}
			if($request->has("post_section")){
				$memes = $memes->where("memes.post_section",$request->input("post_section"));
			}
			$memes = $memes->join('likes','memes.id','=','likes.meme_id')
			->where('likes.user_id',$request->user_id)
			->where('likes.like',1);
			
			$memes = $memes->orderBy("likes_at","desc");


			$memes = $memes->get();

			return response($memes,200);
		}

		public function indexDislikedByUser(Request $request){
			$limit = 20;
			$offset = 0;

			if($request->has("offset")){
				$offset = $request->offset;
			}

			$memes = Meme::select("memes.*",
            DB::raw("(SELECT count(likes.meme_id) FROM likes
			WHERE likes.meme_id = memes.id and likes.like = 1) as total_like"),
            DB::raw("(SELECT count(likes.meme_id) FROM likes
			WHERE likes.meme_id = memes.id and likes.like = 0) as total_dislike"),
            DB::raw("(SELECT count(comments.meme_id) FROM comments
			WHERE comments.meme_id = memes.id) as total_comment"),
            DB::raw("(select likes.like from likes 
			WHERE likes.meme_id = memes.id and likes.user_id = ".$request->user_id.") as is_liked"),
			DB::raw("(select likes.created_at from likes 
			WHERE likes.meme_id = memes.id and likes.user_id = ".$request->user_id.") as likes_at")
			);

			$memes = $memes->limit($limit)->offset($offset);

			if($request->has("type")){
				$memes = $memes->where("memes.type",$request->input("type"));
			}
			if($request->has("post_section")){
				$memes = $memes->where("memes.post_section",$request->input("post_section"));
			}
			$memes = $memes->join('likes','memes.id','=','likes.meme_id')
            ->where('likes.user_id',$request->user_id)
            ->where('likes.like',0);
            
			$memes = $memes->orderBy("likes_at","desc");


			$memes = $memes->get();

			return response($memes,200);
		}
	}
