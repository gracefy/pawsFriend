<?php
require_once('../config/db_connect.php');
require_once 'provinces.php';

// check email
function check_email($dbc, $email)
{
  $email_clean = prepare_string($dbc, $email);

  $q = "SELECT COUNT(name) AS found FROM users WHERE email = ?;";

  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param(
    $stmt,
    's',
    $email_clean
  );

  mysqli_stmt_execute($stmt);

  mysqli_stmt_bind_result($stmt, $found);
  mysqli_stmt_fetch($stmt);

  return $found;
}


// fetch products data by category
function fetch_product_category($dbc, $label, $start, $limit)
{
  switch ($label) {
    case "":
    case "all":
      $q = "SELECT product_id, product_name, unit_price, image_url
        FROM products
        ORDER BY create_date DESC
        LIMIT $start, $limit;";
      break;
    case "food":
      $q = "SELECT product_id,product_name, unit_price, image_url
      FROM products
      WHERE category_id = 1
      ORDER BY create_date
      LIMIT $start, $limit;";
      break;
    case "accessory":
      $q = "SELECT product_id,product_name, unit_price, image_url
      FROM products
      WHERE category_id = 2
      ORDER BY create_date
      LIMIT $start, $limit;";
      break;
    default:
      die('Some error in connect, please try again.');
  }

  $result = mysqli_query($dbc, $q);
  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'product_id' => $row['product_id'],
      'name' => $row['product_name'],
      'price' => $row['unit_price'],
      'image_url' => $row['image_url']
    );
  }
  return $data;
}

// fetch products data by different sort
function fetch_product_sort($dbc, $option, $start, $limit)
{
  switch ($option) {
    case "price_asc":
      $q = "SELECT product_id,product_name, unit_price, image_url
      FROM products
      ORDER BY unit_price ASC
      LIMIT $start, $limit;";
      break;

    case "price_desc":
      $q = "SELECT product_id,product_name, unit_price, image_url
      FROM products
      ORDER BY unit_price DESC
      LIMIT $start, $limit;";
      break;

    case "topsales":
      $q = "SELECT p.product_id, p.product_name, p.unit_price, p.image_url, SUM(o.quantity) AS total_qty
        FROM products p
        LEFT JOIN order_items o
        ON p.product_id = o.product_id
        GROUP BY p.product_id
        ORDER BY total_qty DESC
        LIMIT $start, $limit;";
      break;

    case "deals":
      $q = "SELECT p.product_id, p.product_name, p.unit_price, p.image_url, o.discount
       FROM products p
       JOIN order_items o
       ON p.product_id = o.product_id
       ORDER BY o.discount ASC
       LIMIT $start, $limit;";
      break;

    default:
      $q = "SELECT product_id, product_name, unit_price, image_url
       FROM products
       ORDER BY create_date DESC
       LIMIT $start, $limit;";
      break;
  }

  $result = mysqli_query($dbc, $q);
  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  $data = [];
  if ($option == 'deals') {
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = array(
        'product_id' => $row['product_id'],
        'name' => $row['product_name'],
        'price' => $row['unit_price'],
        'image_url' => $row['image_url'],
        'discount' => $row['discount']
      );
    }
    return $data;
  } else {
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = array(
        'product_id' => $row['product_id'],
        'name' => $row['product_name'],
        'price' => $row['unit_price'],
        'image_url' => $row['image_url']
      );
    }
    return $data;
  }
}

// fetch products data by search key words
function fetch_product_bysearch($dbc, $key_words, $start, $limit)
{
  $input = '%' . $key_words . '%';

  $q = "SELECT product_id, product_name, unit_price, image_url
  FROM products
  WHERE product_name LIKE ?
  ORDER BY create_date DESC
  LIMIT $start, $limit;";

  $key_words_clean = prepare_string($dbc, $input);
  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param($stmt, 's', $key_words_clean);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'product_id' => $row['product_id'],
      'name' => $row['product_name'],
      'price' => $row['unit_price'],
      'image_url' => $row['image_url']
    );
  }
  return $data;
}

