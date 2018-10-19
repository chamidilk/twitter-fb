<?php


namespace Demo\Api;



use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookApi
{

    
    const APP_ID = 'APP_ID';

    
    const APP_SECRET = 'APP_SECRET';

    const FB_ACCESS_TOKEN = 'FB_ACCESS_TOKEN';


   
    protected $app_id;

    
    protected $app_secret;

    protected $fb_access_token;

    protected $fb;


    public function __construct(array $config = [])
    {
        $config = array_merge([
            'app_id' => getenv(static::APP_ID),
            'app_secret' => getenv(static::APP_SECRET),
            'fb_access_token' => getenv(static::FB_ACCESS_TOKEN)
        ], $config);

        if (!$config['app_id']) {
            throw new \Exception('Required "app_id" key not supplied in config and could not find fallback environment variable "' . static::APP_ID . '"');
        }

        if (!$config['app_secret']) {
            throw new \Exception('Required "app_secret" key not supplied in config and could not find fallback environment variable "' . static::APP_SECRET . '"');
        }

        if (!$config['fb_access_token']) {
            throw new \Exception('Required "fb_access_token" key not supplied in config and could not find fallback environment variable "' . static::FB_ACCESS_TOKEN . '"');
        }

        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];
        $this->fb_access_token = $config['fb_access_token'];

        $this->fb = new Facebook([
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => 'v3.1'
        ]);        
    }

    public function postText($message)
    {
        

        try {
            $arr = ['message' => $message];
        
            $post_response = $this->fb->post('/me/feed/', $arr,	$this->fb_access_token);
            return [ 'result' => $post_response->getGraphNode()->asArray()];

        } catch(FacebookResponseException $e) {
            return [ 'error' => $e->getMessage()];
        } catch(FacebookSDKException $e) {
            return [ 'error' => $e->getMessage()];
        }
    }

    public function postPhoto($message, $media)
    {
        try {
            $data = ['source' => $this->fb->fileToUpload($media), 'message' => $message];
            $photo_response = $this->fb->post('/me/photos', $data,$this->fb_access_token);
            return [ 'result' => $photo_response->getGraphNode()->asArray()];
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return [ 'error' => $e->getMessage()];        
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return [ 'error' => $e->getMessage()];        
        }
    }


    public function postPhotos($message, $media_list = [])
    {
        $this->fb->setDefaultAccessToken($this->fb_access_token);

        $batch = [];
        foreach ($media_list as $key=>$item){
            array_push($batch,[]);

            $batch['photo-'.$key] = $this->fb->request('POST', '/me/photos', [
                'source' => $this->fb->fileToUpload($item),
                'published' => false
                ]);
        }

        
        try {
            $responses = $this->fb->sendBatchRequest($batch);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return [ 'error' => $e->getMessage()]; 
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return [ 'error' => $e->getMessage()]; 
        }

        $result = [];
        $media_ids = [];

        foreach ($responses as $key => $response) {
            if ($response->isError()) {
                $e = $response->getThrownException();
                array_push($result, $e->getResponse());
            } else {
                array_push($result, $response->getBody());
                array_push($media_ids, json_decode($response->getBody())->{'id'});
            }
        }

        $data_post = [];


        foreach ($media_ids as $key=>$media_id):
            $data_post['attached_media['.$key.']'] = '{"media_fbid":"'.$media_id.'"}';
        endforeach;
        
        $data_post['message'] = $message;
        
        $response = $this->fb->sendRequest('POST', "/me/feed", $data_post, $this->fb_access_token);
        
        $post_id = $response->getGraphNode()['id'];

        return $post_id;
    }

    public function postVideo($description, $media)
    {
        try {
            $data = ['source' => $this->fb->videoToUpload($media), 'description' => $description];
            $video_response = $this->fb->post('/me/videos', $data,$this->fb_access_token);
            return [ 'result' => $video_response->getGraphNode()->asArray()];
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return [ 'error' => $e->getMessage()];        
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return [ 'error' => $e->getMessage()];        
        }
        

    }


}