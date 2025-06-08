<?php

$router->get('/byblios/', 'HomeController@index');
$router->get('/byblios/books', 'BookController@index');
$router->get('/byblios/books/create', 'BookController@create');
//outer->get('/byblios/publishers', 'controllers/publishers/index.php');
$router->get('/byblios/book', 'BookController@show');
//outer->get('/byblios/books/create', 'controllers/books/create.php');
//outer->get('/byblios/publishers/create', 'controllers/publishers/create.php');


