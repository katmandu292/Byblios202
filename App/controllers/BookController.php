<?php

namespace App\controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class BookController
{
  protected $db;
  protected $authors;
  protected $genres;
  protected $bookCollections;
  protected $publishers;
  protected $allowedFields;
  protected $requiredFields;


  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
    $this->allowedFields = ['VOL_TITLE', 'VOL_INFO', 'LAUNCH_YEAR', 'ISBN', 'GENRE_ID', 'LAUNCHED_BY', 'AUTHOR_ID', 'COLLECT_ID', 'OWNER_ID', 'UPDATED_AT'];
    $this->requiredFields = ['VOL_TITLE', 'VOL_INFO'];
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

    $params = [ 'id' => $id ];

    $book = $this->db->query('select bk.VOLUME_ID, bk.AUTHOR_ID, au.AUTH_NAME, bk.GENRE_ID,
bk.COLLECT_ID, gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN, bk.VOL_TITLE, bk.VOL_INFO,
ed.EDITOR_NAME, bk.LAUNCH_YEAR, bk.OWNER_ID
from tbl_books bk join tbl_authors au on (bk.AUTHOR_ID = au.PERS_ID)
join tbl_genres gr on (bk.GENRE_ID = gr.GENRE_ID)
join tbl_editors ed on (bk.LAUNCHED_BY = ed.EDITOR_ID)
where bk.VOLUME_ID = :id', $params)->fetch();

    // Check if book exists
    if (!$book) {
      ErrorController::notFound('Book not found');
      return;
    } else {
      $convertedOwnerID = (int) $book->OWNER_ID;
      $book->OWNER_ID = $convertedOwnerID;
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

      $newBookData = array_intersect_key($_POST, array_flip($this->allowedFields));

      $newBookData = array_map('sanitize',$newBookData);

      $newBookData['OWNER_ID'] = Session::get('user')['id'];

      $errors = [];

      foreach ($this->requiredFields as $field) {
         if (empty($newBookData[$field]) || !Validation::string($newBookData[$field])) {
           $errors[$field] = ucfirst($field) . ' is required';
         }
      }

      if (!empty($errors)) {
// Reload view with errors
          loadView('books/create', [
            'user_id' => Session::get('user')['id'],
            'authors' => $this->authors,
            'editors' => $this->publishers,
            'collections' => $this->bookCollections,
            'genres' => $this->genres,
            'errors' => $errors,
            'bookData' => $newBookData
          ]);
      } else {

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

          $insertSql = "insert into `tbl_books` ({$fields}) values ({$values})";

          $this->db->query($insertSql, $newBookData);

          Session::setFlashMessage('success_message','Successfully saved the book !');

          redirect('/byblios/book');

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

    $params = [ 'id' => $id ];

    $book = $this->db->query('select bk.VOLUME_ID, bk.VOL_TITLE, bk.OWNER_ID,
bk.VOL_INFO from tbl_books bk where bk.VOLUME_ID = :id',$params)->fetch();
    $ownerId = (int) $book->OWNER_ID;
//     Check if book exists
    if (!$book) {
      ErrorController::notFound('Book not found');
      return;
    }

//     Authorization
    if (!Authorization::isOwner($ownerId)) {
      Session::setFlashMessage('error_message','You are not authorized for this operation');
      return redirect('/byblios/book/show/' . $book->VOLUME_ID);
    }

    $this->db->query('delete from tbl_books where VOLUME_ID = :id',$params);

    Session::setFlashMessage('success_message', 'Successfully deleted the Book');

    redirect('/byblios');
  }

  /**
   * Render a book edit form
   *
   * @param array $params
   * @return void
   */
  public function edit($params)
  {
    $this->getAttribs();

    $id = $params['id'] ?? '';

    $params = [ 'id' => $id ];

    $book = $this->db->query('select bk.VOLUME_ID, bk.AUTHOR_ID, au.AUTH_NAME, bk.GENRE_ID,
bk.COLLECT_ID, gr.GENRE_LABEL, bk.LAUNCHED_BY, bk.ISBN, bk.VOL_TITLE, bk.VOL_INFO,
ed.EDITOR_NAME, bk.OWNER_ID,
bk.LAUNCH_YEAR from tbl_books bk join tbl_authors au on (bk.AUTHOR_ID = au.PERS_ID)
join tbl_genres gr on (bk.GENRE_ID = gr.GENRE_ID)
join tbl_editors ed on (bk.LAUNCHED_BY = ed.EDITOR_ID)
where bk.VOLUME_ID = :id', $params)->fetch();

    // Check if book exists
    if (!$book) {
      ErrorController::notFound('Book not found');
      return;
    }

    loadView('books/edit', [
            'authors' => $this->authors,
            'editors' => $this->publishers,
            'collections' => $this->bookCollections,
            'genres' => $this->genres,
            'bookData' => $book
    ]);
  }

  /**
   * Update a listing
   *
   * @param array $params
   * @return void
   */
  public function update($params) {

      $id = $params['id'] ?? '';

      $params = [ 'id' => $id ];

      $bookCheck = $this->db->query('select bk.VOLUME_ID, bk.OWNER_ID from tbl_books bk where bk.VOLUME_ID = :id', $params)->fetch();

      if (!$bookCheck) {
         ErrorController::notFound('Book not found');
         return;
      }

      $this->getAttribs();

      $updatedBookData = array_intersect_key($_POST, array_flip($this->allowedFields));

      $updatedBookData = array_map('sanitize',$updatedBookData);

      $userId = (int) $bookCheck->OWNER_ID;

      if (!Authorization::isOwner($userId)) {
         Session::setFlashMessage('error_message', 'You are not authorized to update this listing');
         return redirect('/byblios/book/show/' . $id);
      }

      $errors = [];

      foreach ($this->requiredFields as $field) {
         if (empty($updatedBookData[$field]) || !Validation::string($updatedBookData[$field])) {
           $errors[$field] = ucfirst($field) . ' is required';
         }
      }

      if (empty($errors)) {
          $updateFields = [];

          foreach (array_keys($updatedBookData) as $field) {
             $updateFields[] = "{$field} = :{$field}";
          }

          $updateFields = implode(', ', $updateFields);
 
          $updatedBookData['id'] = $id;

          $updateQuery = "update tbl_books set {$updateFields} where VOLUME_ID = :id";

          $this->db->query($updateQuery,$updatedBookData);

          Session::setFlashMessage('success_message','The book was successfully updated !');

          redirect('/byblios/book/show/' . $id);

          exit;
      } else {
// Reload view with errors
          loadView('books/edit', [
            'authors' => $this->authors,
            'editors' => $this->publishers,
            'collections' => $this->bookCollections,
            'genres' => $this->genres,
            'errors' => $errors,
            'bookData' => $updatedBookData
          ]);
            exit;

      }


  }

}

?>
