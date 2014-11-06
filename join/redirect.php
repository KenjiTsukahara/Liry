<?php 
	include 'common_join.php';
 
	if (empty($_GET['code'])) {
	   
	    // 認証の準備
	    $_SESSION['state'] = sha1(uniqid(mt_rand(), true));
	    
	    $params = array(
	        'client_id' => APP_ID,
	        'redirect_uri' => 'http://www.liry.me/join/redirect.php',
	        'state' => $_SESSION['state'],
	        'scope' => 'email,user_about_me,publish_stream'
	    );
	    
	    $url = "https://www.facebook.com/dialog/oauth?".http_build_query($params);    
	    
	  // facebookに一旦飛ばす
	    header('Location: '.$url);
	    exit;
	 
	} else {
	
	    // 認証後の処理
	    if ($_SESSION['state'] != $_GET['state']) {
	        echo "処理できません";
	        exit;
	    }
	    
	    // ユーザー情報の取得
	    $params = array(
	        'client_id' => APP_ID,
	        'client_secret' => APP_SECRET,
	        'code' => $_GET['code'],
	        'redirect_uri' => 'http://www.liry.me/join/redirect.php'
	    );
	    $url = 'https://graph.facebook.com/oauth/access_token?'.http_build_query($params);    
	    $body = file_get_contents($url);
	    parse_str($body);
	    
		$url = 'https://graph.facebook.com/me?access_token='.$access_token.'&fields=name,picture,bio,email';
		$me = json_decode(file_get_contents($url));

	    $get_id = $me->id;
	    $get_name = $me->name;
	    $get_bio = $me->bio;
	    $get_mail = $me->email;
	    $get_picture = 'https://graph.facebook.com/' . $get_id . '/picture?type=large';
	    
	        
	    $stmt = $dbh->prepare("select * from user_data where user_id=:u_id limit 1");
	    $stmt->execute(array(":u_id"=>$get_id));
	    $user = $stmt->fetch();
	 
	  if (empty($user)) {
			
			
			$url = $get_picture;
			$data = file_get_contents($url);
			$date = date('Ymd-His');
			$file_name = $date . sha1( uniqid( mt_rand() , true ) ) . ".jpg";
			file_put_contents('../user_img/'.$file_name, $data);
	    	
	    	$img_url = 	"http://www.liry.me/user_img/".$file_name;
			
			
			$stmt = $dbh->prepare("insert into user_data (user_id, login_sns, picture, prof, name, email, created, modified) values (:getuser, :getlogin, :getpicture, :getprof, :getname, :getmail, now(),now())");
			   
			$stmt->execute(array(":getuser"=>$get_id,":getlogin"=>'facebook',":getpicture"=>$img_url,":getprof"=>$get_bio,":getname"=>$get_name,":getmail"=>$get_mail));
	        
	        //セッションに入れるデータをselect
	        $sesin = $dbh->prepare("select * from user_data where id=:last_insert_id limit 1");
	        $sesin->execute(array(":last_insert_id"=>$dbh->lastInsertId()));
	        $user = $sesin->fetch();
	
	        }
	        
	        
	        
	        
	    if (!empty($user)) {
	    
	        session_regenerate_id(true);
	        $_SESSION['me'] = $user;
	        $stmt = $dbh->prepare("update user_data set modified=now() where id=:id");
	        $params = array(
	        ":id"=>$_SESSION['me']['id']
	        );
	        $stmt->execute($params);
	        header('Location: ../php/login_cookie.php');
	    }
	    
	
	}

?>
