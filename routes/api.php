<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login','ApiController@login');
Route::post('register','ApiController@register');

Route::group(['middleware' => 'auth.jwt'],function(){
    Route::get('logout', 'ApiController@logout');

    Route::get('user', 'ApiController@getAuthUser');
    Route::put('user', 'ApiController@updateUser');

    Route::get('mediasocial', 'MediaSocialController@index');
    Route::get('mediasocial/{id}', 'MediaSocialController@show');
    Route::post('mediasocial', 'MediaSocialController@store');
    Route::put('mediasocial/{id}', 'MediaSocialController@update');
    Route::delete('mediasocial/{id}', 'MediaSocialController@destroy');
});
