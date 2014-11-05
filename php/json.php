<?php 

	require_once('join/config.php');

	try {
        $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        $stmt = $dbh -> query("SET NAMES utf8;");
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
    
    if($_POST['latest']){

    	$sql = "select id, user_id, picture, likes, finds, category, type, user_name, user_picture from contents_q ORDER by created DESC LIMIT 20,18446744073709551615";
   
   } elseif($_POST['help']) {
    	
    	 $sql = "select id, user_id, user_name, user_picture, picture, likes, finds, category, type from contents_q WHERE finds = 0 ORDER by likes DESC LIMIT 20,18446744073709551615";
	   
   } elseif($_POST['popular']) {
	    
	     $sql = "select id, user_id, picture, likes, finds, category, type,user_name,user_picture from contents_q WHERE finds >= 1 ORDER BY buy_list_counts DESC LIMIT 20,18446744073709551615";
	   
   }
   
  	//json contents get
    $json_contents = $dbh->prepare($sql);
    $json_contents->execute();
    $i = 0;
    $json_c = array();
    
	while($json = $json_contents->fetch(PDO::FETCH_ASSOC)) {
	   $json_c[$i] = $json;
	   $info_get = getimagesize($json_c[$i]['picture']);
	   $ratio = $info_get[1] / $info_get[0];
	   $ratio_get = $ratio * 250;
	   $info[$i] = round($ratio_get,1);
	   $i++;
	}
   
   
   $info = json_encode($info);
   
   print json_encode($json_c) . ",,,,,,,,,," . $info;
   
   
   