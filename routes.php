<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
## Route::get('/', function(){return View::make('hello');});
Route::get('/', array('as' => 'index', 'uses' => 'HomeController@index'));
Route::get('/share/{shareID}', array('as' => 'index', 'uses' => 'HomeController@index'));
Route::any('/build', array('as' => 'build', 'uses' => 'VideoStreamController@build')); ### all video action route
Route::any('/ldap', array('as' => 'ldap', 'uses' => 'HomeController@ldap')); ### ldap authentication route
Route::get('/exit', array('as' => 'unsetCookie', 'uses' => 'HomeController@unsetCookie')); ### ldap auth close route

Route::any('/personmessage', array('as' => 'personmessage', 'uses' => 'curlGroveTicketController@personmessage')); ### personal ticket list route
Route::any('/assigneeuser', array('as' => 'assigneeuser', 'uses' => 'curlGroveTicketController@assigneeuser')); ### ticket create or closed user data route
Route::any('/assigneemessage', array('as' => 'assigneemessage', 'uses' => 'curlGroveTicketController@assigneemessage')); ### personal ticket message details route
Route::post('/getticket', array('as' => 'getticket', 'uses' => 'curlGroveTicketController@getticket')); ###  save new ticket route

Route::post('/uploadattach', array('as' => 'uploadattach', 'uses' => 'curlGroveTicketController@uploadattach')); // upload attach
Route::any('/delattach', array('as' => 'delattach', 'uses' => 'curlGroveTicketController@delattach'));

Route::get('/updatedoc/{gelenId?}', array('as' => 'updatedoc', 'uses' => 'RecordController@updatedoc'));
Route::post('/updatesave', array('as' => 'updatesave', 'uses' => 'RecordController@updatesave'));
Route::post('/docsearch', array('as' => 'docsearch', 'uses' => 'RecordController@docsearch'));
/*
Route::get('/loginForm', array('as'=>'loginForm', 'uses'=>'HomeController@loginForm'));
Route::post('/login', array('as'=>'login', 'uses'=>'HomeController@login'));
Route::get('/serkan', array('as'=>'deneme', 'uses'=>'HomeController@deneme', 'before'=>'auth'));
Route::get('/cikis', array('as'=>'cikis', 'uses'=>'HomeController@cikis'));
*/