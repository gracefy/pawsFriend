<?php
session_start();

// set active to current nav item class list
$product_cate_active = 'active';

// get data from database
require('control/tool_functions.php');
$result = select_all($dbc, 'categories', 'category_id');

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $category_id = $row['category_id'];
  $category_name = $row['category_name'];
  $description = $row['description'];
}

// validate update inputs
$errors = [];
if (isset($_POST['submit'])) {
  // validate inputs
  $errors = [];

  if (isset($_POST['category_id']) && is_numeric($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
  } else {
    $category_id = NULL;
    $errors[] =  "<p class='error'>An exist Category ID is required.</p>";
  }

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
    $errors[] = "<p class='error'>A product description is required.</p>";
  }

  if (count($errors) == 0) {

    $category_name_clean = prepare_string($dbc, $category_name);
    $description_clean = prepare_string($dbc, $description);

    $q = "UPDATE categories SET category_name = ?, description = ? WHERE category_id = $category_id;";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'ss',
      $category_name_clean,
      $description_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // get current page
      $page = isset($_SESSION['page_info']['page']) ? $_SESSION['page_info']['page'] : 1;

      header("Location:table_product_cate_view.php?page=$page");
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
  <title>Edit Product Category</title>
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

        <label for="category_id">Category ID: </label>
        <input type="text" id="category_id" name="category_id" value="<?php echo $category_id; ?>" readonly>

        <label for="category_name">Category Name: </label>
        <input type="text" id="category_name" name="category_name" value="<?php echo $category_name; ?>">

        <label for="description">Description: </label>
        <textarea id="description" name="description" rows="8" cols="50"><?php echo $description; ?></textarea>

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