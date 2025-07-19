<?php

namespace App\controllers;

use Framework\Database;
use Framework\Validation;

class BookController
{
  protected $db;
  protected $authors;
  protected $genres;
  protected $bookCollections;
  protected $publishers;

  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
  }


  /**
   * collects all the necessary
   * data from the Database
   * @return  void
   */
  protected function getAttribs(){
    $this->authors = $this->db->query('select aut.PERS_ID, aut.AUTH_NAME from tbl_authors aut order by aut.PERS_ID')->fetchAll();
    $this->publishers = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME from tbl_editors ed')->fetchAll();
    $this->bookCollections = $this->db->query('select cl.collection_id, cl.collection_name from tbl_collections cl')->fetchAll();
    $this->genres = $this->db->query('select gr.GENRE_ID, gr.GENRE_LABEL from tbl_genres gr order by gr.GENRE_ID')->fetchAll();
  }

  /**
   * Show all books
   *
   * @return void
   */
  public function index()
  {
    $books = $this->db->query('select bk.VOLUME_ID, au.AUTH_NAME,
bk.COLLECT_ID, gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN, ed.EDITOR_NAME,
cl.collection_name as COLLECT_NM, bk.VOL_TITLE, bk.VOL_INFO,
bk.LAUNCH_YEAR from tbl_books bk join tbl_authors au ON (bk.AUTHOR_ID = au.PERS_ID)
join tbl_collections cl on (bk.COLLECT_ID = cl.collection_id)
join tbl_editors ed on (bk.LAUNCHED_BY = ed.EDITOR_ID)
join tbl_genres gr oN (bk.GENRE_ID = gr.GENRE_ID) order by bk.VOLUME_ID desc')->fetchAll();

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
    $this->getAttribs();

    loadView('books/create', [
      'authors' => $this->authors,
      'editors' => $this->publishers,
      'collections' => $this->bookCollections,
      'genres' => $this->genres
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
ed.EDITOR_NAME,
bk.LAUNCH_YEAR from tbl_books bk join tbl_authors au on (bk.AUTHOR_ID = au.PERS_ID)
join tbl_genres gr on (bk.GENRE_ID = gr.GENRE_ID)
join tbl_editors ed on (bk.LAUNCHED_BY = ed.EDITOR_ID)
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
      $this->getAttribs();

      $allowedFields = ['VOL_TITLE', 'VOL_INFO', 'LAUNCH_YEAR', 'ISBN', 'GENRE_ID', 'LAUNCHED_BY', 'AUTHOR_ID', 'COLLECT_ID'];

      $newBookData = array_intersect_key($_POST, array_flip($allowedFields));

      $newBookData = array_map('sanitize',$newBookData);

//    $newBookData['user_id'] = 1;

      $requiredFields = ['VOL_TITLE', 'VOL_INFO'];

      $errors = [];

      foreach ($requiredFields as $field) {
         if (empty($newBookData[$field]) || !Validation::string($newBookData[$field])) {
           $errors[$field] = ucfirst($field) . ' is required';
         }
      }

      if (!empty($errors)) {
// Reload view with errors
          loadView('books/create', [
            'authors' => $this->authors,
            'editors' => $this->publishers,
            'collections' => $this->bookCollections,
            'genres' => $this->genres,
            'errors' => $errors,
            'bookData' => $newBookData
          ]);
      } else {
//       inspectAndDie($newBookData);
          $fields = [];
          $values = [];

          foreach ($newBookData as $field => $value) {
              $fields[] = $field;
          }

          $fields = implode(', ', $fields);

          foreach ($newBookData as $field => $value) {
             if ($value === '') {
                 $newBookData[$field] = null;
             }
             $values[] = ":" . $field;
          }

          $values = implode(', ', $values);
          $insert = "insert into `tbl_books` ({$fields}) values ({$values})";

          $this->db->query($insert, $newBookData);

          $_SESSION['flash'] = 'Book was successfully added !';

          redirect('/byblios');

          exit;

      }

  }

  /**
   * Delete a book
   *
   * @param array $params
   * @return void
   */
  public function destroy($params) {

    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $book = $this->db->query('select bk.VOLUME_ID, bk.VOL_TITLE,
bk.VOL_INFO from tbl_books bk where bk.VOLUME_ID = :id',$params)->fetch(); 

    // Check if book exists
    if (!$book) {
      ErrorController::notFound('Book not found');
      return;
    }

    $this->db->query('delete from tbl_books where VOLUME_ID = :id',$params);

    $_SESSION['success_message'] = 'Successfully deleted the Book';

    redirect('/byblios');
  }
}

?>
