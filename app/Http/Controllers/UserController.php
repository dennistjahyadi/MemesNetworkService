<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function storeForApi(Request $request){
        $user = new User();
        $user->email_verified_at = \Carbon::now();
        $user->email = $request->email;
        if($request->has("name")){
            $user->name = $request->name;
        }
        if($request->has("name")){
            $user->name = $request->name;
        }
        $user->save();
    }
}
