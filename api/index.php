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

$app->run();
?>
