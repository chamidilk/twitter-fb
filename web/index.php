<?php

require('../vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

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

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');


  

  $post = ['media_ids' => $media1->media_id_string, 
          'status' => "Good morning 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture"];


  $access_token = '106577396-BIQ9ow7hKEYzvOvFZen4NhBwYeV24inolyugdiLH';
  $access_token_secret = 'gl8s9FKBTorohm030PZGvFmPMBswCKHWs7wrHZFbkKXZS';
  
  $connection = new TwitterOAuth('KvdIHDyqq1a4yPKSE6nQk2npW', 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b', $access_token, $access_token_secret);
  // $content = $connection->get("account/verify_credentials"); 
  $media1 = $connection->upload('media/upload', ['media' => __DIR__.'/images/DpjINq9W4AIJtM-.jpg']);

  $result = $connection->post('statuses/update', $post);

  

  


  $jsonResponse = new JsonResponse($media1);
  $jsonResponse->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);


  return $jsonResponse;
  
  
  
  // $log->addWarning( $content);

  // return $app['twig']->render('index.twig');
});

$app->run();

?>
