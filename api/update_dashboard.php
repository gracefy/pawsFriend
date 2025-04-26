<?php
require_once('../pawsAdmin/control/tool_functions.php');
date_default_timezone_set('America/Toronto');

// Get the current date
$today = date('Y-m-d');
$today_price = calculate_day_price($dbc, $today);

$yesterday = date('Y-m-d', strtotime('-1 day'));
$yesterday_price = calculate_day_price($dbc, $yesterday);

$firstday_month = date('Y-m-1');
$lastday_month = date('Y-m-t');
$month_price = calculate_period_price($dbc, $firstday_month, $lastday_month);

$firstday_year = date('Y-01-01');
$current_day = date('Y-m-d');
$year_price = calculate_period_price($dbc, $firstday_year, $current_day);

$data = [
  'today_price' => $today_price,
  'yesterday_price' => $yesterday_price,
  'month_price' => $month_price,
  'year_price' => $year_price
];

// JSON response
header('Content-Type: application/json');
echo json_encode($data);
