<?php


namespace Demo\Api;


use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterApi
{

    
    const ACCESS_TOKEN = 'ACCESS_TOKEN';

    
    const ACCESS_TOKEN_SECRET = 'ACCESS_TOKEN_SECRET';

    const CONSUMER_KEY = 'CONSUMER_KEY';

    
    const CONSUMER_SECRET = 'CONSUMER_SECRET';


   
    protected $access_token;

    
    protected $access_token_secret;

    protected $consumer_key;
    
    protected $consumer_secret;

    protected $connection;


    public function __construct(array $config = [])
    {
        $config = array_merge([
            'access_token' => getenv(static::ACCESS_TOKEN),
            'access_token_secret' => getenv(static::ACCESS_TOKEN_SECRET),
            'consumer_key' => getenv(static::CONSUMER_KEY),
            'consumer_secret' => getenv(static::CONSUMER_SECRET)
        ], $config);

        if (!$config['access_token']) {
            throw new \Exception('Required "access_token" key not supplied in config and could not find fallback environment variable "' . static::ACCESS_TOKEN . '"');
        }

        if (!$config['access_token_secret']) {
            throw new \Exception('Required "access_token_secret" key not supplied in config and could not find fallback environment variable "' . static::ACCESS_TOKEN_SECRET . '"');
        }

        if (!$config['consumer_key']) {
            throw new \Exception('Required "consumer_key" key not supplied in config and could not find fallback environment variable "' . static::CONSUMER_KEY . '"');
        }

        if (!$config['consumer_secret']) {
            throw new \Exception('Required "consumer_secret" key not supplied in config and could not find fallback environment variable "' . static::CONSUMER_SECRET . '"');
        }

        $this->access_token = $config['access_token'];
        $this->access_token_secret = $config['access_token_secret'];
        $this->consumer_key = $config['consumer_key'];
        $this->consumer_secret = $config['consumer_secret'];

        $this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret);
    }

    public function postText($status)
    {
        

        $post = ['status' => $status];


        $result = $this->connection->post('statuses/update', $post);

        return [ 'result' => $result, 'code' => $this->connection->getLastHttpCode()];
    }

    public function postPhoto($status, $media_list = [])
    {
        $media_id_list = [];

        foreach ($media_list as $item){
            $media = $this->connection->upload('media/upload', ['media' => $item]);
            if ($this->connection->getLastHttpCode() == 200) {

            } else{
                throw new \Exception('Error occured');
            }
            array_push($media_list,$media->media_id_string);
        }

        $post = [
            'status' => $status,
            'media_ids' => implode(',', media_id_list)
        ];       

        

        $result = $this->connection->post('statuses/update', $post);

        if ($this->connection->getLastHttpCode() == 200) {

            return $result;

        } else{
            throw new \Exception('Error occured');
        }
    }

    public function postVideo($status, $media_list = [])
    {
        
        $media_id_list = [];

        foreach ($media_list as $item){
            $media = $this->connection->upload('media/upload', ['media' => $item, 'media_type' => 'video/mp4'], true);

            if ($this->connection->getLastHttpCode() == 200) {

            } else{
                throw new \Exception('Error occured');
            }
            array_push($media_list,$media->media_id_string);
        }

        $post = [
            'status' => $status,
            'media_ids' => implode(',', media_id_list)
        ];       

        

        $result = $this->connection->post('statuses/update', $post);

        if ($this->connection->getLastHttpCode() == 200) {

            return $result;

        } else{
            throw new \Exception('Error occured');
        }

    }


}