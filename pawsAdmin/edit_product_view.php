<?php
session_start();

// set active to current nav item class list
$product_active = 'active';

// get data from database
require('control/tool_functions.php');
$result = select_all($dbc, 'products', 'product_id');

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

  $product_id_get = $row['product_id'];
  $category_id_get = $row['category_id'];
  $product_name_get = $row['product_name'];
  $unit_price_get = $row['unit_price'];
  $qty_instock_get = $row['qty_instock'];
  $description_get = $row['description'];
  $image_url_get = $row['image_url'];
}

// validate update inputs
$errors = [];
if (isset($_POST['submit'])) {
  // validate inputs
  $errors = [];
  if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
  } else {
    $product_id = NULL;
    $errors[] =  "<p class='error'>An exist Product ID is required.</p>";
  }


  if (isset($_POST['category_id']) && is_numeric($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
  } else {
    $category_id = NULL;
    $errors[] =  "<p class='error'>An exist Category ID is required.</p>";
  }

  if (isset($_POST['product_name'])) {
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

    $product_name_clean = prepare_string($dbc, $product_name);
    $unit_price_clean = prepare_string($dbc, $unit_price);
    $qty_instock_clean = prepare_string($dbc, $qty_instock);
    $description_clean = prepare_string($dbc, $description);
    $image_url_clean = prepare_string($dbc, $image_url);

    $q = "UPDATE products SET product_name = ?, unit_price = ?, qty_instock = ?, description = ?, image_url = ? WHERE product_id = $product_id;";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'sdiss',
      $product_name_clean,
      $unit_price_clean,
      $qty_instock_clean,
      $description_clean,
      $image_url_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // get current page
      $page = isset($_SESSION['page_info']['page']) ? $_SESSION['page_info']['page'] : 1;

      header("Location:table_product_view.php?page=$page");
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
  <title>Edit Product</title>
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

  <!-- create table with operated record -->
  <div class="main">
    <h3>Please enter the data to update in the Database</h3>

    <div class="form">
      <form action="" method="post">
        <label for="product_id">Product ID: </label>
        <input type="text" id="product_id" name="product_id" value="<?php echo $product_id_get; ?>" readonly>

        <label for="category_id">Category ID: </label>
        <input type="text" id="category_id" name="category_id" value="<?php echo $category_id_get; ?>" readonly>

        <label for="product_name">Product Name: </label>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product_name_get; ?>">

        <label for="unit_price">Unit Price: </label>
        <input type="text" id="unit_price" name="unit_price" value="<?php echo $unit_price_get; ?>">

        <label for="qty_instock">Instock Quantity: </label>
        <input type="text" id="qty_instock" name="qty_instock" value="<?php echo $qty_instock_get; ?>">

        <label for="description">Description: </label>
        <textarea id="description" name="description" rows="8" cols="50"><?php echo $description_get; ?></textarea>

        <label for="image_url">Image: </label>
        <input type="text" id="image_url" name="image_url" value="<?php echo $image_url_get; ?>">
        <img src="http://<?php echo $image_url_get; ?>" alt="product image" class="center-img">

        <button type="submit" name="submit">Update data in Database</button>
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