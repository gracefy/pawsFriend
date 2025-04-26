<?php
session_start();

require_once('../config/db_connect.php');

// set active to current nav item class list
$product_cate_active = 'active';

if (isset($_POST["submit"])) {
  include 'control/tool_functions.php';

  // validate inputs
  $errors = [];

  if (!empty($_POST['category_name'])) {
    $category_name = $_POST['category_name'];
  } else {
    $category_name = NULL;
    $errors[] =  "<p class='error'>Category name is required.</p>";
  }


  if (!empty($_POST['description'])) {
    $description = $_POST['description'];
  } else {
    $description = NULL;
    $errors[] = "<p class='error'>A category description is required.</p>";
  }

  if (count($errors) == 0) {
    $category_name_clean = prepare_string($dbc, $category_name);
    $description_clean = prepare_string($dbc, $description);

    $q = "INSERT INTO categories(category_name, description) VALUES(?, ?)";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'ss',
      $category_name_clean,
      $description_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // calculate the last page
      $last_page = last_page($dbc, 'categories');

      header("Location:table_product_cate_view.php?page=$last_page");
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
  <title>Add Product Category</title>
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

        <label for="category_name">Category Name: </label>
        <input type="text" id="category_name" name="category_name">

        <label for="description">Description: </label>
        <input type="text" id="description" name="description">

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