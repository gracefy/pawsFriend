<?php
session_start();

// set active to current nav item class list
$cart_item_active = 'active';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart Items</title>
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
    <h3>Cart Items Table</h3>
    <table>
      <thead>
        <tr>
          <th>Cart Item ID</th>
          <th>Cart ID</th>
          <th>Product ID</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php

        // get data from database
        $query = 'SELECT cart_item_id, cart_id, product_id, quantity FROM cart_items ORDER BY update_time DESC;';
        $result = @mysqli_query($dbc, $query);

        // declare a variable data as an array
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $data[] = $row;
        }

        // make pagination and display data in table in multi pages
        include './control/pagination.php';

        for ($i = $start; $i < $end && $i < count($data); $i++) {
          $str = "<tr><td>{$data[$i]['cart_item_id']}</td>";
          $str .= "<td>{$data[$i]['cart_id']}</td>";
          $str .= "<td>{$data[$i]['product_id']}</td>";
          $str .= "<td>{$data[$i]['quantity']}</td></tr>";

          echo $str;
        }
        ?>
      </tbody>
    </table>

    <!-- display page numbers -->
    <?php
    echo "<div class = 'pages'>";
    echo pagination($current_page, $total_pages, 'table_cart_item_view.php');
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