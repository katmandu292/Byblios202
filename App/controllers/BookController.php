<?php

namespace App\Controllers;

use Framework\Database;

class BookController
{
  protected $db;

  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
  }

  /**
   * Show all books
   * 
   * @return void
   */
  public function index()
  {
    $books = $this->db->query('select bk.VOLUME_ID, au.AUTH_NAME,
bk.COLLECT_ID, gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN,
bk.VOL_TITLE, bk.VOL_INFO, bk.LAUNCH_YEAR from tbl_books bk
join tbl_authors au ON (bk.AUTHOR_ID = au.PERS_ID)
join tbl_genres gr oN (bk.GENRE_ID = gr.GENRE_ID)')->fetchAll();

    loadView('books/index', [
      'books' => $books
    ]);
  }

  /**
   * Show the create book form
   * 
   * @return void
   */
  public function create()
  {
    loadView('books/create');
  }

  /**
   * Show a single book
   * 
   * @param array $params
   * @return void
   */
  public function show($params)
  {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $book = $this->db->query('select * from tbl_books where id = :id', $params)->fetch();

    // Check if book exists
    if (!$book) {
      ErrorController::notFound('Book not found');
      return;
    }

    loadView('books/show', [
      'listing' => $listing
    ]);
  }

}

