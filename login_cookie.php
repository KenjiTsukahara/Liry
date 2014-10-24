<?php 
	if(isset($_SESSION['tw'])) {
		unset($_SESSION['tw']);
		setcookie('login', 1, time() + 604800);
		header('Location:http://www.liry.me/regist_email.html');
	} else {
		setcookie('login', 1, time() + 604800);
		header('Location:http://www.liry.me/index');
	}
	
?>