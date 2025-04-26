<?php
require('../utils/functions.php');
$start = isset($_GET['start']) ? $_GET['start'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 5;

try {
  if (isset($_GET['l']) && $_GET['l'] != 'all') {
    $filter = $_GET['l'];
    $data['blogs'] = fetch_blogs_bysearch($dbc, $filter, $start, $limit);
    $data['total_rows'] = total_rows_bysearch($dbc, 'blogs', 'title', $filter);
  } elseif (isset($_GET['s']) && $_GET['s'] != 'all') {
    $filter = $_GET['s'];
    $data['blogs'] = fetch_blogs_bysearch($dbc, $filter, $start, $limit);
    $data['total_rows'] = total_rows_bysearch($dbc, 'blogs', 'title', $filter);
  } else {
    $data['blogs'] = fetch_blogs($dbc, $start, $limit);
    $data['total_rows'] = total_rows($dbc, 'blogs', 'blog_id');
  }
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
} catch (Exception $e) {
  header('Content-Type: application/json');
  echo json_encode($e->getMessage());
  exit;
}
