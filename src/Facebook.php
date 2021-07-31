<?php

namespace JoelButcher\Facebook;

use Facebook\Facebook as Base;
use Facebook\GraphNode\GraphUser;
use Illuminate\Support\Traits\ForwardsCalls;
use JoelButcher\Facebook\Traits\HandlesAuthentication;
use JoelButcher\Facebook\Traits\MakesFacebookRequests;

class Facebook
{
    use ForwardsCalls;
    use HandlesAuthentication;
    use MakesFacebookRequests;

    /**
     * The Facebook App instance.
     *
     * @var \Facebook\Facebook
     */
    protected $facebook;

    /**
     * Create a new Facebook wrapper.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->facebook = new Base($config);
    }

    /**
     * Get a graph user instance after an authenticated request.
     *
     * @param  array  $params
     * @param  string|null  $eTag
     * @param  string|null  $graphVersion
     * @return \Facebook\GraphNode\GraphUser|null
     */
    public function getUser(array $params = []): ?GraphUser
    {
        return $this->get('/me', $params)->getGraphUser();
    }
}
