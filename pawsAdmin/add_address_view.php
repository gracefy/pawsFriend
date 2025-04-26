<?php
session_start();

require_once('../config/db_connect.php');

// set active to current nav item class list
$address_active = 'active';

if (isset($_POST["submit"])) {
  include_once 'control/tool_functions.php';

  // validate inputs
  $errors = [];
  if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
    if (is_id_exist($dbc, $_POST['user_id'], 'users', 'user_id')) {
      $user_id = $_POST['user_id'];
    } else {
      $user_id = NULL;
      $errors[] =  "<p class='error'>User ID does not exist.</p>";
    }
  } else {
    $user_id = NULL;
    $errors[] =  "<p class='error'>An exist user ID is required.</p>";
  }

  if (isset($_POST['phone']) && is_phone_valid($_POST['phone'])) {
    $phone = $_POST['phone'];
  } else {
    $phone = NULL;
    $errors[] =  "<p class='error'>Phone number is required.</p>";
  }

  if (!empty($_POST['street_number'])) {
    $street_number = $_POST['street_number'];
  } else {
    $street_number = NULL;
    $errors[] = "<p class='error'>Street number is required.</p>";
  }


  if (!empty($_POST['street'])) {
    $street = $_POST['street'];
  } else {
    $street = NULL;
    $errors[] = "<p class='error'>Street name is required.</p>";
  }

  if (!empty($_POST['city'])) {
    $city = $_POST['city'];
  } else {
    $city = NULL;
    $errors[] = "<p class='error'>City name is required.</p>";
  }

  if (isset($_POST['province'])) {
    $province = $_POST['province'];
  } else {
    $province = NULL;
    $errors[] = "<p class='error'>Canada province name is required.</p>";
  }

  if (isset($_POST['apartment'])) {
    $apartment = $_POST['apartment'];
  } else {
    $apartment = '';
  }

  if (isset($_POST['unit_number'])) {
    $unit_number = $_POST['unit_number'];
  } else {
    $unit_number = '';
  }


  if (count($errors) == 0) {
    require('../utils/provinces.php');
    $province_code = get_province_code($province);

    $user_id_clean = prepare_string($dbc, $user_id);
    $phone_clean = prepare_string($dbc, $phone);
    $street_number_clean = prepare_string($dbc, $street_number);
    $street_clean = prepare_string($dbc, $street);
    $city_clean = prepare_string($dbc, $city);
    $province_code_clean = prepare_string($dbc, $province_code);
    $apartment_clean = prepare_string($dbc, $apartment);
    $unit_number = prepare_string($dbc, $unit_number);

    $q = "INSERT INTO addresses(user_id, phone, street_number, street, city, province, apartment, unit_number) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($dbc, $q);

    mysqli_stmt_bind_param(
      $stmt,
      'isssssss',
      $user_id_clean,
      $phone_clean,
      $street_number_clean,
      $street_clean,
      $city_clean,
      $province_code_clean,
      $apartment_clean,
      $unit_number
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {

      // calculate the last page
      $last_page = last_page($dbc, 'addresses');

      header("Location:table_user_address_view.php?page=$last_page");
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
  <title>Add User Address</title>
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

        <label for="user_id">User ID: </label>
        <?php
        include_once 'control/tool_functions.php';
        get_key_value($dbc, 'users', 'user_id');
        ?>

        <label for="phone">Phone: </label>
        <input type="text" id="phone" name="phone">

        <label for="street_number">Street Number: </label>
        <input type="text" id="street_number" name="street_number">

        <label for="street">Street: </label>
        <input type="text" id="street" name="street">

        <label for="city">City: </label>
        <input type="text" id="city" name="city">

        <label for="province">Province: </label>
        <select name="province">

          <?php
          require('../utils/provinces.php');

          $provinces = get_provinces();

          foreach ($provinces as $province) {
            echo "<option value='$province'> $province </option>";
          }
          ?>
        </select>

        <label for="apartment">Apartment: </label>
        <input type="text" id="apartment" name="apartment" value="optional">

        <label for="unit_number">Unit_number: </label>
        <input type="text" id="unit_number" name="unit_number" value="optional">

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