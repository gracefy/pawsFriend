<?php
if (!defined('DB_CONNECT_PATH')) {
  define('DB_CONNECT_PATH', __DIR__ . '/../../config/db_connect.php');
}
require(DB_CONNECT_PATH);

// validate functions
function is_email_valid($email)
{
  if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return true;
  }
}

function is_email_exist($dbc, $email)
{

  $email_clean = prepare_string($dbc, $email);

  $q = "SELECT email FROM users WHERE email = ?;";

  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param($stmt, 's', $email_clean);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) > 0) {
    return true;
  }
}

function is_name_valid($username)
{
  if (!empty($username) && preg_match("/^[a-zA-Z-' ]*$/", $username)) {
    return true;
  }
}

function is_password_valid($password)
{
  if (!empty($password) && preg_match("/^\w{6,13}$/s", $password)) {
    return true;
  }
}

function is_phone_valid($phone)
{
  if (!empty($phone) && count_numbers($phone) == 10) {
    return true;
  }
}
function count_numbers($str)
{
  return preg_match_all("/[0-9]/", $str);
}


function is_postal_code_valid($postal)
{
  $str = preg_replace('/\s/', '', $postal);
  $pattern = '/^[A-Za-z]\d[A-Za-z]\d[A-Za-z]\d$/';

  if (!empty($postal) && preg_match($pattern, $str)) {
    return true;
  }
}






// check if id is already exist
function is_id_exist($dbc, $id, $table, $field_name)
{
  $id_clean = prepare_string($dbc, $id);

  $q = "SELECT $field_name FROM $table WHERE $field_name = ?;";

  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param($stmt, 'i', $id_clean);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) > 0) {
    return true;
  }
}

// get the last page of a table
function last_page($dbc, $table)
{
  $q = "SELECT COUNT(*) as total FROM $table";
  $result_q = mysqli_query($dbc, $q);
  while ($row = mysqli_fetch_assoc($result_q)) {
    $total_rows = $row['total'];
    $per_page = 10;
    $last_page = ceil($total_rows / $per_page);
  }
  return $last_page;
}

// datele function
function delete($dbc, $id, $table, $file_name)
{
  $error = null;

  if (!empty($_GET[$id])) {
    $current_id = $_GET[$id];
  } else {
    $current_id = null;
    $error = "<p>Error! $id not found!</p>";
  }

  if ($error == null) {
    $q = "DELETE FROM $table WHERE $id = ?;";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'i', $current_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      // calculate the last page
      $last_page = last_page($dbc, $table);
      $page = ($_SESSION['page_info']['page'] > $last_page) ? $last_page : $_SESSION['page_info']['page'];
      header("Location:../$file_name?page=$page");
      exit;
    } else {
      echo "<p class='error'>Some error in Deleting the record.</p>";
    }
  } else {
    echo $error;
  }
}

// select the whole row of a sepecific ID
function select_all($dbc, $table, $id)
{
  $error = '';

  if (!empty($_GET[$id])) {
    $current_id = $_GET[$id];
  } else {
    $current_id = null;
    $error = "<p>Error! $id not found.</p>";
  }

  // sanitize data before update into database
  if ($error == null) {
    $id_clean = prepare_string($dbc, $current_id);
    $q = "SELECT * FROM $table WHERE $id = ?";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'i', $id_clean);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  } else {
    echo $error;
  }

  return $result;
}


// get the whole column value of a filed
function get_key_value($dbc, $table, $key_name)
{
  $table_clean = prepare_string($dbc, $table);
  $key_name_clean = prepare_string($dbc, $key_name);

  $q = "SELECT $key_name_clean FROM $table_clean;";
  $result = mysqli_query($dbc, $q);
  if ($result) {
    echo "<select name='$key_name'>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<option value='$row[$key_name_clean]'>$row[$key_name_clean]</option>";
    }
    echo "</select>";
    mysqli_free_result($result);
  } else {
    echo "<p class='error'>Error: " . mysqli_error($dbc) . "</p>";
  }
}


function get_key_value_bydate($dbc, $table, $key_name, $date)
{
  $table_clean = prepare_string($dbc, $table);
  $key_name_clean = prepare_string($dbc, $key_name);

  $q = "SELECT $key_name_clean FROM $table_clean ORDER BY $date DESC;";
  $result = mysqli_query($dbc, $q);
  if ($result) {
    echo "<select name='$key_name'>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<option value='$row[$key_name_clean]'>$row[$key_name_clean]</option>";
    }
    echo "</select>";
    mysqli_free_result($result);
  } else {
    echo "<p class='error'>Error: " . mysqli_error($dbc) . "</p>";
  }
}


// get the whole column value of a filed which matched another table
function get_matched_key_value($dbc, $table1, $table2, $key_name)
{
  $q = "SELECT $table1.$key_name FROM $table1 LEFT JOIN $table2 ON $table1.$key_name = $table2.$key_name;";

  $result = mysqli_query($dbc, $q);
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<option value='$row[$key_name]'>$row[$key_name]</option>";
    }
    mysqli_free_result($result);
  } else {
    echo "<p class='error'>Error: " . mysqli_error($dbc) . "</p>";
  }
}

// calculate order price of one day
function calculate_day_price($dbc, $time)
{
  // Query to get the total order price for today
  $sql = "SELECT SUM(products.unit_price * order_items.quantity * order_items.discount) AS total_price
        FROM orders
        JOIN order_items ON orders.order_id = order_items.order_id
        JOIN products ON order_items.product_id = products.product_id
        WHERE DATE(orders.order_date) = '$time'";

  $result = $dbc->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalPrice = $row['total_price'];


    if (isset($totalPrice)) {
      return number_format($totalPrice, 2);
    } else {
      return "0";
    }
  } else {
    return "0";
  }
}


// calculate total price for a period
function calculate_period_price($dbc, $firstDay, $lastDay)
{
  // Query to get the total order price for today
  $sql = "SELECT SUM(products.unit_price * order_items.quantity * order_items.discount) AS total_price
        FROM orders
        JOIN order_items ON orders.order_id = order_items.order_id
        JOIN products ON order_items.product_id = products.product_id
        WHERE DATE(orders.order_date) BETWEEN '$firstDay' AND '$lastDay'";

  $result = $dbc->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalPrice = $row['total_price'];

    // Format the total price only if it is set
    return isset($totalPrice) ? number_format($totalPrice, 2) : "0";
  } else {
    return "0";
  }
}
