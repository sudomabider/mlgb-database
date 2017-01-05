<?php

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

$router = app(\Illuminate\Routing\Router::class);

$router->get('all', 'ApiController@all');
//$router->get('players/first', 'ApiController@firstPlayer');

//$router->get('/', 'PlayerController@index');

$router->get('players', ['uses' => 'PlayerController@index']);
$router->get('players/{tag}', ['uses' => 'PlayerController@show']);

//$router->get('players/{tag}', function ($tag) {
//    $tag = urldecode($tag);
//    return app('clan')->getAllPlayers();
//});