// fetch product data by id
function fetch_product_byID($dbc, $id)
{
  $q = "SELECT product_id, product_name, unit_price, description, image_url
  FROM products
  WHERE product_id = ?;";

  $id_clean = prepare_string($dbc, $id);

  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param($stmt, 'i', $id_clean);

  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  $row = mysqli_fetch_assoc($result);

  if (!$row) {
    return 'No matched data found';
  }

  $data = array(
    'product_id' => $row['product_id'],
    'name' => $row['product_name'],
    'price' => $row['unit_price'],
    'description' => $row['description'],
    'image_url' => $row['image_url']
  );

  return $data;
}

// fetch product data for cart items
function fetch_cart_item($dbc, $cart_id)
{
  $q = "SELECT p.product_id, product_name, unit_price, image_url, c.quantity
  FROM products p
  JOIN cart_items c ON c.product_id = p.product_id
  WHERE cart_id = $cart_id
  ORDER BY c.update_time DESC;";

  $result = mysqli_query($dbc, $q);

  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'product_id' => $row['product_id'],
      'name' => $row['product_name'],
      'price' => $row['unit_price'],
      'image_url' => $row['image_url'],
      'quantity' => $row['quantity']
    );
  };

  if (empty($data)) {
    return false;
  }

  return $data;
}


// fetch pet-category data
function fetch_pet_category($dbc)
{
  $query = "SELECT category_name,image_url FROM pet_category ORDER BY category_id;";
  $result = mysqli_query($dbc, $query);

  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }
  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'category_name' => $row['category_name'],
      'image_url' => $row['image_url']
    );
  }
  return $data;
}


// fetch pets data
function fetch_pet($dbc, $filter, $start, $limit)
{
  switch ($filter) {
    case "cats":
      $q = "SELECT pet_id, pet_name, unit_price, image_url
        FROM pets
        WHERE category_id = 1
        ORDER BY create_date DESC
        LIMIT $start, $limit;";
      break;
    case "dogs":
      $q = "SELECT pet_id, pet_name, unit_price, image_url
          FROM pets
          WHERE category_id = 2
          ORDER BY create_date DESC
          LIMIT $start, $limit;";
      break;
    case "others":
      $q = "SELECT pet_id, pet_name, unit_price, image_url
            FROM pets
            WHERE category_id = 3
            ORDER BY create_date DESC
            LIMIT $start, $limit;";
      break;
    default:
      $q = "SELECT pet_id, pet_name, unit_price, image_url
            FROM pets
            ORDER BY create_date DESC
            LIMIT $start, $limit;";
  }

  $result = mysqli_query($dbc, $q);
  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'id' => $row['pet_id'],
      'name' => $row['pet_name'],
      'price' => $row['unit_price'],
      'image_url' => $row['image_url']
    );
  }

  return $data;
}


// fetch blogs data
function fetch_blogs($dbc, $start, $limit)
{
  $q = "SELECT blog_id, title, author, create_date, image_url, content
    FROM blogs
    ORDER BY create_date DESC
    LIMIT  $start, $limit;";
  $result = mysqli_query($dbc, $q);

  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  // fetch data from the result set

  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'blog_id' => $row['blog_id'],
      'title' => $row['title'],
      'author' => $row['author'],
      'create_date' => $row['create_date'],
      'image_url' => $row['image_url'],
      'content' => $row['content'],
    );
  }

  return $data;
}


