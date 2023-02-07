<?php
include('dbconf.php');
mysql_query("update tbl_admin_key set logout_date='$date_time', token='0' where email='".$_SESSION['username']."'");
session_destroy();
header('Location:../index.php');
?>