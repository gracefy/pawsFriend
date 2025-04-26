<?php
session_start();

// set active to current nav item class list
$order_item_active = 'active';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Items</title>
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

  <!-- create table -->
  <div class="main">
    <h3>Order Items Table</h3>
    <span class="add"><a href="add_order_items_view.php">Add <i class="fa-solid fa-plus"></i></a></span>
    <table>
      <thead>
        <tr>
          <th>Order Item ID</th>
          <th>Order ID</th>
          <th>Product ID</th>
          <th>Quantity</th>
          <th>Discount</th>
        </tr>
      </thead>
      <tbody>
        <?php

        // get data from database
        $query = 'SELECT item_id, o.order_id, product_id, quantity, discount FROM order_items o LEFT JOIN orders ON o.order_id = orders.order_id ORDER BY orders.order_date DESC;';
        $result = @mysqli_query($dbc, $query);

        // declare a variable data as an array
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $data[] = $row;
        }

        // make pagination and display data in table in multi pages
        include './control/pagination.php';

        for ($i = $start; $i < $end && $i < count($data); $i++) {
          $str = "<tr><td>{$data[$i]['item_id']}</td>";
          $str .= "<td>{$data[$i]['order_id']}</td>";
          $str .= "<td>{$data[$i]['product_id']}</td>";
          $str .= "<td>{$data[$i]['quantity']}</td>";
          $str .= "<td>{$data[$i]['discount']}</td></tr>";

          echo $str;
        }
        ?>
      </tbody>
    </table>


    <!-- display page numbers -->
    <?php
    echo "<div class = 'pages'>";
    echo pagination($current_page, $total_pages, 'table_order_item_view.php');
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