// fetch blogs data by search
function fetch_blogs_bysearch($dbc, $filter, $start, $limit)
{
  if ($filter == '' || $filter == 'all') {
    $q = "SELECT blog_id, title, author, create_date, image_url, content
  FROM blogs
  ORDER BY create_date DESC
  LIMIT $start, $limit;";
    $result = mysqli_query($dbc, $q);
  } elseif ($filter == 'others') {
    $q = "SELECT blog_id, title, author, create_date, image_url, content
  FROM blogs
  WHERE title NOT LIKE '%cat%' AND title NOT LIKE '%dog%'
  ORDER BY create_date DESC
  LIMIT $start, $limit;";
    $result = mysqli_query($dbc, $q);
  } else {
    $q = "SELECT blog_id, title, author, create_date, image_url, content
  FROM blogs
  WHERE title LIKE ?
  ORDER BY create_date DESC
  LIMIT $start, $limit;";
    $input = '%' . $filter . '%';
    $key_words_clean = prepare_string($dbc, $input);
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 's', $key_words_clean);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
  }

  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'blog_id' => $row['blog_id'],
      'title' => $row['title'],
      'author' => $row['author'],
      'create_date' => $row['create_date'],
      'image_url' => $row['image_url'],
      'content' => $row['content'],
    );
  }

  return $data;
}


// make pagination
function pagination($current_page, $total_pages, $page_name)
{
  $page_link = '';
  for ($page = 1; $page <= $total_pages; $page++) {
    $page_active = ($page == $current_page) ? 'page-active' : '';
    $page_link .= "<a class='$page_active' href='./$page_name?page=$page'> $page </a>";
  }

  return $page_link;
}


// get total rows
function total_rows($dbc, $table, $field)
{
  $query = "SELECT COUNT($field) AS total FROM $table";
  $result = mysqli_query($dbc, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
  } else {
    throw new Exception('Error counting total records');
  }
}


// get total rows of products table with different category
function total_rows_products($dbc, $label)
{
  switch ($label) {
    case "":
    case "all":
      $query = "SELECT COUNT(product_id) AS total FROM products;";
      break;
    case "food":
      $query = "SELECT COUNT(product_id) AS total FROM products WHERE category_id = 1;";
      break;
    case "accessory":
      $query = "SELECT COUNT(product_id) AS total FROM products WHERE category_id = 2;";
      break;
    default:
      die('Some error in connect, please try again.');
  }

  $result = mysqli_query($dbc, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
  } else {
    throw new Exception('Error counting total records');
  }
}


// get total rows of specific search
function total_rows_bysearch($dbc, $table, $name, $key_words)
{
  $input = '%' . $key_words . '%';

  $q = "SELECT COUNT($name) AS total FROM $table WHERE $name LIKE ?;";

  $key_words_clean = prepare_string($dbc, $input);
  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param($stmt, 's', $key_words_clean);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_rows = $row['total'];
    return $total_rows;
  } else {
    throw new Exception('Error counting total records');
    return 0;
  }
}

// get total rows of pets table
function total_rows_pets($dbc, $filter)
{
  switch ($filter) {
    case "":
    case "all":
      $query = "SELECT COUNT(pet_id) AS total FROM pets;";
      break;
    case "cats":
      $query = "SELECT COUNT(pet_id) AS total FROM pets WHERE category_id = 1;";
      break;
    case "dogs":
      $query = "SELECT COUNT(pet_id) AS total FROM pets WHERE category_id = 2;";
      break;
    case "others":
      $query = "SELECT COUNT(pet_id) AS total FROM pets WHERE category_id = 3;";
      break;
    default:
      die('Some error in connect, please try again.');
  }

  $result = mysqli_query($dbc, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
  } else {
    throw new Exception('Error counting total records');
  }
}

// create shop cart
function getCartID($dbc, $user_id)
{
  $q = "SELECT cart_id FROM cart WHERE user_id = $user_id;";

  $result = mysqli_query($dbc, $q);

  if ($result) {
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      return $row['cart_id'];
    } else {
      $q = "INSERT INTO cart (user_id) VALUES ($user_id);";
      $insert_result = mysqli_query($dbc, $q);

      if ($insert_result) {
        return mysqli_insert_id($dbc);
      } else {
        return "failure to create a new cart, please try again";
      }
    }
  } else {
    return "failure to query in database, please try again";
  }
}



// update product quantity
function update_product_qty($dbc, $cart_id, $product_id, $quantity)
{
  $q = "UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?;";

  $quantity_clean = prepare_string($dbc, $quantity);
  $cart_id_clean = prepare_string($dbc, $cart_id);
  $product_id_clean = prepare_string($dbc, $product_id);


  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param(
    $stmt,
    'iii',
    $quantity_clean,
    $cart_id_clean,
    $product_id_clean
  );

  $result = mysqli_stmt_execute($stmt);

  return $result;
}

