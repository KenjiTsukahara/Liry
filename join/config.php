<?php
//twitter
	define('DSN', '');
	define('DB_USER', '');
	define('DB_PASSWORD', '');
	define('CONSUMER_KEY', '');
	define('CONSUMER_SECRET', '');
	define('SITE_URL', '');
	
	//facebook
	define('APP_ID','');
	define('APP_SECRET','');
	date_default_timezone_set('Asia/Tokyo');
	 
	error_reporting(E_ALL & ~E_NOTICE);
	 
	session_set_cookie_params(0, '/');

?>