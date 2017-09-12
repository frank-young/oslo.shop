<?php
use EasyWeChat\Foundation\Application;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['web'],'namespace' => 'Wechat'], function() {
  Route::any('/notify', 'WechatController@notify');
  Route::get('/token', 'WechatController@token');
  Route::get('/code', 'WechatController@getCode');
  Route::get('/url', 'WechatController@getUrl');
  Route::get('/callback', 'WechatController@callback');
  
  Route::get('/info', 'WechatController@getInfo');
  Route::get('/wxauth', 'WechatController@wxauth');
  Route::any('/serve', 'WechatController@serve');
});

Route::get("/oauth/openwechat/getUrl", function(){

});
Route::get("/examples/oauth_callback", function(){

});
