<?php
session_start();
require('tool_functions.php');
delete($dbc, 'pet_id', 'pets', 'table_pet_view.php');
