<?php

namespace App\controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
  protected $db;

  public function __construct()
  {
    $config = require basePath('config/_db.php');
    $this->db = new Database($config);
  }

  /**
   * Show the login page
   * 
   * @return void
   */
  public function login()
  {
    loadView('users/login');
  }

  /**
   * Show the register page
   * 
   * @return void
   */
  public function create()
  {
    loadView('users/create');
  }

  /**
   * Store user in database
   * 
   * @return void
   */
  public function store()
  {
//   inspectAndDie($_SERVER);
     $name = $_POST['name'];
     $email = $_POST['email'];
     $city = $_POST['city'];
     $state = $_POST['state'];
     $password = $_POST['password'];
     $passwordConfirmation = $_POST['password_confirmation'];

     $errors = [];

//     Validation
     if (!Validation::email($email)) {
       $errors['email'] = 'Please enter a valid email address';
     }

    if (!Validation::string($name, 2, 50)) {
      $errors['name'] = 'Name must be between 2 and 50 characters';
    }

    if (!Validation::string($password, 6, 50)) {
      $errors['password'] = 'Password must be at least 6 characters';
    }

    if (!Validation::match($password, $passwordConfirmation)) {
      $errors['password_confirmation'] = 'Passwords do not match';
    }

    if (!empty($errors)) {
      loadView('users/create', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
          'state' => $state
        ]
      ]);
      exit;
    }

//     Check if email exists
    $params = [ 'email' => $email ];

    $user = $this->db->query('SELECT usr.USER_ID FROM tbl_users usr WHERE usr.USER_EMAIL = :email', $params)->fetch();

    if ($user) {
      $errors['email'] = 'That email already exists';
      loadView('users/create', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
          'state' => $state
        ]
      ]);
      exit;
    }
 
//     Create user account
    $params = [
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'state' => $state,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    $this->db->query('insert into tbl_users (USER_VALID, USER_ROLE, USER_NAME, USER_EMAIL, USER_VALID_EMAIL, USER_FULLNAME,
                      USER_ADDR1, USER_ADDR2, USER_PWD) values (0, 111433001, \'just_arrived\', :email, 0, :name, :city,
                      :state, :password)', $params);

//     Get new user ID
    $userId = $this->db->conn->lastInsertId();

//     Set user session
    Session::set('user', [
      'id' => $userId,
      'is_valid_user' => 0,
      'username' => 'guest',
      'userrole' => 111433001,
      'name' => $name,
      'email' => $email,
      'is_valid_email' => 0,
      'addr' => $city,
      'county' => $state
    ]);

    redirect('/byblios/');
  }

}
?>
