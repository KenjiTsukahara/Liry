<?php

	include 'common.php';

	//judge error
	if(!empty($_POST['post'])) {
	
		$fileName = $_FILES['img']['name'];
	
		if(empty($_POST['name'])) {
			
			echo '<script language="javascript">';
			echo 'alert("nameが空欄です");';
			echo 'location.href("mypage_edit");';
			echo '</script>';
			
		}
	
	
		if(!empty($fileName)) {
			$ext = substr($fileName, -3);
			if($ext != 'jpg' && $ext != 'gif' && $ext != 'png' && $ext != 'gif') {
				echo '<script language="javascript">';
				echo 'alert("fileの拡張子が無効です");';
				echo 'location.href("mypage_edit");';
				echo '</script>';
			}
		}
	
	
	
	//img upload
	$filein = $_FILES['img']['name'];
	if(!empty($filein)) {
	
		$image = 'user_img/' .date('YmdHis') . $_FILES['img']['name'];
	
		move_uploaded_file($_FILES['img']['tmp_name'],$image);
	
	}else {
	
	//get prof
	    $stmt = $dbh->prepare("select picture from user_data where id=:request");
	    $stmt->execute(array(":request"=>$_SESSION['me']['id']));
	    $ss_prof = $stmt->fetch();
		$image = $ss_prof['picture'];
	}
	
		$stmt = $dbh->prepare("update user_data set name=:name, prof=:prof, picture=:picture, modified=now() where id=:id");
	    $params = array(
	        ":name"=>$_POST['name'],
	        ":prof"=>$_POST['about'],
	        ":picture"=>$image,
	        ":id"=>$_SESSION['me']['id']
	    );
	    $stmt->execute($params); 
	    $url = $_REQUEST['id'];
	    $page_url_get = 'http://www.liry.me';
		header("Location: $page_url_get");
		exit();
	}
