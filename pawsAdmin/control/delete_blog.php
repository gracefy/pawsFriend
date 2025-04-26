<?php
session_start();
require('tool_functions.php');
delete($dbc, 'blog_id', 'blogs', 'table_blog_view.php');
