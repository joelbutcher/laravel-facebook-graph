<?php

namespace JoelButcher\Facebook\Tests;

use Facebook\Facebook as BaseFacebook;
use Facebook\PersistentData\InMemoryPersistentDataHandler;
use JoelButcher\Facebook\Facebook;
use JoelButcher\Facebook\Tests\Fixtures\FooOAuth2Client;
use Mockery as m;

uses(TestCase::class);

it('throws for no app id', function () {
    $this->expectException(\Facebook\Exception\SDKException::class);
    $facebook = new Facebook(array_merge($this->config, ['app_id' => null]));
    $facebook->getFacebook();
});

it('throws for no app secret', function () {
    $this->expectException(\Facebook\Exception\SDKException::class);
    $facebook = new Facebook(array_merge($this->config, ['app_secret' => null]));
    $facebook->getFacebook();
});

it('throws for no default graph version', function () {
    $this->expectException(\InvalidArgumentException::class);
    $facebook = new Facebook(array_merge($this->config, ['default_graph_version' => null]));
    $facebook->getFacebook();
});

it('throws for invalid http client ', function () {
    $this->expectException(\InvalidArgumentException::class);
    $facebook = new Facebook(array_merge($this->config, [
        'http_client' => function () {
            return null;
        },
    ]));
    $facebook->getFacebook();
});

it('throws for invalid data handler ', function () {
    $this->expectException(\InvalidArgumentException::class);
    $facebook = new Facebook(array_merge($this->config, [
        'persistent_data_handler' => function () {
            return null;
        },
    ]));
    $facebook->getFacebook();
});

it('throws for invalid default access token', function () {
    $this->expectException(\InvalidArgumentException::class);
    $facebook = new Facebook(array_merge($this->config, [
        'default_access_token' => function () {
            return null;
        },
    ]));
    $facebook->getFacebook();
});

it('throws if no redirect is configured', function () {
    $facebook = $this->getFacebookMock([
        'redirect_uri' => null,
    ]);

    $this->expectException(\InvalidArgumentException::class);
    $facebook->getRedirect();
});

it('builds default scopes', function () {
    $redirect = $this->getFacebookMock()->getRedirect();
    $this->assertStringContainsStringIgnoringCase('scope='.urlencode('email,public_profile'), $redirect);
});

it('builds replaces duplicate scopes with defaults', function () {
    $redirect = $this->getFacebookMock(['scopes' => ['email', 'public_profile']])->getRedirect();
    $this->assertStringContainsStringIgnoringCase('scope='.urlencode('email,public_profile'), $redirect);
});

it('builds appends default scopes to requested', function () {
    $redirect = $this->getFacebookMock(['scopes' => ['publish_actions']])->getRedirect();
    $this->assertStringContainsStringIgnoringCase('scope='.urlencode('publish_actions,email,public_profile'), $redirect);
});

it('returns a valid redirect login helper instance', function () {
    $helper = $this->getFacebookMock()->getLoginHelper();
    $dataHandler = $helper->getPersistentDataHandler();
    $urlDetectionHandler = $helper->getUrlDetectionHandler();
    $this->assertInstanceOf(\Facebook\Helper\RedirectLoginHelper::class, $helper);
    $this->assertInstanceOf(\Facebook\PersistentData\PersistentDataInterface::class, $dataHandler);
    $this->assertInstanceOf(\Facebook\Url\UrlDetectionInterface::class, $urlDetectionHandler);
});

it('returns a valid redirect', function () {
    $redirect = $this->getFacebookMock()->getRedirect();
    $this->assertStringContainsStringIgnoringCase('response_type=code', $redirect);
    $this->assertStringContainsStringIgnoringCase('client_id=123456789', $redirect);
    $this->assertStringContainsStringIgnoringCase('redirect_uri='.urlencode('http://invalid.zzz'), $redirect);
});

it('returns a valid logout url', function () {
    $redirect = $this->getFacebookMock()->getLogoutUrl('foo-access-token', 'http://invalid.zzz');
    $this->assertStringContainsStringIgnoringCase('access_token=foo-access-token', $redirect);
    $this->assertStringContainsStringIgnoringCase('next='.urlencode('http://invalid.zzz'), $redirect);
    $this->assertStringContainsStringIgnoringCase('https://www.facebook.com/logout.php', $redirect);
});

it('a valid re request url', function () {
    $redirect = $this->getFacebookMock()->getReRequestUrl('http://invalid.zzz');
    $this->assertStringContainsStringIgnoringCase('auth_type=rerequest', $redirect);
    $this->assertStringContainsStringIgnoringCase('response_type=code', $redirect);
    $this->assertStringContainsStringIgnoringCase('client_id=123456789', $redirect);
    $this->assertStringContainsStringIgnoringCase('redirect_uri='.urlencode('http://invalid.zzz'), $redirect);
});

it('a valid re authentication url', function () {
    $redirect = $this->getFacebookMock()->getReAuthenticationUrl('http://invalid.zzz');
    $this->assertStringContainsStringIgnoringCase('auth_type=reauthenticate', $redirect);
    $this->assertStringContainsStringIgnoringCase('response_type=code', $redirect);
    $this->assertStringContainsStringIgnoringCase('client_id=123456789', $redirect);
    $this->assertStringContainsStringIgnoringCase('redirect_uri='.urlencode('http://invalid.zzz'), $redirect);
});

it('gets an access token', function () {
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
    $accessToken = $facebook->getAccessToken('http://invalid.zzz');
    $this->assertEquals('foo_token_from_code|foo_code|http://invalid.zzz', (string) $accessToken);
});
