<?php
session_start();

unset($_SESSION['userinfo']);
unset($_SESSION['product']);

header("Location:../pawsfriend/index.php");
exit;
