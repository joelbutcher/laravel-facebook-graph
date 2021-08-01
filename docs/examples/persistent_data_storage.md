# Persistent Data Storage

By default, requests to Facebook's OAuth system are stateful. In order to store the data, you will need to register a persistent data handler with the container.

To register a persistent data handler to be used, add the following to the `register` method of your `AppServiceProvider`

```php
$this->app->singleton(PersistentDataInterface::class, fn ($app) => $app[CachePersistentDataHandler::class])
```

The following class uses Laravel's default `CacheManager` in order to store and retreive the `state` query parameter:

```php
<?php

namespace App\Facebook;

use Facebook\PersistentData\PersistentDataInterface;
use Illuminate\Cache\CacheManager;

class CachePersistentDataHandler implements PersistentDataInterface
{
    protected $cache;
    public function __construct(CacheManager $cache)
    {
        $this->cache = $cache;
    }

    public function get($key)
    {
        if ($value = $this->cache->pull($key)) {
            $this->cache->forget($key);
        }

        return $value;
    }

    public function set($key, $value)
    {
        $this->cache->put($key, $value);
    }
}
```
