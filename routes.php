<?php

$router->get('/byblios/', 'HomeController@index');
$router->get('/byblios/book/{id}', 'BookController@show');
$router->get('/byblios/books', 'BookController@index');
$router->get('/byblios/books/create', 'BookController@create');
//outer->get('/byblios/publishers', 'controllers/publishers/index.php');
//outer->get('/byblios/publishers/create', 'controllers/publishers/create.php');
$router->post('/byblios/books', 'BookController@store');
?>
