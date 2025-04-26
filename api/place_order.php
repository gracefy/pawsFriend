<?php
require('../utils/functions.php');
require_once '../payment/stripe-php-13.4.0/init.php';
require_once '../config/payment_info.php';


// set secret api key
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$input = file_get_contents("php://input");

// Decode the JSON input into an array
$data = json_decode($input, true);


if (isset($data['user_id']) && isset($data['cart_id']) && isset($data['selected_items'])) {
  $user_id = $data['user_id'];
  $cart_id = $data['cart_id'];
  $selected_items = $data['selected_items'];

  $result = is_insert_orders($dbc, $user_id);

  if ($result) {
    $order_id = mysqli_insert_id($dbc);
    if (is_array($selected_items)) {
      foreach ($selected_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        insert_order_items($dbc, $order_id, $product_id, $quantity);
        delete_checkout_items($dbc, $cart_id, $product_id);
      }
    } else {
      echo json_encode(array('success' => false, 'message' => "$selected_items is not an array"));
    }

    try {
      $line_items = [];
      foreach ($selected_items as $item) {
        $line_items[] = [
          'price_data' => [
            'currency' => $data['currency'],
            'product_data' => [
              'name' => $item['product_name'],
            ],
            'unit_amount' => $item['price'] * 100,
          ],
          'quantity' => $item['quantity'],
        ];
      }

      $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => "https://paws.graceye.ca/pawsfriend/success.php?o=$order_id",
        'cancel_url' => "https://paws.graceye.ca/pawsfriend/checkout.php?c=$cart_id",

      ]);

      echo json_encode(['success' => true, 'order_id' => $order_id, 'checkout_session' => $checkout_session, 'client_secret' => $checkout_session->client_secret]);
    } catch (Exception $e) {
      // Handle errors, log, and respond accordingly
      error_log('Error during checkout: ' . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Error during checkout', 'error' => $e->getMessage()]);
    }
  } else {
    echo json_encode(array('success' => false, 'message' => 'Error creating order'));
  }
} else {
  echo json_encode(array('success' => false, 'message' => 'Valid data posted'));
}
