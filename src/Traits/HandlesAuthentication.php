<?php

namespace JoelButcher\Facebook\Traits;

use Facebook\Helper\RedirectLoginHelper;

/**
 * @property array $config
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
     */
    public function getLoginHelper(): ?RedirectLoginHelper
    {
        if (! $this->loginHelper) {
            $this->loginHelper = $this->facebook->getRedirectLoginHelper();
        }

        return $this->loginHelper;
    }

    /**
     * Generate a redirect URL.
     *
     * @param  string|null  $redirectUrl
     * @param  array  $scopes
     * @return string
     */
    public function getRedirect(?string $redirectUrl = null, array $scopes = []): string
    {
        $url = $redirectUrl ?? $this->config['redirect'] ?? null;

        if (! $url) {
            throw new \InvalidArgumentException('A valid redirect URL is required');
        }

        $scopes = !empty($scopes) ? $scopes : $this->config['scopes'] ?? ['email', 'public_profile'];

        return $this->getLoginHelper()->getLoginUrl($url, $scopes);
    }
}
