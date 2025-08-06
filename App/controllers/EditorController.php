<?php

namespace App\controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class EditorController {

  protected $db;

  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
  }

  /**
   * Show all publishers
   * 
   * @return void
   */
  public function index()
  {
    $editorsList = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME,
 ed.OWNER_ID, ed.ADDRESS, ed.EDITOR_INFO, usr.USER_NAME, usr.USER_FULLNAME 
from tbl_editors ed
join tbl_users usr on (ed.OWNER_ID = usr.USER_ID)')->fetchAll();


    loadView('editor/index', [
      'editors' => $editorsList
    ]);
  }

  /**
   * Show a single publisher
   *
   * @param array $params
   * @return void
   */
  public function show($params)
  {
    $id = $params['id'] ?? '';

    $params = [ 'id' => $id ];

    $publisher = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME, ed.OWNER_ID,
ed.EDITOR_NAME, ed.ADDRESS, ed.EDITOR_INFO, usr.USER_NAME,
usr.USER_FULLNAME
from tbl_editors ed
join tbl_users usr on (ed.OWNER_ID = usr.USER_ID)
where ed.EDITOR_ID = :id', $params)->fetch();

    // Check if publisher exists in the database
    if (!$publisher) {
      ErrorController::notFound('Publisher not found');
      return;
    } else {
      $convertedOwnerID = (int) $publisher->OWNER_ID;
      $publisher->OWNER_ID = $convertedOwnerID;
    }

    loadView('editor/show', [
      'publisher' => $publisher
    ]);
  }

  /**
   * Show the Publisher edit form
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

    $publisher = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME, ed.OWNER_ID,
ed.ADDRESS, ed.EDITOR_INFO, usr.USER_NAME, usr.USER_FULLNAME
from tbl_editors ed
join tbl_users usr on (ed.OWNER_ID = usr.USER_ID) where ed.EDITOR_ID = :id',$params)->fetch();

//     Check if listing exists
    if (!$publisher) {
      ErrorController::notFound('Publisher not found');
      return;
    }

    $convertedOwnerId = (int) $publisher->OWNER_ID;
    $publisher->OWNER_ID = $convertedOwnerId;

//     Authorization
    if (!Authorization::isOwner($publisher->OWNER_ID)) {
      Session::setFlashMessage('error_message', 'You are not authorized to update this publisher');
      return redirect('/byblios/editor/show/' . $publisher->id);
    }

    loadView('editor/edit', [
      'publisherData' => $publisher
    ]);
  }

  /**
   * Update a publisher
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

    $publisher = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME, ed.OWNER_ID,
ed.ADDRESS, ed.EDITOR_INFO from tbl_editors ed
where ed.EDITOR_ID = :id',$params)->fetch();

//     Check if listing exists
    if (!$publisher) {
      ErrorController::notFound('Listing not found');
      return;
    }

    $convertedOwnerId = (int) $publisher->OWNER_ID;
    $publisher->OWNER_ID = $convertedOwnerId;

//     Authorization
    if (!Authorization::isOwner($publisher->OWNER_ID)) {
      Session::setFlashMessage('error_message', 'You are not authorized to update this publisher');
      return redirect('/byblios/editor');
    }

    $allowedFields = ['UPDATED_AT', 'EDITOR_NAME', 'ADDRESS', 'EDITOR_INFO'];

    $updateValues = [];

    $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

    $updateValues = array_map('sanitize', $updateValues);

    $requiredFields = ['EDITOR_NAME', 'ADDRESS', 'EDITOR_INFO'];

    $errors = [];

    foreach ($requiredFields as $field) {
      if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if (!empty($errors)) {
      loadView('editor/edit', [
        'publisherData' => $publisher,
        'errors' => $errors
      ]);
      exit;
    } else {
//       Submit to database
      $updateFields = [];

      foreach (array_keys($updateValues) as $field) {
        $updateFields[] = "{$field} = :{$field}";
      }

      $updateFields = implode(', ', $updateFields);

      $updateQuery = "update tbl_editors set {$updateFields} where EDITOR_ID = :id";

      $updateValues['id'] = $id;
      $this->db->query($updateQuery, $updateValues);

      // Set flash message
      Session::setFlashMessage('success_message', 'Publisher updated');

      redirect('/byblios/editor/show/' . $id);
    }
  }

}

?>

