<?php

$config = require basePath('config/_db.php');
$db = new Database($config);

$bookId = $_GET['id'] ?? '';

$params = [ 'volumeId' => $bookId ];

$book = $db->query('SELECT * FROM tbl_books WHERE VOLUME_ID = :volumeId',$params)->fetch();

loadView('books/show', [ 'book'=> $book ]);

