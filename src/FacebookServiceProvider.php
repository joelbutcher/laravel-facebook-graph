<?php

namespace JoelButcher\Facebook;

use Facebook\PersistentData\PersistentDataInterface;
use Facebook\Url\UrlDetectionHandler;
use Facebook\Url\UrlDetectionInterface;
use Http\Client\HttpClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class FacebookServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfig();
        $this->registerDefaultHttpClient();
        $this->registerUrlDetectionHandler();
        $this->registerDefaultPersistentDataHandler();
        $this->registerFacebook();

        $this->app->singleton('facebook-graph', function (Application $app) {
            return $app[Facebook::class];
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();
    }

    /**
     * Publish the config file.
     *
     * @return void
     */
    protected function publishConfig(): void
    {
        $this->publishes([$this->getConfigPath() => config_path('facebook.php')], 'facebook');
    }

    /**
     * Merge the Facebook config with the default "services" config.
     *
     * @return void
     */
    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'facebook');
    }

    /**
     * Get the path to the package config file.
     *
     * @return string
     */
    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/config.php';
    }

    /**
     * Register a default binding for the Facebook HTTP client interface.
     *
     * @return void
     */
    protected function registerDefaultHttpClient(): void
    {
        $this->app->singleton(HttpClient::class, function () {
            return null;
        });
    }

    /**
     * Register the default URL Protection handler for the Facebook SDK.
     *
     * @return void
     */
    protected function registerUrlDetectionHandler(): void
    {
        $this->app->singleton(UrlDetectionInterface::class, function () {
            return new UrlDetectionHandler;
        });
    }

    /**
     * Register a default binding for the persistent data interface.
     *
     * @return void
     */
    protected function registerDefaultPersistentDataHandler(): void
    {
        $this->app->singleton(PersistentDataInterface::class, function () {
            return null;
        });
    }

    /**
     * Register a binding for the Facebook Graph PHP SDK.
     *
     * @return void
     */
    protected function registerFacebook(): void
    {
        // Register the Facebook graph class binding as a singleton - this will be
        // the class instance that gets resolved via dependency injection when the
        // class is typehinted in a constructor or as a method parameter (e.g. in
        // a controller)
        $this->app->singleton(Facebook::class, function (Application $app) {
            return new Facebook([
                'app_id' => $app['config']->get('facebook.app_id'),
                'app_secret' => $app['config']->get('facebook.app_secret'),
                'redirect_uri' => $app['config']->get('facebook.redirect_uri'),
                'scopes' => $app['config']->get('facebook.scopes', []),
                'default_graph_version' => $app['config']->get('facebook.graph_version'),
                'enable_beta_mode' => $app['config']->get('facebook.beta_mode'),
                'persistent_data_handler' => $app[PersistentDataInterface::class],
                'http_client' => $app[HttpClient::class],
                'url_detection_handler' => $app[UrlDetectionInterface::class],
            ]);
        });
    }
}
