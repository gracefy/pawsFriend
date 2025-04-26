<?php
session_start();

require_once('../config/db_connect.php');

// set active to current nav item class list
$order_item_active = 'active';

if (isset($_POST["submit"])) {
  include_once 'control/tool_functions.php';

  // validate inputs
  $errors = [];

  if (isset($_POST['order_id'])) {
    if (is_id_exist($dbc, $_POST['order_id'], 'orders', 'order_id')) {
      $order_id = $_POST['order_id'];
    } else {
      $order_id = NULL;
      $errors[] =  "<p class='error'>Order ID does not exist.</p>";
    }
  } else {
    $order_id = NULL;
    $errors[] =  "<p class='error'>An exist Order ID is required.</p>";
  }

  if (isset($_POST['product_id'])) {
    if (is_id_exist($dbc, $_POST['product_id'], 'products', 'product_id')) {
      $product_id = $_POST['product_id'];
    } else {
      $product_id = NULL;
      $errors[] =  "<p class='error'>Product ID does not exist.</p>";
    }
  } else {
    $product_id = NULL;
    $errors[] =  "<p class='error'>An exist Product ID is required.</p>";
  }

  if (isset($_POST['quantity'])) {
    $quantity = $_POST['quantity'];
  } else {
    $quantity = NULL;
    $errors[] =  "<p class='error'>Quantity is required.</p>";
  }

  if (!empty($_POST['discount'])) {
    $discount = $_POST['discount'];
  } else {
    $discount = NULL;
    $errors[] = "<p class='error'>Discount is required.</p>";
  }

  if (count($errors) == 0) {
    $order_id_clean = prepare_string($dbc, $order_id);
    $product_id_clean = prepare_string($dbc, $product_id);
    $quantity_clean = prepare_string($dbc, $quantity);
    $discount_clean = prepare_string($dbc, $discount);

    $q = "INSERT INTO order_items(order_id, product_id, quantity, discount) VALUES(?, ?, ?, ?)";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'iiid',
      $order_id_clean,
      $product_id_clean,
      $quantity_clean,
      $discount_clean,
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // calculate the last page
      $last_page = last_page($dbc, 'order_items');

      header("Location:table_order_item_view.php?page=$last_page");
    } else {
      echo "<p class='error'>Some error in Saving the data</p>";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Order Items</title>
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/5775cf1f45.js" crossorigin="anonymous"></script>
  <!-- css files -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/sidebar.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="assets/css/table.css">
  <link rel="stylesheet" href="assets/css/form.css">
</head>

<body>
  <?php
  // include header and sidebar
  include 'includes/header.php';
  include 'includes/sidebar.php';
  ?>

  <div class="main">
    <h3>Please enter the data to be saved in the Database</h3>

    <div class="form">
      <form action="" method="post">

        <label for="order_id">Order ID: </label>
        <?php
        include_once 'control/tool_functions.php';
        get_key_value_bydate($dbc, 'orders', 'order_id', 'order_date');
        ?>

        <label for="product_id">Product ID: </label>
        <?php
        include_once 'control/tool_functions.php';
        get_key_value($dbc, 'products', 'product_id');
        ?>

        <label for="quantity">Quantity: </label>
        <input type="text" id="quantity" name="quantity">

        <label for="discount">Discount: </label>
        <select name="discount">
          <?php
          $options = [];

          for ($i = 10; $i >= 1; $i--) {
            $options[] = number_format($i / 10, 2);
          }

          foreach ($options as $option) {
            echo "<option value='$option'> $option </option>";
          }
          ?>
        </select>

        <button type="submit" name="submit">Insert data into Database</button>
      </form>
    </div>

    <?php
    if (!empty($errors)) {
      foreach ($errors as $error) {
        echo $error;
      }
    }
    ?>

  </div>


</body>

</html>