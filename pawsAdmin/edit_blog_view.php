<?php
session_start();

// set active to current nav item class list
$blog_active = 'active';

// get data from database
require('control/tool_functions.php');
$result = select_all($dbc, 'blogs', 'blog_id');

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

  $blog_id_get = $row['blog_id'];
  $title_get = $row['title'];
  $author_get = $row['author'];
  $create_date_get = $row['create_date'];
  $update_time_get = $row['update_time'];
  $content_get = $row['content'];
  $image_url_get = $row['image_url'];
}

// validate update inputs
$errors = [];
if (isset($_POST['submit'])) {
  // validate inputs
  $errors = [];
  if (isset($_POST['blog_id'])) {
    $blog_id = $_POST['blog_id'];
  } else {
    $blog_id = NULL;
    $errors[] =  "<p class='error'>An exist Blog ID is required.</p>";
  }

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

  if (!empty($_POST['update_time'])) {
    $update_time = $_POST['update_time'];
  } else {
    $update_time = NULL;
    $errors[] = "<p class='error'>Update Time is required.</p>";
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
    $content_clean = prepare_string($dbc, $content);
    $create_date_clean = prepare_string($dbc, $create_date);
    $update_time_clean = prepare_string($dbc, $update_time);
    $image_url_clean = prepare_string($dbc, $image_url);

    $q = "UPDATE blogs SET title = ?, author = ?, content = ?, create_date = ?, update_time = ?, image_url = ? WHERE blog_id = $blog_id;";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'ssssss',
      $title_clean,
      $author_clean,
      $content_clean,
      $create_date_clean,
      $update_time_clean,
      $image_url_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // get current page
      $page = isset($_SESSION['page_info']['page']) ? $_SESSION['page_info']['page'] : 1;

      header("Location:table_blog_view.php?page=$page");
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
  <title>Edit Blog</title>
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
        <label for="blog_id">Blog ID: </label>
        <input type="text" id="blog_id" name="blog_id" value="<?php echo $blog_id_get; ?>" readonly>

        <label for="title">Blog Title: </label>
        <input type="text" id="title" name="title" value="<?php echo $title_get; ?>">

        <label for="author">Blog Author: </label>
        <input type="text" id="author" name="author" value="<?php echo $author_get; ?>">

        <label for="create_date">Create Date: </label>
        <input type="text" id="create_date" name="create_date" value="<?php echo $create_date_get; ?>" readonly>

        <label for="update_time">Update Time: </label>
        <input type="text" id="update_time" name="update_time" value="<?php date_default_timezone_set('America/Toronto');
                                                                      $currentDate = date('Y-m-d H:i:s');
                                                                      echo $currentDate; ?>">

        <label for="content">Content: </label>
        <textarea id="content" name="content" rows="10" cols="50"><?php echo $content_get; ?></textarea>

        <label for="image_url">Image: </label>
        <input type="text" id="image_url" name="image_url" value="<?php echo $image_url_get; ?>">
        <img src="http://<?php echo $image_url_get; ?>" alt="blog image" class="center-img">

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