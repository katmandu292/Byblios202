<?php

  use Framework\Session;

  if (Session::has('flash_success_message')) {
     $successMessage = Session::getFlashMessage('success_message');
  } else {
     $successMessage = null;
  }

  if ($successMessage !== null) {
     echo '<div class="message bg-green-100 p-3 my-3">';
     echo $successMessage;
     echo '</div>';
     Session::clear('flash_success_message');
  }

  if (Session::has('flash_error_message')) {
     $errorMessage = Session::getFlashMessage('error_message');
  } else {
     $errorMessage =  null;
  }

  if ($errorMessage !== null) {
     echo '<div class="message bg-red-100 p-3 my-3">';
     echo $errorMessage;
     echo '</div>';
     Session::clear('flash_error_message');
  }
?>

