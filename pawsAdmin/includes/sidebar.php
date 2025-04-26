<!-- sidebar start -->
<?php ob_start(); ?>

<div class="sidebar">
  <div class="logo">
    <a href="index.php"></a>
  </div>

  <ul class="nav" id="sidebar-nav">
    <li><a href="index.php" class="nav-item <?php echo $dashboard_active ?>">Dashboard</a></li>
    <li class="sub-menu">Users
      <a href="table_user_view.php" class="nav-item <?php echo $user_active ?>">- Users</a>
      <a href="table_user_address_view.php" class="nav-item <?php echo $address_active ?>">- User Addresses</a>
    </li>
    <li class="sub-menu">Products
      <a href="table_product_view.php" class="nav-item <?php echo $product_active ?>">- Products</a>
      <a href="table_product_cate_view.php" class="nav-item <?php echo $product_cate_active ?>">- Product Categories</a>
    </li>
    <li class="sub-menu">Pets
      <a href="table_pet_view.php" class="nav-item <?php echo $pet_active ?>">- Pets</a>
      <a href="table_pet_cate_view.php" class="nav-item <?php echo $pet_cate_active ?>">- Pet Categories</a>
    </li>
    <li class="sub-menu">Carts
      <a href="table_cart_view.php" class="nav-item <?php echo $cart_active ?>">- Carts</a>
      <a href="table_cart_item_view.php" class="nav-item <?php echo $cart_item_active ?>">- Cart Items</a>
    </li>
    <li class="sub-menu">Orders
      <a href="table_order_view.php" class="nav-item <?php echo $order_active ?>">- Orders</a>
      <a href="table_order_item_view.php" class="nav-item <?php echo $order_item_active ?>">- Order Items</a>
    </li>
    <li><a href="table_blog_view.php" class="nav-item <?php echo $blog_active ?>">Blogs</a></li>
    <li><a href="table_contact_view.php" class="nav-item <?php echo $contact_active ?>">Contacts</a></li>
  </ul>


  <div class="logout">
    <a href="logout.php">
      <button class="btn-green">Log Out</button>
    </a>
  </div>

</div>
<!-- sidebar end -->