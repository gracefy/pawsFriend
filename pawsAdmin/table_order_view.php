<?php
session_start();

// set active to current nav item class list
$order_active = 'active';


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders</title>
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
    <h3>Orders Table</h3>
    <span class="add"><a href="add_orders_view.php">Add <i class="fa-solid fa-plus"></i></a></span>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>User ID</th>
          <th>Order Date</th>
          <!-- <th>Action</th> -->
        </tr>
      </thead>
      <tbody>
        <?php

        // get data from database
        $query = 'SELECT order_id, user_id, order_status, order_date FROM orders ORDER BY order_date DESC;';
        $result = @mysqli_query($dbc, $query);

        // declare a variable data as an array
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $data[] = $row;
        }

        // make pagination and display data in table in multi pages
        include './control/pagination.php';

        for ($i = $start; $i < $end && $i < count($data); $i++) {
          $str = "<tr><td class='order_id'>{$data[$i]['order_id']}</td>";
          $str .= "<td>{$data[$i]['user_id']}</td>";
          $str .= "<td>{$data[$i]['order_date']}</td></tr>";
          //   $str .= "<td><select name='order-action'>
          // <option value='pending'>Pending</option>
          // <option value='delivered'>Delivered</option>
          // <option value='processing'>Processing</option>
          // <option value='canceled'>Canceled</option>
          // </select></td></tr>";

          echo $str;
        }
        ?>
      </tbody>
    </table>

    <!-- display page numbers -->
    <?php
    echo "<div class = 'pages'>";
    echo pagination($current_page, $total_pages, 'table_order_view.php');
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
  <script src="assets/js/order_status.js"></script>
</body>

</html>