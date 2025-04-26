<?php
session_start();

// set active to current nav item class list
$blog_active = 'active';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blogs</title>
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

  <!-- create address table -->
  <div class="main">
    <h3>Blogs Table</h3>
    <span class="add"><a href="add_blog_view.php">Add <i class="fa-solid fa-plus"></i></a></span>
    <table>
      <thead>
        <tr>
          <th>Blog ID</th>
          <th>Title</th>
          <th>Author</th>
          <th>Create Date</th>
          <th>Update Date</th>
          <th>Image URL</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

        <?php
        // get data from database
        $query = 'SELECT blog_id, title, author, create_date, update_time, image_url FROM blogs;';
        $result = @mysqli_query($dbc, $query);

        // declare a variable data as an array
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $data[] = $row;
        }

        // make pagination and display data in table in multi pages
        include './control/pagination.php';

        for ($i = $start; $i < $end && $i < count($data); $i++) {
          $str = "<tr><td>{$data[$i]['blog_id']}</td>";
          $str .= "<td>{$data[$i]['title']}</td>";
          $str .= "<td>{$data[$i]['author']}</td>";
          $str .= "<td>{$data[$i]['create_date']}</td>";
          $str .= "<td>{$data[$i]['update_time']}</td>";
          $str .= "<td>{$data[$i]['image_url']}</td>";
          $str .= "<td><a href='./edit_blog_view.php?blog_id={$data[$i]['blog_id']}'>Edit</a> | <a href='control/delete_blog.php?blog_id={$data[$i]['blog_id']}'>Delete</a></td></tr>";

          echo $str;
        }
        ?>
      </tbody>
    </table>

    <!-- display page numbers -->
    <?php
    echo "<div class = 'pages'>";
    echo pagination($current_page, $total_pages, 'table_blog_view.php');
    echo "</div>";


    // store page number in a session variable
    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $_SESSION['page_info'] = [
      'page' => $page
    ];


    ?>

  </div>

  <!-- include footer section -->
  <?php include 'includes/footer.php' ?>

</body>

</html>