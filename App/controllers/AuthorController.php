<?php

namespace App\controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class AuthorController
{
  protected $db;
  protected $allowedFields;
  protected $requiredFields;

  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
    $this->allowedFields = [ 'PERS_ID', 'OWNER_ID', 'BIRTH_YEAR', 'AUTH_NAME', 'AUTH_BIO', 'UPDATED_AT' ];
    $this->requiredFields = [ 'AUTH_NAME', '' ];
  }


  /**
   * Show all authors
   *
   * @return void
   */
  public function index()
  {
    $authorsList = $this->db->query('select aut.PERS_ID, aut.OWNER_ID,
 aut.BIRTH_YEAR, aut.AUTH_NAME, substr(aut.AUTH_BIO,1,128) as AUTH_BIO,
 usr.USER_FULLNAME
from tbl_authors aut join tbl_users usr on (aut.OWNER_ID = usr.USER_ID)
order by aut.PERS_ID')->fetchAll();

    loadView('authors/index', [
      'authors' => $authorsList
    ]);
  }





  /**
   * Show an Author
   *
   * @param array $params
   * @return void
   */
  public function show($params)
  {
    $id = $params['id'] ?? '';

    $params = [ 'id' => $id ];

    $author = $this->db->query('select aut.PERS_ID, aut.OWNER_ID,
aut.BIRTH_YEAR, aut.AUTH_NAME,
aut.AUTH_BIO, usr.USER_FULLNAME, usr.USER_NAME
from tbl_authors aut join tbl_users usr
on (aut.OWNER_ID = usr.USER_ID)
where aut.PERS_ID = :id', $params)->fetch();

    // Check if the Author exists in the database
    if (!$author) {
      ErrorController::notFound('Author not found');
      return;
    } else {
      $convertedOwnerID = (int) $author->OWNER_ID;
      $author->OWNER_ID = $convertedOwnerID;
    }

    loadView('authors/show', [
      'author' => $author
    ]);
  }


  /**
   * Show an Author edit form
   *
   * @param array $params
   * @return void
   */
  public function edit($params)
  {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $author = $this->db->query('select aut.PERS_ID, aut.OWNER_ID,
 aut.BIRTH_YEAR, aut.AUTH_NAME, aut.AUTH_BIO, usr.USER_FULLNAME, usr.USER_NAME
 from tbl_authors aut join tbl_users usr
 on (aut.OWNER_ID = usr.USER_ID) where aut.PERS_ID = :id',$params)->fetch();

//     Check if the author exists
    if (!$author) {
      ErrorController::notFound('Publisher not found');
      return;
    }

    $convertedOwnerId = (int) $author->OWNER_ID;
    $author->OWNER_ID = $convertedOwnerId;

//     Authorization
    if (!Authorization::isOwner($author->OWNER_ID)) {
      Session::setFlashMessage('error_message', 'You are not authorized to update this author');
      return redirect('/byblios/editor/show/' . $author->id);
    }

    loadView('authors/edit', [
      'authorData' => $author
    ]);
  }


  /**
   * Update a writer
   *
   * @param array $params
   * @return void
   */
  public function update($params)
  {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $authorCheck = $this->db->query('select aut.OWNER_ID from tbl_authors aut where aut.PERS_ID = :id',$params)->fetch();

//     Check if listing exists
    if (!$authorCheck) {
      ErrorController::notFound('Listing not found');
      return;
    }

    $convertedOwnerId = (int) $authorCheck->OWNER_ID;

//     Authorization
    if (!Authorization::isOwner($convertedOwnerId)) {
      Session::setFlashMessage('error_message', 'You are not authorized to update this author');
      return redirect('/byblios/authors');
    }

    $allowedFields = ['UPDATED_AT', 'AUTH_NAME', 'BIRTH_YEAR', 'AUTH_BIO'];

    $updateValues = [];

    $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

    $updateValues = array_map('sanitize', $updateValues);

    $requiredFields = ['AUTH_NAME', 'BIRTH_YEAR', 'AUTH_BIO'];

    $errors = [];

    foreach ($requiredFields as $field) {
      if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if (empty($errors)) {

      $updateFields = [];

      foreach (array_keys($updateValues) as $field) {
        $updateFields[] = "{$field} = :{$field}";
      }

      $updateFields = implode(', ', $updateFields);

      $updateQuery = "update tbl_authors set {$updateFields} where PERS_ID = :id";

      $updateValues['id'] = $id;

      $this->db->query($updateQuery, $updateValues);

      // Set flash message
      Session::setFlashMessage('success_message', 'Writer updated');

      redirect('/byblios/authors/show/' . $id);
    } else {

      loadView('authors/edit', [
        'authorData' => $updateValues,
        'errors' => $errors
      ]);
      exit;
    }
  }

}
?>