// get all cart items quantity
function get_cart_qty($dbc, $cart_id)
{
  $q = "SELECT SUM(quantity) AS total_qty FROM cart_items WHERE cart_id = ?;";
  $cart_id_clean = prepare_string($dbc, $cart_id);

  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param($stmt, 'i', $cart_id_clean);

  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  $data = mysqli_fetch_assoc($result);
  return $data['total_qty'] ?? 0;
}




// detele product
function delete_product($dbc, $cart_id, $product_id)
{

  $q = "DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?;";
  $cart_id_clean = prepare_string($dbc, $cart_id);
  $product_id_clean = prepare_string($dbc, $product_id);

  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param(
    $stmt,
    'ii',
    $cart_id_clean,
    $product_id_clean,
  );

  $result = mysqli_stmt_execute($stmt);

  return $result;
}

// insert orders table
function is_insert_orders($dbc, $user_id)
{
  $q = "INSERT INTO orders(user_id) VALUES (?);";
  $user_id_clean = prepare_string($dbc, $user_id);
  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param($stmt, 'i', $user_id_clean);

  $result = mysqli_stmt_execute($stmt);

  if (!$result) {
    echo 'Error: ' . mysqli_stmt_error($stmt);
  }

  return $result;
}

// insert order_items table
function insert_order_items($dbc, $order_id, $product_id, $quantity)
{
  $q = "INSERT INTO order_items(order_id, product_id, quantity) VALUES (?, ?, ?);";
  $order_id_clean = prepare_string($dbc, $order_id);
  $product_id_clean = prepare_string($dbc, $product_id);
  $quantity_clean = prepare_string($dbc, $quantity);

  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param(
    $stmt,
    'iii',
    $order_id_clean,
    $product_id_clean,
    $quantity_clean
  );


  $result = mysqli_stmt_execute($stmt);

  if (!$result) {
    echo 'Error: ' . mysqli_stmt_error($stmt);
  }

  return $result;
}


// get order id
function get_order_id($dbc, $user_id)
{
  $q = "SELECT o.order_id
  FROM orders o
  JOIN order_items oi ON o.order_id = oi.order_id
  Where user_id = $user_id
  GROUP BY oi.order_id
  ORDER BY o.order_id DESC;";

  $result = mysqli_query($dbc, $q);

  if (!$result) {
    die("error:" . mysqli_error($dbc));
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row['order_id'];
  }

  mysqli_free_result($result);

  return $data;
}


// get order_items data
function get_order_items($dbc, $order_id)
{
  $q = "SELECT
  o.order_id, o.order_date, oi.quantity, oi.discount, oi.product_id, p.product_name, p.unit_price, p.image_url, p.unit_price * oi.quantity AS sub_total
  FROM orders o
  JOIN order_items oi ON o.order_id = oi.order_id
  JOIN products p ON oi.product_id = p.product_id
  WHERE o.order_id = $order_id
  ORDER BY o.order_date DESC;";

  $result = mysqli_query($dbc, $q);

  if (!$result) {
    die('Error in query: ' . mysqli_error($dbc));
  }

  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
      'order_id' => $row['order_id'],
      'order_date' => $row['order_date'],
      'product_id' => $row['product_id'],
      'product_name' => $row['product_name'],
      'unit_price' => $row['unit_price'],
      'quantity' => $row['quantity'],
      'discount' => $row['discount'],
      'image_url' => $row['image_url'],
      'sub_total' => $row['sub_total'],
    );
  }

  return $data;
}

