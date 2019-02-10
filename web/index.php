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
  'db.options' => array (
    // 'driver'    => 'pdo_mysql',
    'url'      => getenv('CLEARDB_DATABASE_URL'),
    // 'dbname'    => 'heroku_33f91a4bb90418c',
    // 'charset'   => 'utf8mb4',
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

$app->get('/twitter/tweet', function() use($app) {
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

  try {
    $result = $tw->postVideo('hi this a post test', [__DIR__.'/video/032bad5d-5a13-4d4d-886c-2e887eb60f61.mp4']);
    // var_dump($result);
    $app['monolog']->addDebug($result->result->id_str);


    // $sql = "INSERT INTO Tweets (tweet_id, created_date, type) VALUES (?, ?, ?)";
    // $post = $app['db']->executeUpdate($sql, array($result["id_str"], date('Y-m-d H:i:s'), 'TW' ));

  } catch(Exception $e) {
    print_r($e);
    $jsonResponse = new JsonResponse($e);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  }

  

  


  $jsonResponse = new JsonResponse($result);
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
});


$app->get('/twitter/delete/{id}', function($id) use($app) {
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

  try {
    $result = $tw->deleteTweet($id);
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

  $app['monolog']->addDebug(json_encode($post));




  

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

  try {
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


$app->get('/twitter/retweet/{id}', function( $id) use($app) {
  $app['monolog']->addDebug('logging output.');

  $sql = "SELECT * FROM Tweets";
  $post = $app['db']->fetchAssoc($sql);

  $app['monolog']->addDebug(json_encode($post));




  

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

  try {
    $result = $tw->reTweet($id);
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


  

  

  // return $app['twig']->render('index.twig');
  $jsonResponse = new JsonResponse(array('app'=> 'twitter automation'));
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
});

$app->run();

?>
