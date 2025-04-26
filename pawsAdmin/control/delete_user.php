<?php
session_start();
require('tool_functions.php');
delete($dbc, 'user_id', 'users', 'table_user_view.php');
