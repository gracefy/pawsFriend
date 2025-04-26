<?php
session_start();
require_once('../utils/functions.php');
$user_id = isset($_SESSION['userinfo']['user_id']) ? $_SESSION['userinfo']['user_id'] : '';
$cart_id = isset($_SESSION['userinfo']['cart_id']) ? $_SESSION['userinfo']['cart_id'] : '';
$product_id = isset($_SESSION['product']['product_id']) ? $_SESSION['product']['product_id'] : '';
$errors = [];
$results = [];

if (isset($_POST['quantity'])) {
  $quantity = $_POST['quantity'];
} else {
  $quantity = '';
  $errors['qty'] = "Please provide a quantity number.";
}


if (!empty($user_id) && !empty($product_id) && !empty($quantity)) {

  $q = "INSERT INTO cart_items(cart_id, product_id, quantity) VALUES (?, ?, ?)
  ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity);";

  $cart_id_clean = prepare_string($dbc, $cart_id);
  $product_id_clean = prepare_string($dbc, $product_id);
  $quantity_clean = prepare_string($dbc, $quantity);

  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param(
    $stmt,
    'iii',
    $cart_id_clean,
    $product_id_clean,
    $quantity_clean
  );

  $result = mysqli_stmt_execute($stmt);

  if ($result) {
    $results['done'] = "Added successfully";
  } else {
    $errors['insert'] = "Some error in saving data, please try again";
  }
  mysqli_stmt_close($stmt);
} else {
  $errors['login'] = "Data error, please refresh the page or login again";
}

if (count($errors) == 0) {
  $_SESSION['success'] = $results;
  echo '<script src="../pawsfriend/assets/js/cart_qty.js"></script>';
} else {
  $_SESSION['errors'] = $errors;
}

header("Location: ../pawsfriend/detail.php?id=$product_id");
exit;
