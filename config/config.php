<?php

return [
    'app_id' => env('FACEBOOK_APP_ID'),
    'app_secret' => env('FACEBOOK_APP_SECRET'),
    'redirect_uri' => env('FACEBOOK_REDIRECT_URI'),
    'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v11.0'),
    'beta_mode' => env('FACEBOOK_ENABLE_BETA', false),
];
