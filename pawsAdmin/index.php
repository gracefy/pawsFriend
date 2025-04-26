<?php
session_start();

// set active to current nav item class list
$dashboard_active = 'active';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
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
    <!-- dashboard overview start -->
    <h4>Dashboard Overview</h4>
    <div class="overview">
      <div class="amount orange-bgc">
        <span>Today Orders</span>
        <h5 id="today_price"></h5>
      </div>
      <div class="amount green-bgc">
        <span>Yesterday Orders</span>
        <h5 id="yesterday_price"></h5>
      </div>
      <div class="amount blue-bgc">
        <span>This Month</span>
        <h5 id="month_price"></h5>
      </div>
      <div class="amount cyan-bgc">
        <span>This Year</span>
        <h5 id="year_price"></h5>
      </div>
    </div>
    <!-- <div class="overview">
      <div class="orders t-order">
        <span>Total Orders</span>
        <h5>200</h5>
      </div>
      <div class="orders p-order">
        <span>Orders Processing</span>
        <h5>25</h5>
      </div>
      <div class="orders d-order">
        <span>Orders Delivered</span>
        <h5>160</h5>
      </div>
    </div> -->
    <!-- dashboard overview end -->

    <!-- recent table start -->
    <div class="recent-table">
      <h4>Recent Orders</h4>
      <div class="order-table">
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Order Date</th>
              <th> User Name</th>
              <th>Order Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once('../config/db_connect.php');
            $sql = "SELECT o.order_id, o.order_date, u.name, SUM(p.unit_price * oi.quantity) AS order_price
                  FROM orders o
                  JOIN users u ON o.user_id = u.user_id
                  JOIN order_items oi ON o.order_id = oi.order_id
                  JOIN products p ON oi.product_id = p.product_id
                  GROUP BY o.order_id, o.order_date, u.name
                  ORDER BY o.order_date DESC
                  LIMIT 10;";

            $result = mysqli_query($dbc, $sql);
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              $data[] = $row;

              echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['order_date']}</td>
                <td>{$row['name']}</td>
                <td>{$row['order_price']}</td>
            </tr>";
            }


            ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- recent table end -->
  </div>

  <!-- include footer section -->
  <?php include 'includes/footer.php' ?>
  <script src="./assets/js/update_dashboard.js"></script>
</body>

</html>