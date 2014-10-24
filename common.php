<?php

	mb_language('ja');
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8');
	header('Content-Type: text/html; charset=UTF-8');
	
	session_start();
	require_once('join/config.php');

	try {
        $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        $stmt = $dbh -> query("SET NAMES utf8;");
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }

	
	function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
	}
	
?>