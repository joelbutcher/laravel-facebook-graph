<?php

namespace JoelButcher\Facebook\Tests\Fixtures;

use Facebook\Authentication\OAuth2Client;

class FooOAuth2Client extends OAuth2Client
{
    public function getAccessTokenFromCode($code, $redirectUri = '', $machineId = null)
    {
        return 'foo_token_from_code|'.$code.'|'.$redirectUri;
    }
}
