<?php
  if (isset($_SESSION['success_message'])) {
     echo '<div class="message bg-green-100 p-3 my-3">';
     echo $_SESSION['success_message'];
     echo '</div>';
     unset($_SESSION['success_message']);
  }

  if (isset($_SESSION['error_message'])) {
     echo '<div class="message bg-red-100 p-3 my-3">';
     echo $_SESSION['success_message'];
     echo '</div>';
     unset($_SESSION['success_message']);
  }
?>

