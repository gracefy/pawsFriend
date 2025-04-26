<?php
require('../utils/functions.php');

$data = ['none' => 'No Cart Found', 'cart_qty' => 0];

if (isset($_GET['c'])) {
  $cart_id = $_GET['c'];

  if (isset($_GET['type']) && $_GET['type'] === 'qty') {
    $cart_qty = get_cart_qty($dbc, $cart_id);
    if ($cart_qty > 0) {
      $data = [
        'cart_qty' => $cart_qty,
      ];
    } else {
      $data = ['none' => 'You have not added any products.'];
    }
  } else {
    $cart_data = fetch_cart_item($dbc, $cart_id);
    if ($cart_data) {
      $data = [
        'cart_items' => $cart_data,
      ];
    } else {
      $data = ['none' => 'You have not added any products.'];
    }
  }
}

header('Content-Type: application/json');
echo json_encode($data);
exit;
