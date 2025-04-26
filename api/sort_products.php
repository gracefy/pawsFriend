<?php
require('../utils/functions.php');
$start = isset($_GET['start']) ? $_GET['start'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 16;

try {
  if (isset($_GET['l'])) {
    $label = $_GET['l'];
    $data['product'] = fetch_product_category($dbc, $label, $start, $limit);
    $data['total_rows'] = total_rows_products($dbc, $label);
  } elseif (isset($_GET['o'])) {
    $option = $_GET['o'];
    $data['product'] = fetch_product_sort($dbc, $option, $start, $limit);
    $data['total_rows'] = total_rows_products($dbc, 'all');
  } elseif (isset($_GET['s'])) {
    $key_words = $_GET['s'];
    $data['product'] = fetch_product_bysearch($dbc, $key_words, $start, $limit);
    $data['total_rows'] = total_rows_bysearch($dbc, 'products', 'product_name', $key_words);
  } else {
    $data['product'] = fetch_product_sort($dbc, '', $start, $limit);
    $data['total_rows'] = total_rows_products($dbc, 'all');
  }
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
} catch (Exception $e) {
  header('Content-Type: application/json');
  echo json_encode($e->getMessage());
  exit;
}
