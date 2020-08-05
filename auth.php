<?php

class Auth {

    /**
     * Config
     *
     * @var array
     */
    private $_config = [];

    /**
     * Auth constructor.
     */
    public function __construct() {
        $this->_config = include 'config.php';
    }

    /**
     * Init oAuth by redirecting to google
     */
    public function init() {
        $url = "https://accounts.google.com/o/oauth2/v2/auth?scope=".$this->_config["scope"]."&prompt=consent&access_type=offline&include_granted_scopes=true&response_type=code&state=state_parameter_passthrough_value&redirect_uri=".$this->_config["redirect_uri"]."&client_id=".$this->_config["client_id"];

        header('Location: '.$url, true);
    }

    /**
     * do auth to get refresh token after receiving code
     */
    public function auth() {
        $postdata = http_build_query([
            'code' => $_GET["code"],
            'client_id' =>  $this->_config["client_id"],
            'client_secret' =>  $this->_config["client_secret"],
            'redirect_uri' => $this->_config["redirect_uri"],
            'grant_type' => "authorization_code"
        ]);

        $context  = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($postdata) . "\r\n",
                'content' => $postdata
            ]
        ]);

        $this->outputCode(file_get_contents('https://oauth2.googleapis.com/token', false, $context));
    }

    /**
     * Output refresh token to place in config.php
     *
     * @param $result
     */
    public function outputCode($result) {
        $data = json_decode($result);

        echo "PUT THIS IN refresh_token in config.php '<b>".$data->refresh_token."</b>'";
    }
}

$auth = new Auth();
if (isset($_GET["code"])) {
    $auth->auth();
} else {
    $auth->init();
}