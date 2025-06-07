<?php

$config = require basePath('config/_db.php');
$db = new Database($config);

$books = $db->query('select bk.VOLUME_ID, au.AUTH_NAME, bk.COLLECT_ID,
gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN, bk.VOL_TITLE, bk.VOL_INFO,
bk.LAUNCH_YEAR from tbl_books bk join tbl_authors au on (bk.AUTHOR_ID = au.PERS_ID)
join tbl_genres gr on (bk.GENRE_ID = gr.GENRE_ID)')->fetchAll();

loadView('books/index',['books' => $books]);

?>

