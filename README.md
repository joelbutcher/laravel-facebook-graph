# Laravel Facebook Graph SDK

<p align="center">
    <a href="https://github.com/joelbutcher/laravel-facebook-graph/actions">
        <img src="https://github.com/joelbutcher/laravel-facebook-graph/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/joelbutcher/laravel-facebook-graph">
        <img src="https://img.shields.io/packagist/dt/joelbutcher/laravel-facebook-graph" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/joelbutcher/laravel-facebook-graph">
        <img src="https://img.shields.io/packagist/v/joelbutcher/laravel-facebook-graph" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/joelbutcher/laravel-facebook-graph">
        <img src="https://img.shields.io/packagist/l/joelbutcher/laravel-facebook-graph" alt="License">
    </a>
</p>


## Installation

Getting started with Laravel Facebook Graph is easy - first, install the package via composer

```
composer require joelbutcher/laravel-facebook-graph
```

Then publish the package config:

```
php artisan vendor:publish --provider="JoelButcher\Facebook\FacebookServiceProvider"
```

This will add a `config/facebook.php` file to your project. Here you may configure the following options:

| Option  | Description |
| ------- | ------------- |
| `app_id` | Used to identify your app when requesting a users' access token |
| `app_secret` | The secret key used to authorize your app with Facebook |
| `redirect_url` | The destination URL to redirect users to, after authenticating with Facebook |
| `graph_version` | The graph version to target when making user-authenticated requests to the Facebook Graph API, defaults to v11.0 |
| `beta_mode` | Indicates whether or not to run a beta version of the SDK |

## URL Detection Handler
You may add a custom URL Detection handler, by binding a singleton your implementation in the `register` method of the `AppServiceProvider`:

```php
$this->app->singleton(UrlDetectionInterface::class, fn ($app) => $app[UrlDetectionHandler::class])
```

## Persistent Data Handlers

In order to store the `state` for OAuth requests to Facebook, you will either need to register a persistent data handler. You can find an example of how to do this [here](./docs/examples/persistent_data_storage.md)

## HTTP Client
The current version of the Facebook Graph SDK (v6) uses [HTTPlug](http://httplug.io/) for making requests. If you wish to use your own HTTP Client, it MUST implment the `Http\Client\HttpClient` interface. Please refer to [this example](./docs/examples/http_client.md)
