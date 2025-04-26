<?php
session_start();

// set active to current nav item class list
$user_active = 'active';

// get operating data from database
require('control/tool_functions.php');
$result = select_all($dbc, 'users', 'user_id');

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

  $user_id_operate = $row['user_id'];
  $name_operate = $row['name'];
  $email_operate = $row['email'];
}


// validate update inputs
$errors = [];
if (isset($_POST['submit'])) {
  if (!empty($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
  } else {
    $user_id = null;
    $errors[] = "<p>User ID NOT Found.</p>";
  }

  if (is_name_valid($_POST["name"])) {
    $name = $_POST["name"];
  } else {
    $name = null;
    $errors[] =  "<p class='error'>Please enter Valid Name.</p>";
  }


  if (is_email_valid($_POST["email"])) {
    if (!is_email_exist($dbc, $_POST["email"])) {
      $email = $_POST["email"];
    } elseif (is_email_exist($dbc, $_POST["email"]) && $_POST["email"] == $row['email']) {
      $email = $_POST["email"];
    } else {
      $email = null;
      $errors[] =  "<p class='error'>Email Address is already exist.</p>";
    }
  } else {
    $email = null;
    $errors[] =  "<p class='error'>Please enter Valid Email Address.</p>";
  }

  if (count($errors) == 0) {

    $page = isset($_SESSION['page_info']['page']) ? $_SESSION['page_info']['page'] : 1;

    $user_id_clean = prepare_string($dbc, $user_id);
    $name_clean = prepare_string($dbc, $name);
    $email_clean = prepare_string($dbc, $email);

    $query = "UPDATE users SET name = ?, email = ? WHERE user_id = ?;";

    $stmt = mysqli_prepare($dbc, $query);

    mysqli_stmt_bind_param(
      $stmt,
      'ssi',
      $name_clean,
      $email_clean,
      $user_id_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      header("Location:table_user_view.php?page=$page");
      exit;
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
  <title>Edit User</title>
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

  <!-- create table with operated record -->
  <div class="main">
    <h3>Please enter the data to update in the Database</h3>

    <div class="form">
      <form action="" method="post">
        <label for="user_id">User ID: </label>
        <input type="text" id="user_id" name="user_id" value="<?php echo $user_id_operate; ?>" readonly>

        <label for="name">Name: </label>
        <input type="text" id="name" name="name" value="<?php echo $name_operate; ?>">

        <label for="email">Email: </label>
        <input type="text" id="email" name="email" value="<?php echo $email_operate; ?>">

        <button type="submit" name="submit">Update data in Database</button>
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