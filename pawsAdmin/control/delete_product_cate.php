<?php
session_start();
require('tool_functions.php');
delete($dbc, 'category_id', 'categories', 'table_product_cate_view.php');
