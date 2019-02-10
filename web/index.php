<?php

require('../vendor/autoload.php');
require('./classes/TwitterApi.php');
require('./classes/FacebookApi.php');


use Abraham\TwitterOAuth\TwitterOAuth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Demo\Api\TwitterApi;

$app = new Silex\Application();
$app['debug'] = true;

// $log = new Logger('name');
// $log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));



// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  // mysql://b3212e2afff850:dedbf2ec@us-cdbr-iron-east-03.cleardb.net/heroku_33f91a4bb90418c?reconnect=true
  'dbs.options' => array (
    'driver'    => 'pdo_mysql',
    'url'      => getenv('CLEARDB_DATABASE_URL'),
    'dbname'    => 'heroku_33f91a4bb90418c',
    'charset'   => 'utf8mb4',
  ),
));

// Our web handlers

$app->get('/fb', function() use($app) {
  $app['monolog']->addDebug('logging output.');


  

  $fb_access_token = 'EAAJHBJ6XrHcBAK0KdOBRCGJQ2jJUvFPVtphbx4wa5zTtmtWmbJDj7vtAalZCbHEcST72LGi2EnIJ4MzeZBZCjBz8eJwksBMvE0sxAw5vaYLck8gV0H3E955qH9egDaMnDllasL2r8tOJCtZBgqCehsXrXgv9wNHphM8Jlt4SeSTYA8H6AlpseymgjIK2gVIZD';
  $fb = new Facebook([
    // 'app_id' => '641035119602807',
    // 'app_secret' => '3e08aeec01868f5292d0bb95da157cc1',
    'default_graph_version' => 'v3.1',
    // 'default_access_token' => $fb_access_token, // optional
  ]);
  
  try {
    $me_response = $fb->get('/me?fields=id,name', $fb_access_token);
  } catch(FacebookResponseException $e) {
    $jsonResponse = new JsonResponse(['error'=> 'response']);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  } catch(FacebookSDKException $e) {
    $jsonResponse = new JsonResponse(['error'=> 'sdk']);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  }
  
  $me = $me_response->getGraphUser();

  /* try {
    $arr = ['message' => 'Testing Post for our new tutorial. Graph API.'];

    $post_response = $fb->post('/me/feed/', $arr,	$fb_access_token);
  } catch(FacebookResponseException $e) {
    $jsonResponse = new JsonResponse(['error'=> $e->getMessage()]);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  } catch(FacebookSDKException $e) {
    $jsonResponse = new JsonResponse(['error'=> $e->getMessage()]);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  } */

  /* try {
		$data = ['source' => $fb->fileToUpload(__DIR__.'/images/DpjINq9W4AIJtM-.jpg'), 'message' => 'Good day 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture'];
		$photo_response = $fb->post('/me/photos', $data,$fb_access_token);
		$photo_graph_response = $photo_response->getGraphNode()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
    $jsonResponse = new JsonResponse(['error'=> $e->getMessage()]);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
    $jsonResponse = new JsonResponse(['error'=> $e->getMessage()]);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
	} */



  try {
		$data = ['source' => $fb->videoToUpload(__DIR__.'/video/032bad5d-5a13-4d4d-886c-2e887eb60f61.mp4'), 'description' => 'Good day 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture'];
		$photo_response = $fb->post('/me/videos', $data,$fb_access_token);
		$photo_graph_response = $photo_response->getGraphNode()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
    $jsonResponse = new JsonResponse(['error'=> $e->getMessage()]);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
    $jsonResponse = new JsonResponse(['error'=> $e->getMessage()]);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
	}

  

  


  $jsonResponse = new JsonResponse( ['success'=> 'success', 'name' => $me->getName(),  'id' => $me->getId() ] );
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
});