// insert address
function insert_address($dbc, $user_id, $data)
{
  $name = $data['name'];
  $phone = $data['phone'];
  $street = $data['street'];
  $street_number = $data['street_number'];
  $apartment = isset($data['apartment']) ? $data['apartment'] : '';
  $unit_number = isset($data['unit_number']) ? $data['unit_number'] : '';
  $city = $data['city'];
  $province_code = get_province_code($data['province']);
  $postal_code = $data['postal_code'];

  $q = "INSERT INTO  addresses(user_id, name, phone, street, street_number, apartment, unit_number, city, province, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $user_id_clean = prepare_string($dbc, $user_id);
  $name_clean = prepare_string($dbc, $name);
  $phone_clean = prepare_string($dbc, $phone);
  $street_clean = prepare_string($dbc, $street);
  $street_number_clean = prepare_string($dbc, $street_number);
  $apartment_clean = prepare_string($dbc, $apartment);
  $unit_number_clean = prepare_string($dbc, $unit_number);
  $city_clean = prepare_string($dbc, $city);
  $province_code_clean = prepare_string($dbc, $province_code);
  $postal_code_clean = prepare_string($dbc, $postal_code);

  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param(
    $stmt,
    'isssssssss',
    $user_id_clean,
    $name_clean,
    $phone_clean,
    $street_clean,
    $street_number_clean,
    $apartment_clean,
    $unit_number_clean,
    $city_clean,
    $province_code_clean,
    $postal_code_clean
  );
  $result = mysqli_stmt_execute($stmt);

  if (!$result) {
    echo 'Error: ' . mysqli_stmt_error($stmt);
  }

  return $result;
}



function update_address($dbc, $user_id, $data)
{
  $name = $data['name'];
  $phone = $data['phone'];
  $street = $data['street'];
  $street_number = $data['street_number'];
  $apartment = isset($data['apartment']) ? $data['apartment'] : '';
  $unit_number = isset($data['unit_number']) ? $data['unit_number'] : '';
  $city = $data['city'];
  $province_code = get_province_code($data['province']);
  $postal_code = $data['postal_code'];

  $q = "UPDATE  addresses
  SET name = ?, phone = ?, street= ?, street_number=?, apartment=?, unit_number=?, city=?, province=?, postal_code=? WHERE user_id = ?;";
  $user_id_clean = prepare_string($dbc, $user_id);
  $name_clean = prepare_string($dbc, $name);
  $phone_clean = prepare_string($dbc, $phone);
  $street_clean = prepare_string($dbc, $street);
  $street_number_clean = prepare_string($dbc, $street_number);
  $apartment_clean = prepare_string($dbc, $apartment);
  $unit_number_clean = prepare_string($dbc, $unit_number);
  $city_clean = prepare_string($dbc, $city);
  $province_code_clean = prepare_string($dbc, $province_code);
  $postal_code_clean = prepare_string($dbc, $postal_code);

  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param(
    $stmt,
    'sssssssssi',
    $name_clean,
    $phone_clean,
    $street_clean,
    $street_number_clean,
    $apartment_clean,
    $unit_number_clean,
    $city_clean,
    $province_code_clean,
    $postal_code_clean,
    $user_id_clean,
  );
  $result = mysqli_stmt_execute($stmt);

  if (!$result) {
    echo 'Error: ' . mysqli_stmt_error($stmt);
  }

  return $result;
}



// get address by user ID
function get_address($dbc, $user_id)
{
  $q = "SELECT name, phone, province, city, street, street_number, apartment, unit_number, postal_code FROM addresses WHERE user_id = ?";
  $user_id_clean = prepare_string($dbc, $user_id);

  $stmt = mysqli_prepare($dbc, $q);
  mysqli_stmt_bind_param($stmt, 'i', $user_id_clean);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  return $result;
}


// delete items after checkout

function delete_checkout_items($dbc, $cart_id, $product_id)
{
  $q = "DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?";
  $cart_id_clean = prepare_string($dbc, $cart_id);
  $product_id_clean = prepare_string($dbc, $product_id);

  $stmt = mysqli_prepare($dbc, $q);

  mysqli_stmt_bind_param(
    $stmt,
    'ii',
    $cart_id_clean,
    $product_id_clean
  );

  $result = mysqli_stmt_execute($stmt);

  if (!$result) {
    echo 'Error: ' . mysqli_stmt_error($stmt);
  }

  return $result;
}
