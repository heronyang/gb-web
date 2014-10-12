<?php
function getFacebook(){
	$facebook = new Facebook(array('appId' => FB_APPID, 'secret' => FB_SECRET));
	return $facebook;
}

function getDatabaseConnection() {
	$dbh = new PDO(
	"mysql:host=". DBHOST. ";dbname=". DBNAME. ";charset=utf8", DBUSER, DBPASS,
		array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = \'+00:00\''
		)
	);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function getRealIdByPhoto($photo_input){

	/*
     * It is possible to determine the real Facebook user ID of the owner of a photo with direct fbcdn link.  Take the default Facebook profile picture for example https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfp1/t1.0-1/c15.0.50.50/p50x50/954801_10150002137498316_604636659114323291_n.jpg If we split the filename of the picture using "_", the second number would be the ID to that photo, which is '10150002137498316' in this case.  By accessing https://www.facebook.com/$PHOTOID, we will be redirected to the page that shows the photo, which contains some info about the photo.  In this case, we will be redirected to the following URL.  https://www.facebook.com/photo.php?fbid=10150002137498316&set=a.1001968110775.1364293.499829591&type=1 The interesting thing is that if we split the get parameter "set" using ".", the third number would be the real user ID of the owner of the photo.
     */ 

	// Check if input is URL or photo ID
	if(strrpos($photo_input, "_") != false){
		$photo_data = explode("_", $photo_input);
		$photo_id = $photo_data[1];
	}else{
		$photo_id = $photo_input;
	}

	// With the "follow_location" set to be false, we'll be able to get the redirection target of requested url by reading the "Location: " HTTP header.
	$context = stream_context_create(
		array(
			'http' => array(
				'follow_location' => false
			)
		)
	);

	$html = file_get_contents('https://www.facebook.com/'.$photo_id, false, $context);

	// If the provided ID represents a photo which is not public, redirection target would be an error page, so we'll only parse the redirection target if it contains "photo.php", which indicates it's a valid url to a photo.
	if (strpos($http_response_header['1'],'photo.php') !== false) {
		$tmp = explode('.', $http_response_header['1']);
		return explode('&', $tmp[6])[0];
	}else{
		// Returns 0 if could not find real ID
		return 0;
	}

}

function getMeRealId($facebook) {
    $user_profile = $facebook->api('/me','GET');
    $picture_data = $facebook->api('/me/picture?redirect=false','GET');
    $fbid_real = -1;
    $photo_id = explode("_", $picture_data['data']['url'])[1];
    if(!$picture_data['data']['is_silhouette']){
        $fbid_real = getRealIdByPhoto($picture_data['data']['url']);
    }
    return $fbid_real;
}
