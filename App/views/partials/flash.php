<?php

  $successMessage = Session::getFlashMessage('success_message');
  $errorMessage = Session::getFlashMessage('error_message');

  if ($successMessage !== null) {
     echo '<div class="message bg-green-100 p-3 my-3">';
     echo $_SESSION['success_message'];
     echo '</div>';
  }

  if ($errorMessage !== null) {
     echo '<div class="message bg-red-100 p-3 my-3">';
     echo $_SESSION['error_message'];
     echo '</div>';
     unset($_SESSION['error_message']);
  }
?>

