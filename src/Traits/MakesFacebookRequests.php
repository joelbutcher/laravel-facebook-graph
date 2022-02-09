<?php

namespace JoelButcher\Facebook\Traits;

use Facebook\Response;

/**
 * @method \Facebook\Facebook getFacebook()
 * @method \Facebook\Helper\RedirectLoginHelper getLoginHelper()
 *
 * @see \JoelButcher\Facebook\Facebook
 * @see \JoelButcher\Facebook\Traits\HandlesAuthentication
 */
trait MakesFacebookRequests
{
    /**
     * The eTag to send with requests, if set.
     *
     * @var string|null
     */
    protected $eTag = null;

    /**
     * The Facebook Graph version to use, if set.
     *
     * @var string|null
     */
    protected $graphVersion = null;

    /**
     * The users access token.
     *
     * @var string|null
     */
    protected $token = null;

    /**
     * The Facebook Response instance, if set.
     *
     * @var \Facebook\Response|null
     */
    protected $response = null;

    /**
     * Set the eTag to be sent with the request.
     *
     * @param  string  $eTag
     * @return void
     */
    public function setETag(string $eTag): void
    {
        $this->eTag = $eTag;
    }

    /**
     * Set the Facebook Graph version to be sent with the request.
     *
     * @param  string  $graphVersion
     * @return void
     */
    public function setGraphVersion(string $graphVersion): void
    {
        $this->graphVersion = $graphVersion;
    }

    /**
     * Sends a GET request to Graph and returns the result.
     *
     * @param  string  $endpoint
     * @param  array  $params
     * @return \Facebook\Response
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function get(string $endpoint, array $params = []): Response
    {
        return $this->send('GET', $endpoint, $params);
    }

    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param  string  $endpoint
     * @param  array  $params
     * @return \Facebook\Response
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function post(string $endpoint, array $params = []): Response
    {
        return $this->send('POST', $endpoint, $params);
    }

    /**
     * Sends a DELETE request to Graph and returns the result.
     *
     * @param  string  $endpoint
     * @param  array  $params
     * @return \Facebook\Response
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function delete(string $endpoint, array $params = []): Response
    {
        return $this->send('DELETE', $endpoint, $params);
    }

    /**
     * Sends a request to Graph and returns the result.
     *
     * @param  string  $method
     * @param  string  $endpoint
     * @param  array  $params
     * @return \Facebook\Response
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function send(string $method, string $endpoint, array $params = []): Response
    {
        $this->response = $this->getFacebook()->sendRequest(
            $method,
            $endpoint,
            $params,
            $this->getToken(),
            $this->eTag,
            $this->graphVersion ?? $this->getFacebook()->getDefaultGraphVersion()
        );

        return $this->response;
    }

    /**
     * Get the Facebook response, if set.
     *
     * @return \Facebook\Response
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Get the token to use in authenticated user requests.
     *
     * @return string|null
     *
     * @throws \Facebook\Exception\SDKException
     */
    public function getToken(): ?string
    {
        if (! $this->token) {
            $accessToken = $this->getLoginHelper()->getAccessToken();

            if ($accessToken) {
                $this->token = $accessToken->getValue();
            }
        }

        return $this->token;
    }
}
