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

}

?>
