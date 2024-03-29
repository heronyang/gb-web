<?php

/* ========================================================================== */
// load settings
$local_config_filename = 'local_config.php';
if(file_exists($local_config_filename)) {
    include $local_config_filename;
} else {

    // force HTTPS on deployeed environment
    if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "http") {
        if(!headers_sent()) {
            header("Status: 301 Moved Permanently");
            header(sprintf(
                'Location: https://%s%s',
                $_SERVER['HTTP_HOST'],
                $_SERVER['REQUEST_URI']
            ));
            exit();
        }
    }

    //
    define('DEBUG_MODE', False);
    define('WEB_URL', 'https://gb-web.herokuapp.com');

    // FB
    define('FB_APPID', getenv('FB_APPID'));
    define('FB_SECRET', getenv('FB_SECRET'));

    // DB
    $dburl=parse_url(getenv("CLEARDB_DATABASE_URL"));
    define('DBHOST', $dburl["host"]);
    define('DBUSER', $dburl["user"]);
    define('DBPASS', $dburl["pass"]);
    define('DBNAME', substr($dburl["path"],1));

}

// turn on error if debug mode
if(DEBUG_MODE) {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
}

// load vendor libs
require '../vendor/autoload.php';
$app = new \Slim\Slim();

// header setups
$app->response->headers->set("Content-Type", "application/json; charset=utf-8");
$app->response->headers->set("Access-Control-Allow-Credentials", "true");
if(DEBUG_MODE){
	$app->response->headers->set("Access-Control-Allow-Origin", $app->request->headers->get('Origin'));
} else {
	$app->response->headers->set("Access-Control-Allow-Origin", "https://gb-web.herokuapp.com/");
}

//
include 'functions.inc.php';

/* 
 * GB Login/Logout APIs
 */

/* ========================================================================== */
/*
 * Method: GET /login
 * Parameter: None
 * Response:
 *  - 302: User not logged in yet, redirect user to Facebook OAuth page
 *  - 302: User came back from Facebook OAuth page, redirect user to main site
 */
$app->get('/login', function() use($app) {

	$facebook = getFacebook();
	$fbid = $facebook->getUser();

    if($fbid) {
        $app->redirect($_SESSION['referer']);
    } else {

		if(!isset($_GET['error']) && $app->request->getReferer() != null){
			// Store referer in SESSION, used in when redirecting user back
			$_SESSION['referer'] = $app->request->getReferer();
		}else if($app->request->getReferer() == null){
			// User tried to visit /login directly, set URL to redirect back to default
			$_SESSION['referer'] = WEB_URL;
		}else{
			// User may be coming back from Facebook OAuth, but denined access
			// Redirect back to where user came from
			$app->redirect($_SESSION['referer']);
		}

		$login_params = array(
			'scope' => 'user_friends, email, manage_friendlists'
		);

		$app->redirect($facebook->getLoginUrl($login_params));
	}

});

/* ========================================================================== */
/*
 * Method: GET /logout
 * Parameter: None
 * Response: 
 *  - 200: success
 */
$app->get('/logout', function() {
	session_start();
	session_destroy();
});


/* 
 * GB APIs
 */
abstract class GB_STATUS {
    const ENABLED   = 0;
    const DISABLED  = 1;
    const SUCCEED   = 2;
}

/* ========================================================================== */
/*
 * Method: POST /gb
 * Parameter:
 *  - target_user (fbid_tagglable)
 *  - content
 *  Response:
 *  - 400, code=1: content is too large
 *  - 400, code=2: user1 error
 *  - 400, code=3: user2 error
 *  - 400, code=4: bad parameter 
 */
