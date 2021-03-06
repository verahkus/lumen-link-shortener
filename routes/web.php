<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return config('app.version');
});

$router->post('short_link', [
    'as' => 'short-link', 'uses' => 'LinkController@shortLink'
]);
$router->post('get_link', [
    'as' => 'get-link', 'uses' => 'LinkController@getLink'
]);
