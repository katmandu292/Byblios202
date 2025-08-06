<?php

$router->get('/byblios/', 'HomeController@index');
$router->get('/byblios/book', 'BookController@index');
$router->get('/byblios/editor', 'EditorController@index');
$router->get('/byblios/editor/show/{id}', 'EditorController@show');
$router->get('/byblios/book/show/{id}', 'BookController@show');
$router->get('/byblios/book/edit/{id}', 'BookController@edit',['auth']);
$router->get('/byblios/editor/edit/{id}', 'EditorController@edit',['auth']);
$router->get('/byblios/book/create', 'BookController@create',['auth']);
//outer->get('/byblios/publishers', 'controllers/publishers/index.php');
//outer->get('/byblios/publishers/create', 'controllers/publishers/create.php',['auth']);

$router->get('/byblios/auth/login','UserController@login',['guest']);
$router->get('/byblios/auth/register','UserController@create',['guest']);

$router->put('/byblios/book/update/{id}', 'BookController@update',['auth']);
$router->put('/byblios/editor/update/{id}', 'EditorController@update',['auth']);

$router->post('/byblios/books', 'BookController@store',['auth']);
$router->post('/byblios/auth/register','UserController@store',['auth']);
$router->post('/byblios/auth/login','UserController@authenticate',['guest']);
$router->post('/byblios/auth/logout','UserController@logout',['auth']);

$router->delete('/byblios/book/show/{id}', 'BookController@destroy',['auth']);

?>
