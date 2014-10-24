<?php 
	mb_language('ja');
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8');
	header('Content-Type: text/html; charset=UTF-8');
?>
<?php
	
	$subject = $_POST['name'] . "/" . $_POST['mail'];
	$body = $_POST['detail'];
	
	if(mb_send_mail("liryme0302@gmail.com",$subject,$body)){
	   echo "メール送信に成功しました。5秒後にTOPへ戻ります。";
	}else{
	   echo "メール送信に失敗しました。最初からやり直して下さい。5秒後にTOPへ戻ります。";
	}
	
?>
<HTML>
<HEAD>
<meta http-equiv="Refresh" content="5, http://www.liry.me/join/">
<TITLE></TITLE>
</HEAD>
<BODY>
</BODY>
</HTML>