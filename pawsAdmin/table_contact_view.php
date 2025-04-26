<?php
session_start();

// set active to current nav item class list
$contact_active = 'active';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Table</title>
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

  <!-- create user table -->
  <div class="main">
    <h3>Contact Table</h3>
    <table>
      <thead>
        <tr>
          <th>Contact ID</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Message</th>
          <th>Create Date</th>
        </tr>
      </thead>
      <tbody>
        <?php

        // get data from database
        $query = 'SELECT contact_id, name, phone, email, content, create_date FROM contacts;';
        $result = @mysqli_query($dbc, $query);

        // declare a variable data as an array
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $data[] = $row;
        }

        // make pagination and display data in table in multi pages
        include 'control/pagination.php';

        for ($i = $start; $i < $end && $i < count($data); $i++) {
          $str = "<tr><td>{$data[$i]['contact_id']}</td>";
          $str .= "<td>{$data[$i]['name']}</td>";
          $str .= "<td>{$data[$i]['phone']}</td>";
          $str .= "<td>{$data[$i]['email']}</td>";
          $str .= "<td>{$data[$i]['content']}</td>";
          $str .= "<td>{$data[$i]['create_date']}</td></tr>";

          echo $str;
        }
        ?>
      </tbody>
    </table>

    <!-- display page numbers -->
    <?php
    echo "<div class = 'pages'>";
    echo pagination($current_page, $total_pages, 'table_contact_view.php');
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