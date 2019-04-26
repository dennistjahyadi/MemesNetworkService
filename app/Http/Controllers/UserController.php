<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    //
    public function storeForApi(Request $request){
        $user = new User();
        $user->email_verified_at = Carbon::now();
        $user->email = $request->email;
        if($request->has("name")){
            $user->name = $request->name;
        }
        if($request->has("photo_url")){
            $user->photo_url = $request->photo_url;
        }

        $result = User::where('email',$request->email)->first();

        if($result==null){
            $user->save();
        }else{
            $user = $result;
        }

        $data = ['result' => 1,
            'data' => $user
        ];
        return response()->json($data,200);
    }

    public function insertUsername(Request $request){

        $user = User::where('username',$request->username)->first();

        if($user==null) {
            if(strlen($request->username) <= 4 ){

                return response()->json("Username at least 5 character long.",401);
            }
            if(!preg_match('/^\w{5,}$/', $request->username)) { // \w equals "[0-9A-Za-z_]"
                // valid username, alphanumeric & longer than or equals 5 chars
                return response()->json("Username cannot include special characters or space.",401);
            }
            $user = User::where('email', $request->email)->first();
            $user->username = $request->username;
            $user->save();
        }else {

            return response()->json("Username has been used.",401);
        }

        $data = ['result' => 1,
            'data' => $user
        ];
        return response()->json($data,200);
    }

    public function editProfile(Request $request){
        $user = User::where('username',$request->username)->whereNot('email',$request->email)->first();

        if($user==null) {
            if(strlen($request->username) <= 4 ){

                return response()->json("Username at least 5 character long.",401);
            }
            if(!preg_match('/^\w{5,}$/', $request->username)) { // \w equals "[0-9A-Za-z_]"
                // valid username, alphanumeric & longer than or equals 5 chars
                return response()->json("Username cannot include special characters or space.",401);
            }
            $user = User::where('email', $request->email)->first();
            $user->username = $request->username;
            $user->save();
        }else {

            return response()->json("Username has been used.",401);
        }

        $data = ['result' => 1,
            'data' => $user
        ];
        return response()->json($data,200);
    }
}
