<?php
session_start();

require_once('../config/db_connect.php');

// set active to current nav item class list
$blog_active = 'active';

if (isset($_POST["submit"])) {
  include_once 'control/tool_functions.php';

  // validate inputs
  $errors = [];

  if (!empty($_POST['title'])) {
    $title = $_POST['title'];
  } else {
    $title = NULL;
    $errors[] =  "<p class='error'>Blog Title is required.</p>";
  }

  if (!empty($_POST['author'])) {
    $author = $_POST['author'];
  } else {
    $author = NULL;
    $errors[] = "<p class='error'>Blog Author is required.</p>";
  }

  if (!empty($_POST['create_date'])) {
    $create_date = $_POST['create_date'];
  } else {
    $create_date = NULL;
    $errors[] = "<p class='error'>Date is required.</p>";
  }

  if (!empty($_POST['content'])) {
    $content = $_POST['content'];
  } else {
    $content = NULL;
    $errors[] = "<p class='error'>Blog Content is required.</p>";
  }

  if (!empty($_POST['image_url'])) {
    $image_url = $_POST['image_url'];
  } else {
    $image_url = NULL;
    $errors[] = "<p class='error'>Image URL is required.</p>";
  }

  if (count($errors) == 0) {
    $title_clean = prepare_string($dbc, $title);
    $author_clean = prepare_string($dbc, $author);
    $create_date = prepare_string($dbc, $create_date);
    $content_clean = prepare_string($dbc, $content);
    $image_url_clean = prepare_string($dbc, $image_url);

    $q = "INSERT INTO blogs(title, author, create_date, content, image_url) VALUES(?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'sssss',
      $title_clean,
      $author_clean,
      $create_date,
      $content_clean,
      $image_url_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // calculate the last page
      $last_page = last_page($dbc, 'blogs');

      header("Location:table_blog_view.php?page=$last_page");
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
  <title>Add Blog</title>
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

        <label for="title">Blog Title: </label>
        <input type="text" id="title" name="title">

        <label for="author">Blog Author: </label>
        <input type="text" id="author" name="author">

        <label for="create_date">Create Date: </label>
        <input type="text" id="create_date" name="create_date" value="<?php date_default_timezone_set('America/Toronto');
                                                                      $currentDate = date('Y-m-d H:i:s');
                                                                      echo $currentDate; ?>">

        <label for="content">Blog Content: </label>
        <input type="text" id="content" name="content">

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