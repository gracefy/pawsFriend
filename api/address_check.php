<?php
require_once('../utils/functions.php');
require_once('../pawsAdmin/control/tool_functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $user_id = isset($_GET['id']) ? $_GET['id'] : '';

  $result = get_address($dbc, $user_id);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $address = [
        'name' => $row['name'],
        'phone' => $row['phone'],
        'province' => $row['province'],
        'city' => $row['city'],
        'street' => $row['street'],
        'street_number' => $row['street_number'],
        'apartment' => $row['apartment'],
        'unit_number' => $row['unit_number'],
        'postal_code' => $row['postal_code'],
      ];
    }

    header('Content-Type: application/json');
    echo json_encode($address);
  } else {
    echo json_encode(['none' => 'no address']);
  }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = isset($_GET['id']) ? $_GET['id'] : '';

  // validate post data
  $errors = [];

  if (is_name_valid($_POST['name'])) {
    $name = $_POST['name'];
  } else {
    $name = NULL;
    $errors['name'] =  "Please enter a valid Name";
  }

  if (is_phone_valid($_POST['phone'])) {
    $phone = $_POST['phone'];
  } else {
    $phone = NULL;
    $errors['phone'] =  "Please enter a valid Phone Number";
  }

  if (!empty($_POST['street_number']) && is_numeric($_POST['street_number'])) {
    $street_number = $_POST['street_number'];
  } else {
    $street_number = NULL;
    $errors['street_number'] = "Please enter a valid Street number";
  }


  if (!empty($_POST['street'])) {
    $street = $_POST['street'];
  } else {
    $street = NULL;
    $errors['street'] = "Please enter a valid Street name";
  }

  if (!empty($_POST['city'])) {
    $city = $_POST['city'];
  } else {
    $city = NULL;
    $errors['city'] = "Please enter a valid city name";
  }

  if (isset($_POST['province'])) {
    $province = $_POST['province'];
  } else {
    $province = NULL;
    $errors['province'] = "Please select a valid Canada Province name";
  }

  if (is_postal_code_valid($_POST['postal_code'])) {
    $postal_code = $_POST['postal_code'];
  } else {
    $postal_code = NULL;
    $errors['postal_code'] =  "Please enter a valid Canada Postal Code";
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
    $data = [
      'name' => $name,
      'phone' => $phone,
      'street_number' => $street_number,
      'street' => $street,
      'city' => $city,
      'province' => $province,
      'postal_code' => $postal_code,
      'apartment' => $apartment,
      'unit_number' => $unit_number,
    ];


    $result = get_address($dbc, $user_id);
    if (mysqli_num_rows($result) > 0) {
      $update_result = update_address($dbc, $user_id, $data);
      if ($update_result) {
        $address_result = get_address($dbc, $user_id);
        $address = mysqli_fetch_assoc($address_result);
        header('Content-Type: application/json');
        echo json_encode($address);
      }
    } else {
      $result = insert_address($dbc, $user_id, $data);
      if ($result) {
        $address_result = get_address($dbc, $user_id);
        $address = mysqli_fetch_assoc($address_result);
        header('Content-Type: application/json');
        echo json_encode($address);
      } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode('error in insert address' . $user_id . $_POST['name']);
      }
    }
  } else {
    header('Content-Type: application/json');
    echo json_encode(['errors' => $errors]);
  }
} else {
  header('HTTP/1.1 400 Bad Request');
  echo 'Invalid Request';
}
