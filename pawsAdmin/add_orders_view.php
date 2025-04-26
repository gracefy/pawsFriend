<?php
session_start();

require_once('../config/db_connect.php');

// set active to current nav item class list
$order_active = 'active';

if (isset($_POST["submit"])) {
  include_once 'control/tool_functions.php';

  // validate inputs
  $errors = [];

  if (isset($_POST['user_id'])) {
    if (is_id_exist($dbc, $_POST['user_id'], 'users', 'user_id')) {
      $user_id = $_POST['user_id'];
    } else {
      $user_id = NULL;
      $errors[] =  "<p class='error'>User ID does not exist.</p>";
    }
  } else {
    $user_id = NULL;
    $errors[] =  "<p class='error'>An exist User ID is required.</p>";
  }

  if (isset($_POST['date'])) {
    $date = $_POST['date'];
  } else {
    $date = NULL;
    $errors[] =  "<p class='error'>Date is required.</p>";
  }

  if (count($errors) == 0) {
    $user_id_clean = prepare_string($dbc, $user_id);
    $date_clean = prepare_string($dbc, $date);

    $q = "INSERT INTO orders(user_id, order_date) VALUES( ?, ?)";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'ss',
      $user_id_clean,
      $date_clean,
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // calculate the last page
      $last_page = last_page($dbc, 'orders');

      header("Location:table_order_view.php?page=$last_page");
    } else {
      echo "<p class='error'>Some error in Saving the data</p>";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Orders</title>
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/5775cf1f45.js" crossorigin="anonymous"></script>
  <!-- css files -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/sidebar.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="assets/css/table.css">
  <link rel="stylesheet" href="assets/css/form.css">
</head>

<body>
  <?php
  // include header and sidebar
  include 'includes/header.php';
  include 'includes/sidebar.php';
  ?>

  <div class="main">
    <h3>Please enter the data to be saved in the Database</h3>

    <div class="form">
      <form action="" method="post">

        <label for="user_id">User ID: </label>
        <?php
        include_once 'control/tool_functions.php';
        get_key_value($dbc, 'users', 'user_id');
        ?>

        <label for="Date">Date: </label>
        <input type="datetime-local" id="date" name="date">

        <button type="submit" name="submit">Insert data into Database</button>
      </form>
    </div>

    <?php
    if (!empty($errors)) {
      foreach ($errors as $error) {
        echo $error;
      }
    }
    ?>

  </div>


</body>

</html>