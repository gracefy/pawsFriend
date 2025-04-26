<?php
session_start();

require_once('../config/db_connect.php');

// set active to current nav item class list
$user_active = 'active';

if (isset($_POST["submit"])) {
  include 'control/tool_functions.php';

  $errors = [];

  if (is_email_valid($_POST["email"])) {
    if (!is_email_exist($dbc, $_POST["email"])) {
      $email = $_POST["email"];
    } else {
      $errors[] =  "<p class='error'>Email Address is already exist.</p>";
    }
  } else {
    $errors[] =  "<p class='error'>Please enter Valid Email Address.</p>";
  }

  if (is_name_valid($_POST["name"])) {
    $name = $_POST["name"];
  } else {
    $errors[] =  "<p class='error'>Please enter Valid Name.</p>";
  }

  if (is_password_valid($_POST["password"])) {
    $password = $_POST["password"];
  } else {
    $errors[] =  "<p class='error'>Please enter Valid Password.</p>";
  }

  if (count($errors) == 0) {

    $result = is_insert($dbc, $name, $email, $password);

    if ($result) {

      // calculate the last page
      $last_page = last_page($dbc, 'users');

      header("Location: table_user_view.php?page=$last_page");
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
  <title>Add User</title>
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
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="User name..">

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" placeholder="Email..">

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" placeholder="Password..">

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

<?php
function is_insert($dbc, $name, $email, $password)
{
  require(DB_CONNECT_PATH);
  $options = [
    'cost' => 10
  ];

  $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);

  $name_clean = prepare_string($dbc, $name);
  $email_clean = prepare_string($dbc, $email);
  $password_clean = prepare_string($dbc, $hashed_password);

  $query = "INSERT INTO users(name , email, password) VALUES (?,?,?)";

  $stmt = mysqli_prepare($dbc, $query);

  mysqli_stmt_bind_param(
    $stmt,
    'sss',
    $name_clean,
    $email_clean,
    $password_clean,
  );

  $result = mysqli_stmt_execute($stmt);
  return $result;
}
?>