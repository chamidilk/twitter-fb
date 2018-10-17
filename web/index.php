<?php

require('../vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

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

// Our web handlers

$app->get('/fb', function() use($app) {
  $app['monolog']->addDebug('logging output.');


  

  $fb_access_token = 'EAAJHBJ6XrHcBAPyK3u8aan4h8ON6QvD8dWJuA7dKike6KFaDhqqvvXR3v5TyItZADu6CZAuZBgUGznMZBaDI36xjqZAxSQ77utEhvlZAXAnBUYY90tWKZAkVCstoN1sngkqdvvJaeTvc2DKZCkaB78sdkDTie8AZBZAOVg3ZBrbv4ZC2Jbql6gW2u4PF';

  $fb = new Facebook([
    // 'app_id' => '641035119602807',
    // 'app_secret' => '3e08aeec01868f5292d0bb95da157cc1',
    // 'default_graph_version' => 'v2.3',
    // 'default_access_token' => $fb_access_token, // optional
  ]);
  
  try {
    $response = $fb->get('/me?fields=id,name', $fb_access_token);
  } catch(FacebookResponseException $e) {
    $jsonResponse = new JsonResponse(['error'=> 'response']);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  } catch(FacebookSDKException $e) {
    $jsonResponse = new JsonResponse(['error'=> 'sdk']);
    $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);
    return $jsonResponse;
  }
  
  $me = $response->getGraphUser();

  

  


  $jsonResponse = new JsonResponse($response);
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
});

$app->get('/twitter', function() use($app) {
  $app['monolog']->addDebug('logging output.');


  

  $access_token = '106577396-BIQ9ow7hKEYzvOvFZen4NhBwYeV24inolyugdiLH';
  $access_token_secret = 'gl8s9FKBTorohm030PZGvFmPMBswCKHWs7wrHZFbkKXZS';
  
  $connection = new TwitterOAuth('KvdIHDyqq1a4yPKSE6nQk2npW', 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b', $access_token, $access_token_secret);
  $media1 = $connection->upload('media/upload', ['media' => __DIR__.'/images/DpjINq9W4AIJtM-.jpg']);


  $media2 = $connection->upload('media/upload', ['media' => __DIR__.'/video/032bad5d-5a13-4d4d-886c-2e887eb60f61.mp4', 'media_type' => 'video/mp4'], true);

  $post = ['media_ids' => $media2->media_id_string, 
          'status' => "Good morning 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture"];


  $result = $connection->post('statuses/update', $post);

  

  


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
