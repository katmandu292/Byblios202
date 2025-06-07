<?php

$config = require basePath('config/_db.php');
$db = new Database($config);

$books = $db->query('select bk.VOLUME_ID, au.AUTH_NAME, bk.COLLECT_ID,
gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN, bk.VOL_TITLE, bk.VOL_INFO,
bk.LAUNCH_YEAR from tbl_books bk JOIN tbl_authors au ON (bk.AUTHOR_ID = au.PERS_ID)
JOIN tbl_genres gr oN (bk.GENRE_ID = gr.GENRE_ID) limit 6')->fetchAll();

loadView('partials/home',['books' => $books]);
?>

