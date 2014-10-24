<?php 
	include 'common_join.php';
    
	if(isset($_POST['mail'])) {
	    
	    
		    $sql = "select email from user_data where email = :id limit 1";
		    $stmt = $dbh->prepare($sql);
		    $stmt->execute(array(":id" => $_POST['mail']));
		    $user = $stmt->fetch();
	    
	   if($user) {
		   
		   $error = '既に登録されているメールアドレスです';
		   
	   } else {
	    
	    
	    
		    if (!$user) {
		    
		    	$pass = $_POST['password'];
		    	$hash = hash('liry2289',$pass);
		    
		        $sql = "insert into user_n
		                (user_id, email, password, created) 
		                values 
		                (:user_id, :email, :password, now())";
		        $stmt = $dbh->prepare($sql);
		        $params = array(
		            ":user_id" => $_POST['user_id'],
		            ":email" => $_POST['mail'],
					":password" => $hash
		        );
		        $stmt->execute($params);
		        
		        
		        $num = mt_rand(0,2147483647);
		        
		        //usersのデータベースに挿入
		        $sql = "insert into user_data 
		                (user_id, name, email, picture, login_sns, created, modified) 
		                values 
		                (:user_id, :name, :email, :picture, :login, now(), now())";
		        $dbin = $dbh->prepare($sql);
		        $params = array(
		            ":user_id" => $num,
		            ":name" => $_POST['user_id'],
		            ":email" => $_POST['mail'],
					":picture" => 'user_img/noimg.jpg',
		            ":login" => "normal"
		        );
		        $dbin->execute($params);
		        
		        $myId = $dbh->lastInsertId();
		        $sql = "select * from user_data where id = :id limit 1";
		        $dbin = $dbh->prepare($sql);
		        $dbin->execute(array(":id" => $myId));
		        $user = $dbin->fetch();    
		    }
	    
	    // ログイン処理
	    	if (!empty($user)) {
	    
	        // セッションハイジャック対策
	        session_regenerate_id(true);
	        $_SESSION['me'] = $user;
	
	         // ホームに飛ばす
			 header('Location: ../index.html');    
	
			}
		}
	}
	
?>

<!DOCTYPE html>
<html lang="ja">
<head>

<meta charset="utf-8">
<title>Liry</title>
<meta name="description" content="">
<meta name="keywords" content="">

<!--Require Stylesheet-->
<link rel="stylesheet" href="../css/n_login.css">
<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css"/>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:700' rel='stylesheet' type='text/css'>


</head>
<body>

<div id="wrapper">

<div id="wrap-top">

<header>
<div id="header">

<div id="top_logo">
<img src="../img/LIRY_LOGO2.png" style="height:48px;"/>
</div>

<div id="nav_search">
</div>

<div id="header_signin">

<div id="user_img_icon">

</div>

</div><!--header_signin-->


</div>
</header>
</div><!--wrap-top-->
<div id="login_wrapper">

<div id="join_wrapper">
	
	
	<div id="form_wrapper">
	<form method="post" id="hoge" action="" name="regist">
	<p id="title">User name</p>
	<input type="text" name="user_id" id="user_id" placeholder="user name" class="validate[required,maxSize[12]]" value="<?php echo h($_POST['user_id']); ?>"/>
	<p id="title">E-mail <?php if(!empty($error)) { ?><span style="color:red; font-size:60%;">*既に登録されているメールアドレスです</span><?php } ?></p>
	<input type="text" name="mail" id="mail" placeholder="E-mail" class="validate[required,custom[email]]" value="<?php echo h($_POST['mail']); ?>"//>
	<p id="title">Password</p>
	<input type="password" name="password" id="password" placeholder="password 6-12"  class="validate[required,custom[onlyLetterNumber],minSize[6],maxSize[12]]">
	<input type="submit" id="submit" value="登録" />
	</form>
	</div>
	
</div>
</div><!--join_btn_wrapper-->


</div>


<div id="wrap-btm" style="position:absolute; bottom:0;">



<p id="copyright">Liry</p>


<!--wrap-btm--></div>

<!--wrapper--></div>

<!--js-->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="../js/jquery.backstretch.min.js"></script>
<script src="../js/jquery.validationEngine-ja.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	$("#login_wrapper").backstretch("../img/login_page_img.jpg");
</script>
<script type="text/javascript">
	$(function(){
	   jQuery("#hoge").validationEngine();
	});
</script>
</body>
</html>