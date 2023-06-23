<?php

include 'components/connect.php';

setcookie('admin_id', '', time() - 1, '/');

header('location:login.php');

?>