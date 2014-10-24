<?php 
    
     //session prof get
    $stmt = $dbh->prepare("select picture, name from user_data where id=:request");
    $stmt->execute(array(":request"=>$_SESSION['me']['id']));
    $prof = $stmt->fetch();
    
	//judge erorr
	if(!empty($_POST['item_post'])) {
	
		$sel_t = '';
		$sel_c = '';
		$sel_g = '';
		
		$sel_t = $_POST['type'];
		$sel_c = $_POST['category'];
		$sel_g = $_POST['gender'];
		
		if(empty($_FILES['image']['name']) || empty($_POST['caption']) || empty($_POST['type']) || empty($_POST['category']) || empty($_POST['gender'])) {
			
			$erorr = 'empty';
		
		
		}
	
		$fileName = $_FILES['image']['name'];
		
		
		if(!empty($fileName)) {
		
			$ext = substr($fileName, -3);
			$exts = substr($fileName, -4);
			if($ext != 'jpg' && $ext != 'gif' && $ext != 'png' && $ext != 'gif' && $exts != 'jpeg') {
			$erorr = 'type';
			exit();
		
		}
	
	
	}
	
	if(empty($erorr)) {
	
		//img upload
		$file = date('YmdHis') . $_FILES['image']['name'];
		
		
		if(!empty($_FILES['image']['name'])) {
		
			if(strstr($file, ',,,,,,,,,,')) {
			
				$file = str_replace(",,,,,,,,,,","",$file);
			
			}
	
			$image = 'contents_img/' . $file;
			move_uploaded_file($_FILES['image']['tmp_name'],$image);
		}
		
		 $str = mb_convert_kana($_POST['caption'], "as", "UTF-8");

		 $sql = "insert into contents_q 
		                (category, caption, type, picture, user_id, gender, likes, finds, buy_list_counts, created, modified, user_name, user_picture) 
		                values 
		                (:category, :caption, :type, :picture, :user, :gender, :likes, :finds, :buy, now(), now(), :user_name, :user_picture)";
		        $stmt = $dbh->prepare($sql);
		        $params = array(
		            ":category" => $_POST['category'],
					":caption" => $str,
		            ":type" => $_POST['type'],
		            ":picture" => $image,
		            ":user" => $_SESSION['me']['id'],
		            ":gender" => $_POST['gender'],
		            ":likes" => 0,
		            ":finds" => 0,
		            ":buy" => 0,
		            ":user_name" => $prof['name'],
		            ":user_picture" => $prof['picture']
		            
		            );
		        $stmt->execute($params);
		
		$page_url_get = $_SERVER['HTTP_REFERER'];
		header("Location: index.html");
		
		exit();
		}
	}


?>
