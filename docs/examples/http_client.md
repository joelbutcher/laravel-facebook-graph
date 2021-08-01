# HTTP Client

The Facebook Graph SDK uses [HTTPlug](http://httplug.io/) for sending and recieving requests. If you wish to use a custom HTTP Client implementation, you may do so by registering it in the `register` method of your `AppServiceProvider`

```php
$this->app->singleton(\Http\Client\HttpClient::class, fn ($app) => $app[MyHttpClient::class])
```

The class below constructs an example custom `HttpClient` implementation using Laravel's HTTP Factory:

```php
<?php

namespace App\Http;

use Http\Client\HttpClient;
use Illuminate\Http\Client\Factory;
use Psr\Http\Message\RequestInterface;

class Client implements HttpClient
{
    protected $factory;
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }
    /**
     * Sends a PSR-7 request.
     *
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendRequest(RequestInterface $request)
    {
        return $this->factory->send($request->getMethod(), $request->getUri(), [
            'headers' => $request->getHeaders(),
        ])->toPsrResponse();
    }
}

```
