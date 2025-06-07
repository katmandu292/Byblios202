<?php

$router->get('/byblios/', 'controllers/home.php');
$router->get('/byblios/books', 'controllers/books/index.php');
$router->get('/byblios/publishers', 'controllers/publishers/index.php');
$router->get('/byblios/book', 'controllers/books/show.php');
$router->get('/byblios/books/create', 'controllers/books/create.php');
$router->get('/byblios/publishers/create', 'controllers/publishers/create.php');


