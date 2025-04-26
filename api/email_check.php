<?php
require('../utils/functions.php');

if (!isset($_GET['email'])) {
  http_response_code(400);
  die();
}

$found = check_email($dbc, $_GET['email']);

$status = 'valid';

if ($found) {
  $status = 'duplicate';
}

header('Content-Type: application/json');
echo json_encode(array('status' => $status));
exit;
