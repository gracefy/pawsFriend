<?php
require('../utils/functions.php');
$start = isset($_GET['start']) ? $_GET['start'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 12;

try {
  if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    $data['pets'] = fetch_pet($dbc, $filter, $start, $limit);
    $data['total_rows'] = total_rows_pets($dbc, $filter);
  } else {
    $data['pets'] = fetch_pet($dbc, '', 0, 12);
    $data['total_rows'] = total_rows_pets($dbc, 'all');
  }
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
} catch (Exception $e) {
  header('Content-Type: application/json');
  echo json_encode($e->getMessage());
  exit;
}
