<?php

namespace JoelButcher\Facebook\Traits;

use Facebook\Helper\RedirectLoginHelper;

/**
 * @property array $config
 *
 * @method \Facebook\Facebook getFacebook()
 *
 * @see \JoelButcher\Facebook\Facebook
 */
trait HandlesAuthentication
{
    /**
     * The login helper used to get OAuth redirect urls.
     *
     * @var \Facebook\Helper\RedirectLoginHelper|null
     */
    protected $loginHelper = null;

    /**
     * Get the redirect login helper instance.
     *
     * @return \Facebook\Helper\RedirectLoginHelper
     *
     * @throws \Facebook\Exception\SDKException
     */
    public function getLoginHelper(): ?RedirectLoginHelper
    {
        if (! $this->loginHelper) {
            $this->loginHelper = $this->getFacebook()->getRedirectLoginHelper();
        }

        return $this->loginHelper;
    }

    /**
     * Generate a redirect URL.
     *
     * @param  string|null  $redirectUrl
     * @param  array  $scopes
     * @return string
     *
     * @throws \Facebook\Exception\SDKException
     */
    public function getRedirect(?string $redirectUrl = null, array $scopes = []): string
    {
        $url = $redirectUrl ?? $this->config['redirect_uri'] ?? null;

        if (! $url) {
            throw new \InvalidArgumentException('A valid redirect URL is required');
        }

        $scopes = array_merge($this->config['scopes'] ?? [], $scopes, [
            'email', 'public_profile',
        ]);

        return $this->getLoginHelper()->getLoginUrl($url, $scopes);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     * @throws \Facebook\Exception\SDKException
     */
    public function __call($method, $parameters)
    {
        if (! is_callable([$this->getLoginHelper(), $method])) {
            throw new \BadMethodCallException(sprintf(
                'Method %s::%s does not exist.',
                get_class($this->getLoginHelper()),
                $method
            ));
        }

        return $this->getLoginHelper()->$method(...$parameters);
    }
}
