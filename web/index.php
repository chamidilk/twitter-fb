<?php

require('../vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

$app = new Silex\Application();
$app['debug'] = true;

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
  $access_token = '106577396-BIQ9ow7hKEYzvOvFZen4NhBwYeV24inolyugdiLH';
  $access_token_secret = 'gl8s9FKBTorohm030PZGvFmPMBswCKHWs7wrHZFbkKXZS';
  
  $connection = new TwitterOAuth('KvdIHDyqq1a4yPKSE6nQk2npW', 'fv2wCYK86w4Pxd8YYhOytxLM8z7vV9krKqtDw2R1fp4tnLkp7b', $access_token, $access_token_secret);
  $content = $connection->get("account/verify_credentials");
  
  
  
  echo $content;


  return $app['twig']->render('index.twig');
});

$app->run();

?>
