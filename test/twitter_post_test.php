<?php

error_reporting(E_ALL); ini_set('display_errors', 1);

//$root_dir=dirname(dirname(__FILE__)); 
$root_dir=dirname($_SERVER['DOCUMENT_ROOT']); 

$include=__DIR__."/includes/"; 

require($include."twitteroauth/autoload.php");
require($include."TwitterApi.php");
		

use Abraham\TwitterOAuth\TwitterOAuth;
use Demo\Api\TwitterApi;

$access_token="ACCESS_TOKEN_XYZ";
$access_token_secret="ACCESS_TOKEN_SECRET_XYZ";

define("CONSUMER_KEY", "CONSUMER_KEY_XYZ");
define("CONSUMER_SECRET", "CONSUMER_SECRET_XYZ");


$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);
$content = $connection->get("account/verify_credentials");






// postText($status);
// postPhoto($status, $media_list = []);
// postVideo($status, $media_list = []);

$access_token = '**';
$access_token_secret = '**';
$consumer_key = '**';
$consumer_secret = '**';
$tw = new TwitterApi([
'access_token' => $access_token,
'access_token_secret' => $access_token_secret,
'consumer_key' => $consumer_key,
'consumer_secret' => $consumer_secret 
]);


$result = $tw->postText("Good #morning #America 🌞🌞😎");

print_r($result);



?>