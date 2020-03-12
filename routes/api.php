<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('test', function(){
	return 'test success';
});

Route::get('index','MemeController@index');
Route::get('indexliked','MemeController@indexLikedByUser');
Route::get('indexdisliked','MemeController@indexDislikedByUser');
Route::get('find','MemeController@find');
Route::post('sendcomment','CommentController@store');
Route::get('comments','CommentController@index');
Route::get('commentsuserhistory','CommentController@indexByUserId');
Route::post('syncusers','UserController@storeForApi');
Route::post('insertusername','UserController@insertUsername');
Route::post('editprofile','UserController@editProfile');
Route::get('sections','SectionController@index');
Route::post('insertlike','LikeController@insert');


