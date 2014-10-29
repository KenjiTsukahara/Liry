<?php
 
require_once('config.php');
require_once('codebird.php');
 
session_start();
 
\Codebird\Codebird::setConsumerKey(CONSUMER_KEY, CONSUMER_SECRET);
$cb = \Codebird\Codebird::getInstance();
 
if (! isset($_GET['oauth_verifier'])) {
    // gets a request token
    $reply = $cb->oauth_requestToken(array(
        'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
    ));
 
    // stores it
    $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
 
    // gets the authorize screen URL
    $auth_url = $cb->oauth_authorize();
    header('Location: ' . $auth_url);
    die();
 
} else {
    // gets the access token
    $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $reply = $cb->oauth_accessToken(array(
        'oauth_verifier' => $_GET['oauth_verifier']
    ));
    $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
    
    $me = $cb->account_verifyCredentials();
    
    // insert detabase
    
    try {
        $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        $stmt = $dbh -> query("SET NAMES utf8;");
        //$dbin = $dbh -> query("SET NAMES utf8;");
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
    
    $sql = "select * from user_data where user_id = :id limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":id" => $me->id));
    $user = $stmt->fetch();
    
    if (!$user) {
    
    	$url = $me->profile_image_url;
		$data = file_get_contents($url);
		$date = date('Ymd-His');
		$file_name = $date . sha1( uniqid( mt_rand() , true ) ) . ".jpg";
		file_put_contents('../user_img/'.$file_name, $data);
    	
    	$img_url = 	"http://www.liry.me/user_img/".$file_name;
    	
        $sql = "insert into user_tw 
                (tw_user_id, tw_screen_name, tw_user_picture, tw_access_token, tw_access_token_secret, created, modified) 
                values 
                (:tw_user_id, :tw_screen_name, :tw_user_picture, :tw_access_token, :tw_access_token_secret, now(), now())";
        $stmt = $dbh->prepare($sql);
        $params = array(
            ":tw_user_id" => $me->id_str,
            ":tw_screen_name" => $me->screen_name,
			":tw_user_picture" => $img_url,
            ":tw_access_token" => $reply->oauth_token,
            ":tw_access_token_secret" => $reply->oauth_token_secret
        );
        $stmt->execute($params);
        
        
        //insert database
        $sql = "insert into user_data 
                (user_id, name, picture, prof, login_sns, created, modified) 
                values 
                (:user_id, :name, :picture, :prof, :login, now(), now())";
        $dbin = $dbh->prepare($sql);
        $params = array(
            ":user_id" => $me->id_str,
            ":name" => $me->screen_name,
			":picture" => $img_url,
            ":prof" => $me->description,
            ":login" => "twitter"
        );
        $dbin->execute($params);
        
        $myId = $dbh->lastInsertId();
        $sql = "select * from user_data where id = :id limit 1";
        $dbin = $dbh->prepare($sql);
        $dbin->execute(array(":id" => $myId));
        $user = $dbin->fetch();    
    }
    
    // process login
    if (!empty($user)) {
    
        // session hijack measure
        session_regenerate_id(true);
        $_SESSION['me'] = $user;
        
        $stmt = $dbh->prepare("update user_data set modified=now() where id=:id");
        $params = array(
        ":id"=>$_SESSION['me']['id']
        );
        $stmt->execute($params);
        $_SESSION['tw'] = 1;
		header('Location: ../login_cookie.php');    
    }

    
}

?>