<?php

	include 'common.php';
	if (empty($_SESSION['me'])) {
		header('Location: '.SITE_URL.'index.html');
		exit;
	}
    

	if(!empty($_POST['contents'])) {
	
			//liked or not
		$like = $dbh->prepare("select id from likes where like_user_id=:me AND like_contents_id=:request");
		$like->execute(array(":me"=>$_SESSION['me']['id'],":request"=>$_POST['contents']));
		$likes = $like->fetch();
		    
		    
		
		if(empty($likes)) {
		    
		    
				    // get liked contents
				    $fashion = $dbh->prepare("select id from contents_q where id=:get");
				    $fashion->execute(array(":get"=>$_POST['contents']));
				    $fashions = $fashion->fetch();
					    
					    $sql = "insert into likes 
				                (like_user_id, like_contents_id, created) 
				                values 
				                (:user, :contents, now())";
				        $like = $dbh->prepare($sql);
				        $params = array(
				        	":user" => $_SESSION['me']['id'],
				        	":contents" => $_POST['contents']
				        );
				        $like->execute($params);
				        
				     
				    //like+
				    $stmt = $dbh->prepare("UPDATE contents_q SET likes=likes+1 WHERE id=:request");
				    $stmt->execute(array(":request"=>$_POST['contents']));
		        
		        
		 
		       
		 } elseif(!empty($likes)) {
		        
		       
				    $unlike = $dbh->prepare("delete from likes where like_user_id=:me AND like_contents_id=:request");
				    $unlike->execute(array(":me"=>$_SESSION['me']['id'], ":request"=>$_POST['contents']));
				    
				    
				    //like-
				    $stmt = $dbh->prepare("UPDATE contents_q SET likes=likes-1 WHERE id=:request");
				    $stmt->execute(array(":request"=>$_POST['contents']));
		        
		        
		}
		        
		    $like_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM likes WHERE like_contents_id=:likes;");
		    $like_count->execute(array(":likes"=>$_POST['contents']));
		    $like_counts = $like_count->fetchColumn();
	        
	}
        

		
		echo $_POST['contents'] . "," . $like_counts;	



?>