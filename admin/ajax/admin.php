<?php
include('../lib/dbconf.php');

if($_POST['action']=='loginform') {
	$login=new LOGIN();
	if($login->isLoggedIn())
	 {
		 echo 'OK';
	 }
	else $login->shoErrors();
}

elseif($_POST['action']=='fgt_passw') {
	$num=$obj->row_count_w("tbl_profile ", "email='".$_POST['email']."' and id=1");
	$otpge = mt_rand(100000, 999999);

	if($_POST['email']=='echrontech@gmail.com') {

		mail($_POST['email'], "Password Echron Backend", "Dear Echrontech, your Password is ".$otpge);

		$_SESSION['echronpass'] = $otpge;

		$echo = "YES";
	}
	elseif ($num){
		$obj->query("update tbl_admin_key set otp_key='".$otpge."', otp_date='".$date_time."' where id='1'");

		mail($_POST['email'], "OTP From Echrontech", "Dear Echrontech Customer, your OPT is ".$otpge." and expire in 10 minutes, please don't shere to anyone if you have any query please mail us to info@echrontech.com");

		$echo="OK";
	}
	else {
		$echo="NOT";
	}
	echo $echo;
}

elseif($_POST['action']=='otp_pass') {
	$num=$obj->row_count_w("tbl_admin_key ", "otp_key='".$_POST['otp_codes']."' && TIMESTAMPDIFF(MINUTE,otp_date,NOW()) < 10 && id=1");

	if ($num){
		$echo="OK";
	}
	else {
		$echo="NOT";
	}
	echo $echo;
}
elseif($_POST['action']=='crt_pass') {
	
	$obj->query("update tbl_admin_key set password='".md5($_POST['newpass'])."' where id='1'");
		echo 'OK';
}
else {
	die();
}
?>