$app->get('/twitter', function() use($app) {
  $app['monolog']->addDebug('logging output.');


  

  $access_token = '106577396-BIQ9ow7hKEYzvOvFZen4NhBwYeV24inolyugdiLH';
  $access_token_secret = 'gl8s9FKBTorohm030PZGvFmPMBswCKHWs7wrHZFbkKXZS';
  $consumer_key = 'KvdIHDyqq1a4yPKSE6nQk2npW';
  $consumer_secret = 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b';


  $tw = new TwitterApi([
    'access_token' => $access_token,
    'access_token_secret' => $access_token_secret,
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_secret 
  ]);
  
  /* $connection = new TwitterOAuth('KvdIHDyqq1a4yPKSE6nQk2npW', 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b', $access_token, $access_token_secret);
  $media1 = $connection->upload('media/upload', ['media' => __DIR__.'/images/DpjINq9W4AIJtM-.jpg']);


  $media2 = $connection->upload('media/upload', ['media' => __DIR__.'/video/032bad5d-5a13-4d4d-886c-2e887eb60f61.mp4', 'media_type' => 'video/mp4'], true);

  $post = ['media_ids' => $media2->media_id_string, 
          'status' => "Good morning 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture"];


  $result = $connection->post('statuses/update', $post); */

  // $result = $tw->postText('hi this a post test');

  try {
    // $result = $tw->postText('hi this a post test');
    $result = $tw->postVideo('hi this a post test', [__DIR__.'/video/032bad5d-5a13-4d4d-886c-2e887eb60f61.mp4']);
  } catch(Exception $e) {
    $jsonResponse = new JsonResponse(['error'=> 'api error']);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  }

  

  


  $jsonResponse = new JsonResponse($result);
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
});


$app->get('/twitter/delete', function() use($app) {
  $app['monolog']->addDebug('logging output.');


  

  $access_token = '106577396-BIQ9ow7hKEYzvOvFZen4NhBwYeV24inolyugdiLH';
  $access_token_secret = 'gl8s9FKBTorohm030PZGvFmPMBswCKHWs7wrHZFbkKXZS';
  $consumer_key = 'KvdIHDyqq1a4yPKSE6nQk2npW';
  $consumer_secret = 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b';


  $tw = new TwitterApi([
    'access_token' => $access_token,
    'access_token_secret' => $access_token_secret,
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_secret 
  ]);
  
  /* $connection = new TwitterOAuth('KvdIHDyqq1a4yPKSE6nQk2npW', 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b', $access_token, $access_token_secret);
  $media1 = $connection->upload('media/upload', ['media' => __DIR__.'/images/DpjINq9W4AIJtM-.jpg']);


  $media2 = $connection->upload('media/upload', ['media' => __DIR__.'/video/032bad5d-5a13-4d4d-886c-2e887eb60f61.mp4', 'media_type' => 'video/mp4'], true);

  $post = ['media_ids' => $media2->media_id_string, 
          'status' => "Good morning 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture"];


  $result = $connection->post('statuses/update', $post); */

  // $result = $tw->postText('hi this a post test');

  try {
    // $result = $tw->postText('hi this a post test');
    $result = $tw->deleteTweet('1094261506219352067');
  } catch(Exception $e) {
    $jsonResponse = new JsonResponse(['error'=> 'api error']);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  }

  

  


  $jsonResponse = new JsonResponse($result);
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
});

$app->get('/twitter/search/{from}/{tag}', function( $from, $tag) use($app) {
  $app['monolog']->addDebug('logging output.');

  $sql = "SELECT * FROM Tweets";
  $post = $app['db']->fetchAssoc($sql);

  $app['monolog']->addDebug($post);




  

  $access_token = '106577396-BIQ9ow7hKEYzvOvFZen4NhBwYeV24inolyugdiLH';
  $access_token_secret = 'gl8s9FKBTorohm030PZGvFmPMBswCKHWs7wrHZFbkKXZS';
  $consumer_key = 'KvdIHDyqq1a4yPKSE6nQk2npW';
  $consumer_secret = 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b';


  $tw = new TwitterApi([
    'access_token' => $access_token,
    'access_token_secret' => $access_token_secret,
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_secret 
  ]);
  
  /* $connection = new TwitterOAuth('KvdIHDyqq1a4yPKSE6nQk2npW', 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b', $access_token, $access_token_secret);
  $media1 = $connection->upload('media/upload', ['media' => __DIR__.'/images/DpjINq9W4AIJtM-.jpg']);


  $media2 = $connection->upload('media/upload', ['media' => __DIR__.'/video/032bad5d-5a13-4d4d-886c-2e887eb60f61.mp4', 'media_type' => 'video/mp4'], true);

  $post = ['media_ids' => $media2->media_id_string, 
          'status' => "Good morning 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture"];


  $result = $connection->post('statuses/update', $post); */

  // $result = $tw->postText('hi this a post test');

  try {
    // $result = $tw->postText('hi this a post test');
    $result = $tw->searchTweet($from, $tag);
  } catch(Exception $e) {
    $jsonResponse = new JsonResponse(['error'=> 'api error']);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  }

  

  


  $jsonResponse = new JsonResponse($result);
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
});



$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');


  

  

  return $app['twig']->render('index.twig');
});

$app->run();

?>
