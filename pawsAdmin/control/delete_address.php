<?php
session_start();
require('tool_functions.php');
delete($dbc, 'address_id', 'addresses', 'table_user_address_view.php');
