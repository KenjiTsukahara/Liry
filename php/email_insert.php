<?php 
	include 'common.php';
		    
	if (empty($_SESSION['me'])) {
	    header('Location: '.SITE_URL.'index.html');
		exit;
	}
	

 	$stmt = $dbh->prepare("select email from user_data where id=:request");
    $stmt->execute(array(":request"=>$_SESSION['me']['id']));
    $mail = $stmt->fetch();

	if(isset($_POST['email'])) {
	
		$get_mails = $_POST['email'];
	
		//正規表現error
		if (!preg_match('/^(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+))*)|(?:"(?:\\[^\r\n]|[^\\"])*")))\@(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/', $get_mails)) {
	    $error1 = '正しいメールアドレスを入力してください。';
	    
	    }
    
    //dupulicate error
	    $stmt = $dbh->prepare("select email from user_data where email=:request");
	    $stmt->execute(array(":request"=>$get_mails));
	    $dub_mail = $stmt->fetch();
	    
	    if(!empty($dub_mail)) {
		    
		    $error2 = 'このメールアドレスはすでに登録されています';
		    
		    
	    }
    
    


		if(empty($error1) && empty($error2)) {
		
		//insert user_data
	        
	        $sql = "update user_data set email=:mail where id=:id";
	        $email = $dbh->prepare($sql);
	        $params = array(
	        	":mail" => $get_mails,
	        	":id" => $_SESSION['me']['id']
	        );
	        $email->execute($params);
	        header("Location: index.html");
			exit();
		}
	}
?>