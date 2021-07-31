<?php

namespace JoelButcher\Facebook\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getRedirect(string|null $redirectUrl = null, array $scopes = [])
 * @method static \Facebook\Helpers\FacebookRedirectLoginHelper getLoginHelper()
 * @method static void setETag(string $eTag)
 * @method static void setGraphVersion(string $graphVersion)
 * @method static \Facebook\Response get(string $endpoint, array $params = [])
 * @method static \Facebook\Response post(string $endpoint, array $params = [])
 * @method static \Facebook\Response delete(string $endpoint, array $params = [])
 * @method static \Facebook\Response send(string $method, string $endpoint, array $params = [])
 * @method static string getToken()
 * @method static \Facebook\GraphNode\GraphUser|null getUser(array $params = [])
 *
 * @see \JoelButcher\Facebook\Facebook
 */
class Facebook extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'facebook-graph';
    }
}
