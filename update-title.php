<?php

class UpdateYoutubeVideo {

    /**
     * Config
     *
     * @var array
     */
    private $_config = [];

    /**
     * Acess token used to make api calls
     *
     * @var string
     */
    protected $_access_token;

    /**
     * UpdateYoutubeVideo constructor.
     */
    public function __construct() {
        $this->_config = include 'config.php';

        $this->_access_token = $this->setAccessToken();
    }

    /**
     * Get Video stats from youtube
     *
     * @return mixed
     */
    public function getVideoData() {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer ".$this->_access_token."\r\n" . "Content-Type: application/json\r\n",
            ]
        ]);

        $result = file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id='.$this->_config["video_id"].'&key='.$this->_config["api_key"], false, $context);

        $data = json_decode($result);

        return $data->items[0]->statistics;
    }

    /**
     * Run script and set video title
     *
     * @return false|string
     */
    public function run() {

        $videoStatistics = $this->getVideoData();

        $title = "This video has ".$videoStatistics->viewCount." views and ".$videoStatistics->likeCount." likes.";

        $data = json_encode([
            'id' => $this->_config["video_id"],
            'snippet' => [
                'title' => $title,
                'categoryId' => '27'
            ]
        ]);

        $context  = stream_context_create([
            'http' => [
                'method' => 'PUT',
                'header' => "Authorization: Bearer ".$this->_access_token."\r\n" . "Content-Length: " . strlen($data) . "\r\n" . "Content-Type: application/json\r\n",
                'content' => $data,
            ]
        ]);

        return file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cstatus%2Clocalizations&key='.$this->_config["api_key"], false, $context);
    }

    /**
     * Set access token for script to run
     *
     * @return mixed
     */
    private function setAccessToken() {
        $postdata = http_build_query(
            [
                'client_id' =>  $this->_config["client_id"],
                'client_secret' =>  $this->_config["client_secret"],
                'refresh_token' => $this->_config["refresh_token"],
                'grant_type' => "refresh_token"
            ]
        );

        $context  = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($postdata) . "\r\n",
                'content' => $postdata
            ]
        ]);

        $results = file_get_contents('https://www.googleapis.com/oauth2/v4/token', false, $context);

        return json_decode($results)->access_token;
    }
}

$update = new UpdateYoutubeVideo();
$update->run();