$MAX_CONTENT_SIZE = 1500;
$app->post('/gb', function() use($app) {

	$facebook = getFacebook();
	$fbid = $facebook->getUser();

    // not logged in, permission denied
	if(!$fbid)  $app->halt(403, json_encode(array("data" => "[POST /gb]: not logged in")));

    // disable old gbs
    try {

        $db = getDatabaseConnection();
        $sql = 'UPDATE `gb` SET `status` = :status WHERE `user1_appid` = :user1_appid';
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                ":status"       => GB_STATUS::DISABLED,
                ":user1_appid"  => $fbid
            )
        );

	} catch(PDOException $e) {

        $tag = "[GET /gb](disable old gbs) Error";
        error_log( $tag . ": " . $e->getMessage() );
		$app->halt(500, $tag);

	}

    // add new
    global $MAX_CONTENT_SIZE;
    if( isset($_POST['target_user_url']) && isset($_POST['target_user_id']) && isset($_POST['content']) ) {

        error_log("content size: " . strlen($_POST['content']));
        if(strlen($_POST['content']) > $MAX_CONTENT_SIZE) {
            $app->halt(400, json_encode(array("data" => "Content is too long", "code" => "1")));
        }

        try {

            $user1          = getMeRealId($facebook);
            $user1_appid    = $fbid;
            $r = $facebook->api('/me');
            $user1_email    = $r['email'];
            $user1_name     = $r['name'];

            $user2          = getRealIdByPhoto($_POST['target_user_url']);
            $user2_tagid    = $_POST['target_user_id'];
            $user2_tagurl   = $_POST['target_user_url'];
            $user2_name     = $_POST['target_user_name'];

            $content        = $_POST['content'];
            $status         = GB_STATUS::ENABLED;

            if( $user1==0 ) {
                $app->halt(400, json_encode(array("data" => "[POST /gb] Error: can't handle this user1", "code" => "2")));
            } else if ( $user2==0 ) {
                $app->halt(400, json_encode(array("data" => "[POST /gb] Error: can't handle this user2", "code" => "3")));
            }

            $sql = 'INSERT INTO `gb` (`user1`, `user1_appid`, `user1_email`, `user1_name`, `user2`, `user2_tagid`, `user2_tagurl`, `user2_name`, `content`, `status`, `ctime`) VALUES (:user1, :user1_appid, :user1_email, :user1_name, :user2, :user2_tagid, :user2_tagurl, :user2_name, :content, :status, NOW())';
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    ':user1'=>          $user1,
                    ':user1_appid'=>    $user1_appid,
                    ':user1_email'=>    $user1_email,
                    ':user1_name'=>     $user1_name,
                    ':user2'=>          $user2,
                    ':user2_tagid'=>    $user2_tagid,
                    ':user2_tagurl'=>   $user2_tagurl,
                    ':user2_name'=>     $user2_name,
                    ':content'=>        $content,
                    ':status'=>         $status,
                )
            );
            echo json_encode (array("data" => "success"));

        } catch(PDOException $e) {

            $tag = "[GET /gb](add new) Error";
            error_log( $tag . ": " . $e->getMessage());
            $app->halt(500, json_encode(array("date" => $tag)));

        }

    } else {
        // bad request
        $app->halt(400, json_encode(array("data" => "bad request", "code" => "4")));
    }

    // post on wall (only for passing the Facebook Review)
    /*
    try {
        $content    = $_POST['content'];
        $tag_friend = $_POST['target_user_id'];
        error_log('tag: '.$tag_friend);
        $facebook->api('me/feed', 'POST', array(
            'message'   => $content,
            'tags'      => $tag_friend,
            'place'     => '147492585312445'
        ));
        echo json_encode (array("data" => "success"));
    } catch(PDOException $e) {
        $tag = "[POST /gb] Error (can't post)";
        error_log($tag . ": " . $e->getMessage());
        $app->halt(500, $tag);
    }
     */

});

/* ========================================================================== */
/*
 * Method: GET /gb
 * Parameter: -
 */
$app->get('/gb', function() use($app) {

	$facebook = getFacebook();
	$fbid = $facebook->getUser();

    // not logged in, permission denied
	if(!$fbid)  $app->halt(403, "[GET /gb]: not logged in");

    try {

        $db = getDatabaseConnection();
        $sql = 'SELECT `gid`, `user1`, `user2`, `content`, `status`, `ctime`, `mtime` FROM `gb` WHERE `user1_appid` = :user1_appid AND `status` = :status';

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                ':user1_appid'    => $fbid,
                ':status'   => GB_STATUS::ENABLED
            )
        );
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$result)    echo json_encode (array("data" => ""));
        else            echo json_encode (array("data" => $result));

	} catch(PDOException $e) {

        $tag = "[GET /gb] Error";
        error_log( $tag . ": " . $e->getMessage());
		$app->halt(500, $tag);

	}
});

/* ========================================================================== */
/*
 * Method: GET /gb_success
 * Parameter:
 * Response: 
 *  - 200: success
 */
$app->get('/gb_success', function() use($app) {
    try {

        $db = getDatabaseConnection();
        $sql = 'SELECT `gsid`, `user1`, `user2`, `gid1`, `gid2`, `status`, `ctime`, `mtime` FROM `gb_success`';

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$result) {
            echo json_encode (array("data" => ""));
            $app->stop();
        }
        foreach($result as $key => $value) {
            $sql = 'SELECT `user1_name`, `content`, `status`, `ctime`, `mtime` FROM `gb` WHERE `gid` = :gid';
            $stmt = $db->prepare($sql);

            $gids = array('gid1', 'gid2');
            foreach($gids as $gs) {
                $stmt->execute(
                    array(
                        ':gid' => $result[$key][$gs]
                    )
                );
                $result[$key][$gs."_d"] = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

        echo json_encode (array("data" => $result));

	} catch(PDOException $e) {

        $tag = "[GET /gb_success] Error";
        error_log( $tag . ": " . $e->getMessage());
		$app->halt(500, $tag);

	}
});

/* ========================================================================== */
/*
 * Method: GET /fb_friends
 * Parameter: -
 */
$app->get('/fb_friends', function() use($app) {
	$facebook = getFacebook();
	$fbid = $facebook->getUser();

    // not logged in, permission denied
	if(!$fbid)  $app->halt(403, "[GET /fb_friends]: not logged in");

    //
    $friends = $facebook->api('me/taggable_friends?fields=name,picture.width(100)');
	if(!$friends)  $app->halt(400, "[GET /fb_friends]: can't get friends");
    echo json_encode($friends);
});

/* ========================================================================== */
//
$app->run();
?>
