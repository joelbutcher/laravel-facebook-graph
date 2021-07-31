<?php

return [
    'client_id' => env('FACEBOOK_APP_ID'),
    'client_secret' => env('FACEBOOK_APP_SECRET'),
    'enable_beta_mode' => env('FACEBOOK_ENABLE_BETA', false),
    'default_graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v10'),
];
