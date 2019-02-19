<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ScrapController extends Controller
{
    //

    public function index(){


        return view("scrap");
    }

    public function scrap(){
        $client = new Client();
        $res = $client->get('https://9gag.com/v1/group-posts/group/default/type/hot');

        $resArray = json_decode($res->getBody(),true);
        $nextCursor = $resArray["data"]["nextCursor"];
        $posts = $resArray["data"]["posts"];
        foreach ($posts as $post) {
            $code = $post["id"];
            $title = $post["title"];
            $type = $post["type"];
            $tags = json_encode($post["tags"]);
            $postSection = $post["postSection"]["name"];
            $images = $post["images"];

           /* foreach ($images as $key => $value){
                $imageUrl = $images[$key]["url"];
                // downloading images
                $url = $imageUrl;
                $contents = file_get_contents($url);
                $name = substr($url, strrpos($url, '/') + 1);
                Storage::disk('public_sources')->put($name, $contents);
                // ------------
                $images[$key]["url"] = $name;
            }*/


            echo $code.'  ,   '.$title.'<br/>';

        }


    }

    public function downloadImage(){
        $url = "https://img-9gag-fun.9cache.com/photo/a9Kq8W0_460svvp9.webm";
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        Storage::disk('public_photos')->put($name, $contents);
        return "done ".$name;
    }
}
