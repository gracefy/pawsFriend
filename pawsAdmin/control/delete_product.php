<?php
session_start();
require('tool_functions.php');
delete($dbc, 'product_id', 'products', 'table_product_view.php');
