<?php
session_start();

// set active to current nav item class list
$product_active = 'active';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products</title>
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
    <h3>Products Table</h3>
    <span class="add"><a href="add_product_view.php">Add <i class="fa-solid fa-plus"></i></a></span>
    <table>
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Category ID</th>
          <th>Product Name</th>
          <th>Unit Price</th>
          <th>Instock Quantity</th>
          <th>Image URL</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

        <?php
        // get data from database
        $query = 'SELECT product_id, category_id, product_name, unit_price, qty_instock, image_url FROM products ORDER BY create_date DESC;';
        $result = @mysqli_query($dbc, $query);

        // declare a variable data as an array
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $data[] = $row;
        }

        // make pagination and display data in table in multi pages
        include './control/pagination.php';

        for ($i = $start; $i < $end && $i < count($data); $i++) {
          $str = "<tr><td>{$data[$i]['product_id']}</td>";
          $str .= "<td>{$data[$i]['category_id']}</td>";
          $str .= "<td>{$data[$i]['product_name']}</td>";
          $str .= "<td>{$data[$i]['unit_price']}</td>";
          $str .= "<td>{$data[$i]['qty_instock']}</td>";
          $str .= "<td>{$data[$i]['image_url']}</td>";
          $str .= "<td><a href='./edit_product_view.php?product_id={$data[$i]['product_id']}'>Edit</a> | <a href='control/delete_product.php?product_id={$data[$i]['product_id']}'>Delete</a></td></tr>";

          echo $str;
        }
        ?>
      </tbody>
    </table>

    <!-- display page numbers -->
    <?php
    echo "<div class = 'pages'>";
    echo pagination($current_page, $total_pages, 'table_product_view.php');
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