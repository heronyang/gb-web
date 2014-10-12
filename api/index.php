<?php

/* ========================================================================== */
// 
define('DEBUG_MODE', True);
define('WEB_URL', 'https://gb-web.herokuapp.com/');

// turn on error if debug mode
if(DEBUG_MODE) {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
}

// load vendor libs
require '../vendor/autoload.php';
$app = new \Slim\Slim();

// load settings
$local_config_filename = 'local_config.php';
if(file_exists($local_config_filename)) {
    include $local_config_filename;
} else {
    define('FB_APPID', getenv('FB_APPID'));
    define('FB_SECRET', getenv('FB_SECRET'));

    // DB
    $dburl=parse_url(getenv("CLEARDB_DATABASE_URL"));
    define('DBHOST', $dburl["host"]);
    define('DBUSER', $dburl["user"]);
    define('DBPASS', $dburl["pass"]);
    define('DBNAME', substr($dburl["path"],1));
}

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
 *  - 302: User came back from Facebook OAuth page, redirect user to Pairs Web
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
			'scope' => 'user_friends, email'
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
 * Response: 
 *  - 200: success
 */
$app->post('/gb', function() use($app) {

	$facebook = getFacebook();
	$fbid = $facebook->getUser();

    // not logged in, permission denied
	if(!$fbid)  $app->halt(403, "[POST /gb]: not logged in");

    // disable old gbs
    try {

        $db = getDatabaseConnection();
        $sql = 'UPDATE `gb` SET `status` = '.GB_STATUS::DISABLED.' WHERE `user1` = :user1';
        $stmt = $db->prepare(sql);
        $stmt->execute( {
            array(
                ":user1" => user1
            )
        }

	} catch(PDOException $e) {

        $tag = "[GET /gb](disable old gbs) Error";
        error_log( $tag . ": " . $e.->getMessage());
		$app->halt(500, $tag);

	}

    // add new
    if( isset($_POST['target_user' && isset($_POST['content'] ) {

        try {

            $user1 = $fbid;
            $user2 = getRealIdByPhoto($_POST['target_uer');
            $content = $_POST['content'];
            $status = GB_STATUS:ENABLED;

            $sql = 'INSERT INTO `gb` (`user1`, `user2`, `content`, `status`) VALUES (:user1, :user2, :content, :status)';
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    ':user1':   $user1,
                    ':user2':   $user2,
                    ':content': $content,
                    ':status':  $status
                )
            );
            echo json_decode (array("data" => "success"));

        } catch(PDOException $e) {

            $tag = "[GET /gb](add new) Error";
            error_log( $tag . ": " . $e.->getMessage());
            $app->halt(500, $tag);

        }

    } else {
        // bad request
        $app->halt(400, "[POST /gb]: bad request");
    }

}

/* ========================================================================== */
/*
 * Method: GET /gb
 * Parameter:
 * Response: 
 *  - 200: success
 */
$app->get('/gb', function() use($app) {

	$facebook = getFacebook();
	$fbid = $facebook->getUser();

    // not logged in, permission denied
	if(!$fbid)  $app->halt(403, "[GET /gb]: not logged in");

    try {

        $db = getDatabaseConnection();
        $sql = 'SELECT `gid`, `user2`, `content`, `status`, `ctime`, `mtime` FROM `gb` WHERE `user1` = :user1 AND `status` = :status';

        $stmt = $db->prepare($sql);
        $stmt->execute(
            ":user1"    => $fbid,
            ":status"   => GB_STATUS::ENABLED
        );
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_decode (array("data" => $result));

	} catch(PDOException $e) {

        $tag = "[GET /gb_success] Error";
        error_log( $tag . ": " . $e.->getMessage());
		$app->halt(500, $tag);

	}
}

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
        $sql = 'SELECT `gsid`, `user1`, `user2`, `gid1`, `gid2`, `ctime`, `mtime`, `status` FROM `gb_success`';

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        foreach($result as $key => $value) {
            $sql = 'SELECT `content`, `status`, `ctime`, `mtime` FROM `gb_success` WHERE `gid` = :gid';
            $stmt = $db->prepare($sql);

            $gids = array('gid1', 'gid2');
            foreach($gs as $gids) {
                $stmt->execute(
                    array(
                        ':gid' => $result[$key][$gs]
                    )
                );
                $result[$key][$gs] = $stmt->fetch(PDO:FETCH_ASSOC);
            }
        }

        echo json_decode (array("data" => $result));

	} catch(PDOException $e) {

        $tag = "[GET /gb_success] Error";
        error_log( $tag . ": " . $e.->getMessage());
		$app->halt(500, $tag);

	}
}

/* ========================================================================== */
//
$app->run();
?>
