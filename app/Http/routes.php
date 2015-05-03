<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* Main view to show the results */
$app->get('/', function() use ($app) {
    return "No results yet";
});
$app->get('/t/{token}', 'App\Http\Controllers\PagesController@confirm');

/* Simple API routes*/
// $app->post('/api/v1/votes', ['middleware' => 'cors', 'uses' =>  'App\Http\Controllers\VotesAPIController@store']);
$app->post('/api/v1/votes', 'App\Http\Controllers\VotesAPIController@store');
//$app->put('/api/v1/votes/{id}', 'App\Http\Controllers\VotesAPIController@update');
