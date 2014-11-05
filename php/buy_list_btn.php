<?php

	include 'common.php';

	if (empty($_SESSION['me'])) {
		header('Location: '.SITE_URL.'index.html');
		exit;
	}
	
	if(!empty($_POST['contents'])) {
		
				//active or not
				$like = $dbh->prepare("select id from buy_lists where buy_list_user_id=:me AND buy_list_contents_id=:request");
			    $like->execute(array(":me"=>$_SESSION['me']['id'],":request"=>$_POST['contents']));
			    $likes = $like->fetch();
		    
		    
				
			    if(empty($likes)) {
			    
			    
					    //get contents
					    $fashion = $dbh->prepare("select id from contents_f where id=:get");
					    $fashion->execute(array(":get"=>$_POST['contents']));
					    $fashions = $fashion->fetch();
						    
						    $sql = "insert into buy_lists 
					                (buy_list_user_id, buy_list_contents_id, created) 
					                values 
					                (:user, :contents, now())";
					        $like = $dbh->prepare($sql);
					        $params = array(
					        	":user" => $_SESSION['me']['id'],
					        	":contents" => $_POST['contents']
					        );
					        $like->execute($params);
					        
				$getid = $dbh->prepare("select contents_id from contents_f where id=:me");
			    $getid->execute(array(":me"=>$_POST['contents']));
			    $getids = $getid->fetch();
					     
					    //count+
					    $stmt = $dbh->prepare("UPDATE contents_q SET buy_list_counts=buy_list_counts+1 WHERE id=:request");
					    $stmt->execute(array(":request"=>$getids['contents_id']));
					        
					    $stmt2 = $dbh->prepare("UPDATE contents_f SET buylist_count=buylist_count+1 WHERE id=:request");
					    $stmt2->execute(array(":request"=>$_POST['contents']));
					    
					    
			        
			 
			 //if active     
			 } elseif(!empty($likes)) {
			        
				        $unlike = $dbh->prepare("delete from buy_lists where buy_list_user_id=:me AND buy_list_contents_id=:request");
				    $unlike->execute(array(":me"=>$_SESSION['me']['id'], ":request"=>$_POST['contents']));
				    
				    
					  //count-
				    $stmt = $dbh->prepare("UPDATE contents_q SET buy_list_counts=buy_list_counts-1 WHERE id=:request");
				    $stmt->execute(array(":request"=>$_POST['contents']));
				        
				    $stmt2 = $dbh->prepare("UPDATE contents_f SET buylist_count=buylist_count-1 WHERE id=:request");
				    $stmt2->execute(array(":request"=>$_POST['contents']));
			        
			        
			 }
		        
			    $like_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM buy_lists WHERE buy_list_contents_id=:likes;");
			    $like_count->execute(array(":likes"=>$_POST['contents']));
			    $like_counts = $like_count->fetchColumn();
		        
	}
        

	echo $_POST['contents'] . "," . $like_counts;

	
?>