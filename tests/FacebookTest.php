<?php

namespace JoelButcher\Facebook\Tests;

use Facebook\Application;
use Facebook\Client;
use Facebook\Facebook as BaseFacebook;
use Facebook\PersistentData\InMemoryPersistentDataHandler;
use JoelButcher\Facebook\Facebook;
use JoelButcher\Facebook\Tests\Fixtures\FooOAuth2Client;
use Mockery as m;

class FacebookTest extends TestCase
{
    public function testItThrowsForNoAppId()
    {
        $this->expectException(\Facebook\Exception\SDKException::class);
        $facebook = new Facebook(array_merge($this->config, ['app_id' => null]));
        $facebook->getFacebook();
    }

    public function testItThrowsForNoAppSecret()
    {
        $this->expectException(\Facebook\Exception\SDKException::class);
        $facebook = new Facebook(array_merge($this->config, ['app_secret' => null]));
        $facebook->getFacebook();
    }

    public function testItThrowsForNoDefaultGraphVersion()
    {
        $this->expectException(\InvalidArgumentException::class);
        $facebook = new Facebook(array_merge($this->config, ['default_graph_version' => null]));
        $facebook->getFacebook();
    }

    public function testItThrowsForInvalidHttpClient()
    {
        $this->expectException(\InvalidArgumentException::class);
        $facebook = new Facebook(array_merge($this->config, [
            'http_client' => function () {
                return null;
            },
        ]));
        $facebook->getFacebook();
    }

    public function testItThrowsForInvalidDataHandler()
    {
        $this->expectException(\InvalidArgumentException::class);
        $facebook = new Facebook(array_merge($this->config, [
            'persistent_data_handler' => function () {
                return null;
            },
        ]));
        $facebook->getFacebook();
    }

    public function testItThrowsForInvalidDefaultAccessToken()
    {
        $this->expectException(\InvalidArgumentException::class);
        $facebook = new Facebook(array_merge($this->config, [
            'default_access_token' => function () {
                return null;
            },
        ]));
        $facebook->getFacebook();
    }

    public function testItThrowsIfNoRedirectIsConfigured()
    {
        $facebook = $this->getFacebookMock([
            'redirect_uri' => null,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $facebook->getRedirect();
    }

    public function itReturnsAValidRedirectLoginHelperInstance()
    {
        $helper = $this->getFacebookMock()->getLoginHelper();
        $dataHandler = $helper->getPersistentDataHandler();
        $urlDetectionHandler = $helper->getUrlDetectionHandler();
        $this->assertInstanceOf(\Facebook\Helper\RedirectLoginHelper::class, $helper);
        $this->assertInstanceOf(\Facebook\PersistentData\PersistentDataInterface::class, $dataHandler);
        $this->assertInstanceOf(\Facebook\Url\UrlDetectionInterface::class, $urlDetectionHandler);
    }

    public function testReturnsAValidRedirect()
    {
        $redirect = $this->getFacebookMock()->getRedirect();
        $this->assertStringContainsStringIgnoringCase('response_type=code', $redirect);
        $this->assertStringContainsStringIgnoringCase('client_id=123456789', $redirect);
        $this->assertStringContainsStringIgnoringCase('redirect_uri='.urlencode(static::REDIRECT_URL), $redirect);
    }

    public function testItReturnsAValidLogoutUrl()
    {
        $redirect = $this->getFacebookMock()->getLogoutUrl('foo-access-token', static::REDIRECT_URL);
        $this->assertStringContainsStringIgnoringCase('access_token=foo-access-token', $redirect);
        $this->assertStringContainsStringIgnoringCase('next='.urlencode(static::REDIRECT_URL), $redirect);
        $this->assertStringContainsStringIgnoringCase('https://www.facebook.com/logout.php', $redirect);
    }

    public function testReturnsAValidReRequestUrl()
    {
        $redirect = $this->getFacebookMock()->getReRequestUrl(static::REDIRECT_URL);
        $this->assertStringContainsStringIgnoringCase('auth_type=rerequest', $redirect);
        $this->assertStringContainsStringIgnoringCase('response_type=code', $redirect);
        $this->assertStringContainsStringIgnoringCase('client_id=123456789', $redirect);
        $this->assertStringContainsStringIgnoringCase('redirect_uri='.urlencode(static::REDIRECT_URL), $redirect);
    }

    public function testReturnsAValidReAuthenticationUrl()
    {
        $redirect = $this->getFacebookMock()->getReAuthenticationUrl(static::REDIRECT_URL);
        $this->assertStringContainsStringIgnoringCase('auth_type=reauthenticate', $redirect);
        $this->assertStringContainsStringIgnoringCase('response_type=code', $redirect);
        $this->assertStringContainsStringIgnoringCase('client_id=123456789', $redirect);
        $this->assertStringContainsStringIgnoringCase('redirect_uri='.urlencode(static::REDIRECT_URL), $redirect);
    }

    public function testItGetsAnAccessToken()
    {
        $_GET['code'] = 'foo_code';
        $_GET['state'] = 'foo_state';

        $persistentDataHandler = new InMemoryPersistentDataHandler;
        $persistentDataHandler->set('state', 'foo_state');

        /** @var \Facebook\Facebook|\Mockery\MockInterface $base */
        $base = m::mock(BaseFacebook::class, [array_merge($this->config, [
            'persistent_data_handler' => $persistentDataHandler,
        ])])->makePartial();

        $oAuth2Client = new FooOAuth2Client($base->getApplication(), $base->getClient(), 'v1337');
        $base->shouldReceive('getOAuth2Client')->once()->andReturn($oAuth2Client);

        $facebook = $this->getFacebookMock();
        $facebook->shouldReceive('getFacebook')->andReturn($base);
        $accessToken = $facebook->getAccessToken(static::REDIRECT_URL);
        $this->assertEquals('foo_token_from_code|foo_code|'.self::REDIRECT_URL, (string) $accessToken);
    }

    /**
     * @param  array  $config
     * @return \JoelButcher\Facebook\Facebook|\Mockery\MockInterface
     */
    protected function getFacebookMock(array $config = [])
    {
        return m::mock(Facebook::class, [array_merge($this->config, $config)])->makePartial();
    }
}
