<?php

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

Route::get('/index','FaceController@index');//将初始请求接入此处
Route::get('/firstuse','FaceController@register');
Route::post('/firstregis','FaceController@register_post');
Route::group(['prefix'=>'panel'],function() {
    Route::get('/index','PanelController@index');
    Route::post('/trybind','PanelController@try_bind');
});
Route::group(['prefix'=>'admin'],function(){
    Route::get('/index','PanelController@admin_index');
    Route::get('/servers','PanelController@admin_servers');
});
Route::group(['prefix'=>'auth'],function() {
    Route::match(['post','get'],'/login','PanelAuthController@login_face');
    Route::get('/login_time','PanelAuthController@login_time');
    Route::post('/register','PanelAuthController@register');
    Route::get('/logout','PanelAuthController@logout');
});
Route::group(['prefix'=>'test'],function(){
    Route::get('/insert','TestController@insert');
});
Route::group(['prefix'=>'api'],function(){
    Route::post('/daemon_test','ApiController@daemon_test');
});

?>
