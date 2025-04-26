<?php
session_start();
require('tool_functions.php');
delete($dbc, 'category_id', 'pet_category', 'table_pet_cate_view.php');
