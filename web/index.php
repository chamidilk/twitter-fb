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


  

  $fb_access_token = 'EAAJHBJ6XrHcBAK0KdOBRCGJQ2jJUvFPVtphbx4wa5zTtmtWmbJDj7vtAalZCbHEcST72LGi2EnIJ4MzeZBZCjBz8eJwksBMvE0sxAw5vaYLck8gV0H3E955qH9egDaMnDllasL2r8tOJCtZBgqCehsXrXgv9wNHphM8Jlt4SeSTYA8H6AlpseymgjIK2gVIZD';
// $fb_access_token = 'EAAJHBJ6XrHcBAGJQDhITUIKnV1vKx4pN3lZA9wk3xcQlZC7mVNAx6YaumuN8zzQIiLCOTbbH1YqapmWDfz8Qbcem9GgsMVSw6mUqjZCTT7pgYuD5nChpicgz8gndZAK9LAegk58dcHcz7sca5sHm96XpcmxaFmEyk1vVWclsmfDZBZC7tOS3hy';
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


  try {
		// message must come from the user-end
		$data = ['source' => $fb->fileToUpload(__DIR__.'/images/DpjINq9W4AIJtM-.jpg'), 'message' => 'Good morning 🤗 Can’t wait to hit the ground in The Hague! 🇳🇱 RT @BrandBaseNL We are under construction! 🛠🚧 Work in progress at the Malieveld in The Hague. Generation Discover Festival by @Shell and partners. #makethefuture'];
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
	}

  

  


  $jsonResponse = new JsonResponse( ['success'=> 'success', 'name' => $me->getName(),  'id' => $me->getId() ] );
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
