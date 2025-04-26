<?php
session_start();
require_once('../config/db_connect.php');

$error = '';
if (isset($_POST['login'])) {
  $input_username = isset($_POST['username']) ? $_POST['username'] : '';
  $input_password = isset($_POST['password']) ? $_POST['password'] : '';
  $input_username_clean = prepare_string($dbc, $input_username);
  $query = "SELECT * FROM admin_users WHERE username = ?;";
  $stmt = mysqli_prepare($dbc, $query);
  mysqli_stmt_bind_param($stmt, 's', $input_username_clean);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($input_username == $row['username'] && $input_password == $row['password']) {
      $_SESSION['admin_userinfo'] = [
        'uid' => $row['user_id'],
        'uname' => $row['username'],
        'uEmail' => $row['email']
      ];
      header(("Location: index.php"));
      exit;
    } else {
      $error = "Please Enter Valid Username and Password!";
    }
  }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
  <div class="logo"></div>
  <div>
    <form class="login-form" method="post">
      <label for="username">User Name</label>
      <input type="text" id="username" name="username" placeholder="username">

      <label for="password">Password</label>
      <input type="text" id="password" name="password" placeholder="password">

      </select>

      <input type="submit" value="Login" name="login">
    </form>
  </div>
  </form>
  <?php echo '<p class="error-info">' . $error . '</p>' ?>
</body>

</html>