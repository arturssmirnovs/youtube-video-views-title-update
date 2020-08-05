<?php

return [
    "api_key" => "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", // google console API key
    "video_id" => "xxxxxxxxxxx", // youtube video ID

    "client_id" => "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com", // google console client id
    "client_secret" => "xxxxxxxxxxxxxxxxxxxxxxxx", // google console client secret
    "redirect_uri" => "http://localhost/youtube-video-views-title-update/auth.php", // redirect url for oAuth
    "scope" => "https://www.googleapis.com/auth/youtube", // scope for oAuth, don't change this

    "refresh_token" => "", // Refresh token got from auth.php
];