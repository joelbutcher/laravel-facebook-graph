<?php

namespace JoelButcher\Facebook;

use Facebook\Facebook as Base;
use Facebook\GraphNode\GraphUser;
use Illuminate\Support\Traits\ForwardsCalls;
use JoelButcher\Facebook\Traits\HandlesAuthentication;
use JoelButcher\Facebook\Traits\MakesFacebookRequests;

/**
 * @method \Facebook\PersistentData\PersistentDataInterface getPersistentDataHandler()
 * @method \Facebook\Url\UrlDetectionInterface getUrlDetectionHandler()
 * @method string getLoginUrl($redirectUrl, array $scope = [], $separator = '&')
 * @method string getLogoutUrl($accessToken, $next, $separator = '&')
 * @method string getReRequestUrl($redirectUrl, array $scope = [], $separator = '&')
 * @method string getReAuthenticationUrl($redirectUrl, array $scope = [], $separator = '&')
 * @method string getAccessToken($redirectUrl = null)
 *
 * @see \Facebook\Helper\RedirectLoginHelper
 */
class Facebook
{
    use ForwardsCalls;
    use HandlesAuthentication {
        __call as authenticationCall;
    }

    use MakesFacebookRequests {
        __call as facebookCall;
    }

    /**
     * The configuration options for the Facebook instance.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The Facebook App instance.
     *
     * @var \Facebook\Facebook
     */
    protected $facebook;

    /**
     * Create a new Facebook wrapper.
     *
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get the base Facebook instance.
     *
     * @return \Facebook\Facebook
     *
     * @throws \Facebook\Exception\SDKException
     */
    public function getFacebook(): Base
    {
        if (! $this->facebook) {
            $this->facebook = new Base($this->config);
        }

        return $this->facebook;
    }

    /**
     * Get a graph user instance after an authenticated request.
     *
     * @param  array  $params
     * @return \Facebook\GraphNode\GraphUser|null
     *
     * @throws \Facebook\Exception\SDKException
     */
    public function getUser(array $params = []): ?GraphUser
    {
        return $this->get('/me', $params)->getGraphUser();
    }

    /**
     * Handle dynamic calls into the login helper, or forward them to the Facebook SDK.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \Facebook\Exception\SDKException
     */
    public function __call($method, $parameters)
    {
        if (is_callable([$this->getLoginHelper(), $method])) {
            return $this->authenticationCall($method, $parameters);
        }

        return $this->forwardCallTo($this->getFacebook(), $method, $parameters);
    }
}
