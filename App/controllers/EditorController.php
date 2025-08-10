<?php

namespace App\controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;


class EditorController {

  protected $db;
  protected $allowedFields;
  protected $requiredFields;

  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
    $this->allowedFields = [ 'OWNER_ID', 'EDITOR_NAME', 'ADDRESS', 'EDITOR_INFO' ];
    $this->requiredFields = [ 'OWNER_ID', 'EDITOR_NAME', 'ADDRESS', 'EDITOR_INFO' ];
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
   * Show the create publisher form
   *
   * @return void
   */
  public function create()
  {
    $publisherData = [];
    loadView('editor/create', $publisherData);
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
   * Store data in database
   *
   * @return void
   */
  public function store()
  {
     $newEditorData = array_intersect_key($_POST, array_flip($this->allowedFields));
     $newEditorData = array_map('sanitize',$newEditorData);
     $newEditorData['OWNER_ID'] = Session::get('user')['id'];
     $chkName = $newEditorData['EDITOR_NAME'];

     $params = [ 'EDITOR_NAME' => $chkName ];

     $publisher = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME, ed.OWNER_ID
  from tbl_editors ed
  join tbl_users usr on (ed.OWNER_ID = usr.USER_ID)
  where ed.EDITOR_NAME = :EDITOR_NAME', $params)->fetch();

     if($publisher) {
         $errors['EDITOR_NAME'] = 'This is a duplicated Publisher-s Name';
     } else {
         $errors = [];
     }

     foreach ($this->requiredFields as $field) {
         if (empty($newEditorData[$field]) || !Validation::string($newEditorData[$field])) {
           $errors[$field] = ucfirst($field) . ' is required';
         }
     }

     if (empty($errors)) {
        $fields = [];
        $values = [];

        foreach ($newEditorData as $field => $value) {
           $fields[] = $field;
        }

        $fields = implode(', ', $fields);

        foreach ($newEditorData as $field => $value) {
             if ($value === '') {
                 $newEditorData[$field] = null;
             }
             $values[] = ":" . $field;
        }

        $values = implode(', ', $values);

        $insertSql = "insert into `tbl_editors` ({$fields}) values ({$values})";

        $this->db->query($insertSql, $newEditorData);

        Session::setFlashMessage('success_message','Successfully added a new Publisher');

        redirect('/byblios/editor');

     } else {

        loadView('editor/create',[
             'publisherData' => $newEditorData,
             'errors' => $errors
        ]);

     }
  }


  /**
   * Delete an Editor
   *
   * @param array $params
   * @return void
   */
  public function destroy($params) {

    $id = $params['id'] ?? '';

    $params = [ 'id' => $id ];

    $editor = $this->db->query('select ed.EDITOR_ID, ed.OWNER_ID
 from tbl_editors ed
 join tbl_users usr on (ed.OWNER_ID = usr.USER_ID) where ed.EDITOR_ID = :id',$params)->fetch();

    $ownerId = (int) $editor->OWNER_ID;
//     Check if book exists
    if (!$editor) {
      ErrorController::notFound('Publisher not found');
      return;
    }

//     Authorization
    if (!Authorization::isOwner($ownerId)) {
      Session::setFlashMessage('error_message','You are not authorized for this operation');
      return redirect('/byblios/editor/show/' . $editor->EDITOR_ID);
    }

    $this->db->query('delete from tbl_editors where EDITOR_ID = :id',$params);

    Session::setFlashMessage('success_message', 'Successfully deleted the Publisher');

    redirect('/byblios/editor');
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

    $editorCheck = $this->db->query('select ed.OWNER_ID from tbl_editors ed where ed.EDITOR_ID = :id',$params)->fetch();

//     Check if listing exists
    if (!$editorCheck) {
      ErrorController::notFound('Listing not found');
      return;
    }

    $convertedOwnerId = (int) $editorCheck->OWNER_ID;

//     Authorization
    if (!Authorization::isOwner($convertedOwnerId)) {
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

    if (empty($errors)) {

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
    } else {

      loadView('editor/edit', [
        'publisherData' => $updateValues,
        'errors' => $errors
      ]);
      exit;
    }
  }

}

?>

