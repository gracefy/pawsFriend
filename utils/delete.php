<?php
session_start();
require_once('../utils/functions.php');

$product_id = isset($_GET['id']) ? $_GET['id'] : '';
$cart_id = isset($_GET['c']) ? $_GET['c'] : '';

if ($product_id && $cart_id) {
  $userinfo = isset($_SESSION['userinfo']) ? $_SESSION['userinfo'] : [];
  $result = delete_product($dbc, $cart_id, $product_id);
  if ($result) {
    header('Location: ../pawsfriend/cart.php');
    $_SESSION['userinfo'] = [
      'name' => $userinfo['name'],
      'user_id' => $userinfo['user_id'],
      'cart_id' => $cart_id,
    ];
    exit;
  } else {
    echo "Delete Error";
  }
} else {
  echo "Invalid Inputs";
}
