<?php

session_start();
require '../pawsAdmin/control/tool_functions.php';
require './functions.php';

$errors = [];


if (is_name_valid($_POST["username"])) {
  $name = $_POST["username"];
} else {
  $name = NULL;
  $errors['username'] =  "<p>Please enter Valid Name.</p>";
}


if (is_email_valid($_POST["email"]) && !check_email($dbc, $_POST['email'])) {
  $email = $_POST["email"];
} else {
  $email = NULL;
  $errors['email'] =  "<p>Please enter Valid Email Address.</p>";
}

if (is_password_valid($_POST["password"])) {
  $password = $_POST["password"];
} else {
  $password = NULL;
  $errors['password'] =  "<p>Please enter Valid Password.</p>";
}


if (count($errors) == 0) {

  $result = is_insert($dbc, $name, $email, $password);

  if ($result['success']) {
    $cart_id = getCartID($dbc, $result['user_id']);
    $_SESSION['userinfo'] = [
      'name' => $result['name'],
      'user_id' => $result['user_id'],
      'cart_id' => $cart_id
    ];
    header("Location:../pawsfriend/index.php");
    exit;
  } else {
    echo "</br>Some error in Saving the data";
  }
} else {
  $_SESSION['errors'] = $errors;
  header('Location: ../pawsfriend/signup.php');
}


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

  if ($result) {

    $user_id = mysqli_insert_id(($dbc));
    return [
      'user_id' => $user_id,
      'name' => $name,
      'email' => $email,
      'success' => true
    ];
  } else {
    return ['success' => false];
  }
}
