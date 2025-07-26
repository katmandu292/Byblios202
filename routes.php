<?php

$router->get('/byblios/', 'HomeController@index');
$router->get('/byblios/book/show/{id}', 'BookController@show');
$router->get('/byblios/book/edit/{id}', 'BookController@edit');
$router->get('/byblios/book', 'BookController@index');
$router->get('/byblios/book/create', 'BookController@create');
//outer->get('/byblios/publishers', 'controllers/publishers/index.php');
//outer->get('/byblios/publishers/create', 'controllers/publishers/create.php');
$router->get('/byblios/auth/login','UserController@login');
$router->get('/byblios/auth/register','UserController@create');

$router->put('/byblios/book/update/{id}', 'BookController@update');

$router->post('/byblios/books', 'BookController@store');
$router->post('/byblios/auth/register','UserController@store');
$router->post('/byblios/auth/logout','UserController@logout');

$router->delete('/byblios/book/{id}', 'BookController@destroy');

?>
