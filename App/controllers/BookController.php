<?php

namespace App\controllers;

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
    $authors = $this->db->query('select aut.PERS_ID, aut.AUTH_NAME from tbl_authors aut')->fetchAll();

    $publishers = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME from tbl_editors ed')->fetchAll();

    $bookCollections = $this->db->query('select cl.collection_id, cl.collection_name from tbl_collections cl')->fetchAll();

    $genres = $this->db->query('select gr.GENRE_ID, gr.GENRE_LABEL from tbl_genres gr')->fetchAll();

    loadView('books/create', [
      'authors' => $authors,
      'editors' => $publishers,
      'collections' => $bookCollections,
      'genres' => $genres
    ]);
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

    $book = $this->db->query('select bk.VOLUME_ID, bk.AUTHOR_ID, au.AUTH_NAME, bk.GENRE_ID,
bk.COLLECT_ID, gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN, bk.VOL_TITLE, bk.VOL_INFO,
bk.LAUNCH_YEAR from tbl_books bk join tbl_authors au on (bk.AUTHOR_ID = au.PERS_ID)
join tbl_genres gr on (bk.GENRE_ID = gr.GENRE_ID)
where bk.VOLUME_ID = :id', $params)->fetch();

    // Check if book exists
    if (!$book) {
      ErrorController::notFound('Book not found');
      return;
    }

    loadView('books/show', [
      'book' => $book
    ]);
  }

  /**
   * Store data in database
   * 
   * @return void
   */
  public function store()
  {
//    inspectAndDie($_POST);
      inspectAndDie($authors);
  }
}

