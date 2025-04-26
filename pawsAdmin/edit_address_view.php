<?php
session_start();

// set active to current nav item class list
$address_active = 'active';

// get operating data from database
require_once('control/tool_functions.php');
$result = select_all($dbc, 'addresses', 'address_id');

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $address_id_get = $row['address_id'];
  $user_id_get = $row['user_id'];
  $phone_get = $row['phone'];
  $street_number_get = $row['street_number'];
  $street_get = $row['street'];
  $city_get = $row['city'];
  $province_get = $row['province'];
  $apartment_get = $row['apartment'];
  $unit_number_get = $row['unit_number'];
}

// validate update inputs
$errors = [];
if (isset($_POST['submit'])) {

  if (isset($_POST['address_id'])) {
    $address_id = $_POST['address_id'];
  } else {
    $address_id = NULL;
    $errors[] = "<p>Address ID NOT Found.</p>";
  }

  if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
  } else {
    $user_id = NULL;
    $errors[] = "<p>User ID NOT Found.</p>";
  }

  if (is_phone_valid($_POST['phone'])) {
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


  if (!empty($_POST['province'])) {
    $province = $_POST['province'];
  } else {
    $province = NULL;
    $errors[] = "<p class='error'>Province name is required.</p>";
  }

  if (!empty($_POST['apartment'])) {
    $apartment = $_POST['apartment'];
  } else {
    $apartment = '';
  }

  if (!empty($_POST['unit_number'])) {
    $unit_number = $_POST['unit_number'];
  } else {
    $unit_number = '';
  }

  if (count($errors) == 0) {
    require('../utils/provinces.php');
    $province_code = get_province_code($province);


    $page = isset($_SESSION['page_info']['page']) ? $_SESSION['page_info']['page'] : 1;

    $address_id_clean = prepare_string($dbc, $address_id);
    $user_id_clean = prepare_string($dbc, $user_id);
    $phone_clean = prepare_string($dbc, $phone);
    $street_number_clean = prepare_string($dbc, $street_number);
    $street_clean = prepare_string($dbc, $street);
    $city_clean = prepare_string($dbc, $city);
    $province_code_clean = prepare_string($dbc, $province_code);
    $apartment_clean = prepare_string($dbc, $apartment);
    $unit_number_clean = prepare_string($dbc, $unit_number);

    $query = "UPDATE addresses SET phone = ?, street_number = ?, street = ?, city = ?, province = ?, apartment = ?, unit_number = ? WHERE address_id = $address_id_clean;";

    $stmt = mysqli_prepare($dbc, $query);

    mysqli_stmt_bind_param(
      $stmt,
      'sssssss',
      $phone_clean,
      $street_number_clean,
      $street_clean,
      $city_clean,
      $province_code_clean,
      $apartment_clean,
      $unit_number_clean
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      header("Location:table_user_address_view.php?page=$page");
      exit;
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
  <title>Edit Address</title>
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
        <label for="address_id">Address ID: </label>
        <input type="text" id="address_id" name="address_id" value="<?php echo $address_id_get; ?>" readonly>

        <label for="user_id">User ID: </label>
        <input type="text" id="user_id" name="user_id" value="<?php echo $user_id_get; ?>" readonly>

        <label for="phone">Phone: </label>
        <input type="text" id="phone" name="phone" value="<?php echo $phone_get; ?>">

        <label for="street_number">Street Number: </label>
        <input type="text" id="street_number" name="street_number" value="<?php echo $street_number_get; ?>">

        <label for="street">Street: </label>
        <input type="text" id="street" name="street" value="<?php echo $street_get; ?>">

        <label for="city">City: </label>
        <input type="text" id="city" name="city" value="<?php echo $city_get; ?>">

        <label for="province">Province: </label>
        <select name="province">

          <?php
          require('../utils/provinces.php');

          $provinces = get_provinces();


          foreach ($provinces as $province) {
            if ($province == get_province($province_get)) {
              echo "<option selected='selected' value='$province'> $province </option>";
            } else {
              echo "<option value='$province'> $province </option>";
            }
          }
          ?>
        </select>

        <label for="apartment">Apartment: </label>
        <input type="text" id="apartment" name="apartment" value="<?php echo $apartment_get; ?>">

        <label for="unit_number">Unit_number: </label>
        <input type="text" id="unit_number" name="unit_number" value="<?php echo $unit_number_get; ?>">

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