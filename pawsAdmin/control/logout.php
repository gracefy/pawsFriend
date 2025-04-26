<?php
session_start();

unset($_SESSION['admin_userinfo']);

header("Location:login_view.php");
exit;
