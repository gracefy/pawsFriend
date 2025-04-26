<?php
require('../utils/functions.php');

if (isset($_GET['u'])) {
  $user_id = $_GET['u'];

  $order_ids = get_order_id($dbc, $user_id);

  $data = [];
  foreach ($order_ids as $order_id) {
    $data[] = get_order_items($dbc, $order_id);
  }

  header('Content-Type: application/json');
  echo json_encode(['data' => $data, 'order_ids' => $order_ids]);
  exit;
} else {
  echo json_encode(['success' => false, 'message' => 'No User ID Found']);
}
