<?php

namespace JoelButcher\Facebook\Tests;

use JoelButcher\Facebook\FacebookServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected const REDIRECT_URL = 'http://invalid.zzz';

    /**
     * @var array
     */
    protected $config = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->config = [
            'app_id' => $this->app['config']->get('facebook.app_id'),
            'app_secret' => $this->app['config']->get('facebook.app_secret'),
            'redirect_uri' => $this->app['config']->get('facebook.redirect_uri'),
            'default_graph_version' => $this->app['config']->get('facebook.graph_version'),
        ];
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('facebook.app_id', '123456789');
        $app['config']->set('facebook.app_secret', '123456789');
        $app['config']->set('facebook.redirect_uri', static::REDIRECT_URL);
        $app['config']->set('facebook.graph_version', 'v11.0');
    }

    protected function getPackageProviders($app)
    {
        return [FacebookServiceProvider::class];
    }
}
