<?php
session_start();

require_once('../config/db_connect.php');

// set active to current nav item class list
$product_active = 'active';

if (isset($_POST["submit"])) {
  include_once 'control/tool_functions.php';

  // validate inputs
  $errors = [];

  if (isset($_POST['category_id']) && is_numeric($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
  } else {
    $category_id = NULL;
    $errors[] =  "<p class='error'>An exist Category ID is required.</p>";
  }

  if (!empty($_POST['product_name'])) {
    $product_name = $_POST['product_name'];
  } else {
    $product_name = NULL;
    $errors[] =  "<p class='error'>Product name is required.</p>";
  }

  if (!empty($_POST['unit_price']) && is_numeric($_POST['unit_price'])) {
    $unit_price = $_POST['unit_price'];
  } else {
    $unit_price = NULL;
    $errors[] = "<p class='error'>Unit Price is required.</p>";
  }


  if (!empty($_POST['qty_instock']) && is_numeric($_POST['qty_instock'])) {
    $qty_instock = $_POST['qty_instock'];
  } else {
    $qty_instock = NULL;
    $errors[] = "<p class='error'>Instock Quantity is required.</p>";
  }

  if (!empty($_POST['description'])) {
    $description = $_POST['description'];
  } else {
    $description = NULL;
    $errors[] = "<p class='error'>A product description is required.</p>";
  }

  if (!empty($_POST['image_url'])) {
    $image_url = $_POST['image_url'];
  } else {
    $image_url = NULL;
    $errors[] = "<p class='error'>Image URL is required.</p>";
  }

  if (count($errors) == 0) {
    $category_id_clean = prepare_string($dbc, $category_id);
    $product_name_clean = prepare_string($dbc, $product_name);
    $unit_price_clean = prepare_string($dbc, $unit_price);
    $qty_instock_clean = prepare_string($dbc, $qty_instock);
    $description_clean = prepare_string($dbc, $description);
    $image_url_clean = prepare_string($dbc, $image_url);

    $q = "INSERT INTO products(category_id, product_name, unit_price, qty_instock, description, image_url) VALUES(?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'isdiss',
      $category_id_clean,
      $product_name_clean,
      $unit_price_clean,
      $qty_instock_clean,
      $description_clean,
      $image_url_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // calculate the last page
      $last_page = last_page($dbc, 'products');

      header("Location:table_product_view.php?page=$last_page");
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
  <title>Add Product</title>
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

        <label for="category_id">Category ID: </label>
        <?php
        include_once 'control/tool_functions.php';
        get_key_value($dbc, 'categories', 'category_id');
        ?>

        <label for="product_name">Product Name: </label>
        <input type="text" id="product_name" name="product_name">

        <label for="unit_price">Unit Price: </label>
        <input type="text" id="unit_price" name="unit_price">

        <label for="qty_instock">Instock Quantity: </label>
        <input type="text" id="qty_instock" name="qty_instock">

        <label for="description">Description: </label>
        <input type="text" id="description" name="description">

        <label for="image_url">Image URL: </label>
        <input type="text" id="image_url" name="image_url">


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