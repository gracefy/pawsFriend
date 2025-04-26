<?php

// get username
if ($_SESSION['admin_userinfo']) {
  $username = $_SESSION['admin_userinfo']['uname'];
} else {
  header("Location:login_view.php");
  exit;
}
?>

<div class="body">
  <!-- header start -->
  <header class="header">
    <div class="date">
      <?php
      date_default_timezone_set('America/Toronto');
      $currentDate = date('l Y-m-d');
      echo "<span>$currentDate</span>" ?>
    </div>
    <div class="account">
      <?php
      require_once('../config/db_connect.php');
      if ($username) {
        echo 'Hello, ' . $username . '<a href="logout.php">Log out</a>';
      } else {
        echo 'Please <a href="login_view.php">Log In</a>.';
      }
      ?>
    </div>
  </header>
  <!-- header end -->