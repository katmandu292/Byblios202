<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
  protected $db;

  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
  }

  /*
   * Show the latest books
   * 
   * @return void
   */
  public function index()
  {
    $books = $this->db->query('select bk.VOLUME_ID, au.AUTH_NAME, ed.EDITOR_NAME,
                          bk.COLLECT_ID, gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN,
                        cl.collection_name as COLLECT_NM, bk.VOL_TITLE, bk.VOL_INFO,
                                    bk.LAUNCH_YEAR from tbl_books bk
                         join tbl_authors au ON (bk.AUTHOR_ID = au.PERS_ID)
                         join tbl_collections cl on (bk.COLLECT_ID = cl.collection_id)
                         join tbl_editors ed on (bk.LAUNCHED_BY = ed.EDITOR_ID)
                         join tbl_genres gr oN (bk.GENRE_ID = gr.GENRE_ID) limit 6')->fetchAll();

    loadView('home', [
      'books' => $books
    ]);
  }
}

?>
