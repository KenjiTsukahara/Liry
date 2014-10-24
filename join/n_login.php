<?php
	if(isset($_POST['login_mail'])) {
	
	
			$input_pass = $_POST['login_password'];
			$input_hash = sha1($input_pass);
			
		
		    //session prof get
		    $stmt = $dbh->prepare("select * from user_n where email=:request and password=:request2 limit 1");
		    $stmt->execute(array(":request"=>$_POST['login_mail'], ":request2"=>$input_hash));
		    $get_data = $stmt->fetch();
	    
	    
	    if(!$get_data) {
	    
			$login_erorr = 'Loginできません';
	    
	    }elseif($get_data) {
	    
		    $stmt = $dbh->prepare("select * from user_data where id=:request2 limit 1");
		    $stmt->execute(array(":request2"=>$get_data['uniq_id']));
		    $get_data2 = $stmt->fetch();
	    
	    
	    // セッションハイジャック対策
	        session_regenerate_id(true);
	        $_SESSION['me'] = $get_data2;
	
			$stmt = $dbh->prepare("update user_data set modified=now() where id=:id");
	        $params = array(
	        ":id"=>$get_data2['id']
	        );
	        $stmt->execute($params);
				 
			header('Location: ../login_cookie.php');
	    
	    
	    }
	
	}


    
	if(isset($_POST['mail'])) {
	
	
		$sql = "select email from user_data where email = :id limit 1";
		$stmt = $dbh->prepare($sql);
		$stmt->execute(array(":id" => $_POST['mail']));
		$user = $stmt->fetch();
		
		
		if($user) {
		
			$error = 'on';
		
		} else {
		
			$sql = "select * from user_data where email = :id limit 1";
			$stmt = $dbh->prepare($sql);
			$stmt->execute(array(":id" => $_POST['mail']));
			$user1 = $stmt->fetch();
			

		
			if (!$user1) {
			
				$num = mt_rand(0,2147483647);
			
				//user_dataに挿入
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
				
				$pass = sha1($_POST['password']);
				$sql = "insert into user_n
				(user_id, email, password, created, uniq_id) 
				values 
				(:user_id, :email, :password, now(), :uniq)";
				$stmt = $dbh->prepare($sql);
				$params = array(
				":user_id" => $_POST['user_id'],
				":email" => $_POST['mail'],
				":password" => $pass,
				":uniq" => $myId
				);
				$stmt->execute($params);
				
				$sql = "select * from user_data where id = :id limit 1";
				$dbin = $dbh->prepare($sql);
				$dbin->execute(array(":id" => $myId));
				$session_user_data = $dbin->fetch(); 

				  
			}
			
			// ログイン処理
			if (!empty($session_user_data)) {
				// セッションハイジャック対策
				session_regenerate_id(true);
				$_SESSION['me'] = $session_user_data;
				header('Location: ../index.html');   
			
			}
		}
	}
	
?>