<?php 
	session_start();
	$_SESSION = array(); 
	session_destroy();
	setcookie("login", "", time() - 604800);
	unset($_COOKIE["login"]);
	header('Location:http://www.liry.me/join/');
?>