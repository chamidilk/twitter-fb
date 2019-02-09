<?php

$app_id="***";
$redirect_url=urlencode("***");
$client_secret="***";

$url1="https://www.facebook.com/dialog/oauth?client_id=".$app_id."&redirect_uri=".$redirect_url."&scope=read_stream,publish_stream";	

$url2="https://graph.facebook.com/oauth/access_token?client_id=".$app_id."&redirect_uri=".$redirect_url."&client_secret=".$client_secret."&code=CODE";

$url3="https://graph.facebook.com/oauth/access_token
  ?client_id=".$app_id."
  &client_secret=".$client_secret."
  &redirect_uri=".$redirect_url."
  &grant_type=client_credentials";

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url1,
));
// Send the request & save response to $resp
$resp = curl_exec($curl);

var_dump($resp);
print_r($_GET);

/*if(!curl_exec($curl)){
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
}*/
// Close request to clear up some resources
curl_close($curl);	
	
?>