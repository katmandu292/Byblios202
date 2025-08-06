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
    $editorsList = $this->db->query('select ed.EDITOR_ID, ed.EDITOR_NAME, ed.OWNER_ID,
ed.EDITOR_NAME, ed.ADDRESS, ed.EDITOR_INFO, usr.USER_NAME, usr.USER_FULLNAME 
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


}

?>

