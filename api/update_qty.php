<?php
require('../utils/functions.php');

$input = file_get_contents("php://input");

// Decode the JSON input into an array
$data = json_decode($input, true);

if ($data) {
  $cart_id = $data['cart_id'];
  $product_id = $data['product_id'];
  $quantity = $data['quantity'];

  $result = update_product_qty($dbc, $cart_id, $product_id, $quantity);

  if ($result) {
    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Update Failure']);
  }
} else {
  // Send an error response if the input is not valid JSON
  http_response_code(400);
  echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
