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
   * Show the reset password page
   *
   * @return void
   */
  public function reset()
  {
    loadView('users/reset');
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


  /**
   * Generate a temporary teken
   * for the user who lost his/her password
   * 
   * @return void
   */
  public function gen_token()
  {
    $email = $_POST['email'];
    $timestmp = $_POST['CREATED_AT'];
    $errors = [];

//      Validation
    if (!Validation::email($email)) {
      $errors['email'] = 'Please enter a valid email';
    }

//      Check for email
    $params = [ 'email' => $email ];

    $user = $this->db->query('select usr.USER_ID
from tbl_users usr where usr.USER_VALID_EMAIL = 1 and usr.USER_VALID = 1
and usr.USER_EMAIL = :email', $params)->fetch();

    if (!$user) {
      $errors['user'] = 'Invalid user';
      loadView('users/reset', [
        'errors' => $errors
      ]);
      exit;
    }

    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
    $params = [ 'user' => $user->USER_ID,
                'email' => $email,
                'token'=> $token,
                'expires' => date("Y-m-d H:i:s", strtotime("+1 hour")),
                'created' => $timestmp
    ];

    $query = 'insert into tbl_psswdrst(USER_ID, USER_EMAIL, TMP_TOKEN, EXPIRES_AT, CREATED_AT) values (:user, :email, :token, :expires, :created)';

    $this->db->query($query,$params);

    loadView('users/done', [
       'expires' => $expires
    ]);
    exit;
  }


  /**
   * Logout a user and kill session
   * 
   * @return void
   */
  public function logout()
  {
    Session::clearAll();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

    redirect('/byblios');
  }


  /**
   * Authenticate a user with email and password
   * 
   * @return void
   */
  public function authenticate()
  {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];

//      Validation
    if (!Validation::email($email)) {
      $errors['email'] = 'Please enter a valid email';
    }

    if (!Validation::string($password, 6, 50)) {
      $errors['password'] = 'Password must be at least 6 characters';
    }

//      Check for errors
    if (!empty($errors)) {
      loadView('users/login', [
        'errors' => $errors
      ]);
      exit;
    }

//      Check for email
    $params = [ 'email' => $email ];

    $user = $this->db->query('select usr.USER_ID, usr.USER_NAME, usr.USER_FULLNAME,
           usr.USER_PWD, usr.USER_ROLE, usr.USER_EMAIL, usr.USER_ADDR1, usr.USER_ADDR2
from tbl_users usr where usr.USER_VALID_EMAIL = 1 and usr.USER_VALID = 1
and usr.USER_EMAIL = :email', $params)->fetch();

    if (!$user) {
      $errors['email'] = 'Incorrect credentials';
      loadView('users/login', [
        'errors' => $errors
      ]);
      exit;
    }

//     Check if password is correct
    if (!password_verify($password, $user->USER_PWD)) {
      $errors['email'] = 'Incorrect credentials';
      loadView('users/login', [
        'errors' => $errors
      ]);
      exit;
    }

    $userId = $this->db->conn->lastInsertId();

//     Set user session
    Session::set('user', [
      'id' => $user->USER_ID,
      'is_valid_user' => 1,
      'username' => $user->USER_NAME,
      'role' => $user->USER_ROLE,
      'name' => $user->USER_FULLNAME,
      'email' => $user->USER_EMAIL,
      'is_valid_email' => 1,
      'addr' => $user->USER_ADDR1,
      'county' => $user->USER_ADDR2
    ]);

    redirect('/byblios/');


  }
}
?>
