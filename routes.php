<?php

$router->get('/byblios/', 'HomeController@index');
$router->get('/byblios/book/show/{id}', 'BookController@show');
$router->get('/byblios/book/edit/{id}', 'BookController@edit');
$router->get('/byblios/books', 'BookController@index');
$router->get('/byblios/books/create', 'BookController@create');
//outer->get('/byblios/publishers', 'controllers/publishers/index.php');
//outer->get('/byblios/publishers/create', 'controllers/publishers/create.php');
$router->put('/byblios/book/update/{id}', 'BookController@update');
$router->post('/byblios/books', 'BookController@store');
$router->delete('/byblios/book/{id}', 'BookController@destroy');
?>
