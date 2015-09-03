<?php

namespace HieuLe\Taki\Traits;

use Laravel\Socialite\Two\FacebookProvider;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HieuLe\Taki\BaseTestCase;
use HieuLe\Taki\Stubs\Controller;
use HieuLe\Taki\Stubs\User;

/**
 * Description of TakiSocialTest
 *
 * @author Hieu Le <hieu@codeforcevina.com>
 */
class TakiSocialTest extends BaseTestCase
{

    /**
     * User is redirected to the authentication url of the service
     */
    public function testSocialiteRedirect()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['all', 'only'])
            ->getMock();
        $ses     = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->setSession($ses);

        $s = $this->initSocialiteService();
        $s->expects($this->once())
            ->method('buildProvider')
            ->with('Laravel\Socialite\Two\FacebookProvider')
            ->willReturn(new FacebookProvider(
                $request, '', '', ''));

        $this->initConfigService();

        $rd = $this->initRedirectorService();

        $c = new Controller;

        $res = $c->getOauth('facebook');

        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertStringStartsWith('https://www.facebook.com/v2.4/dialog/oauth?', $res->getTargetUrl());
    }

    /**
     * When the email is registered, user is logged in and redirected
     * to the intended path.
     */
    public function testOauthCallbackLogin()
    {
        $this->initConfigService();

        $auth = $this->initAuthService();

        \Illuminate\Support\Facades\Auth::swap($auth);

        $rd = $this->initRedirectorService();

        $this->initUserModel();

        $this->setUpSocial();

        $c = new Controller;

        $res = $c->getOauthCallback('facebook');
        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertEquals('/', $res->getTargetUrl());
    }

    /**
     * When the email not register, by default, an account is created
     * 
     * @expectedException \RuntimeException
     * @expectedExceptionMessage When Taki `social.username_auto` option is set to false, your controller must implement `generateUsername($service, $user)` method and override this one
     */
    public function testOauthCallbackRegisterAuth()
    {
        User::$first = false;

        $this->initConfigService();

        $auth = $this->initAuthService();

        \Illuminate\Support\Facades\Auth::swap($auth);

        $rd = $this->initRedirectorService();

        $this->initUserModel();

        $this->setUpSocial();

        $c = new Controller;

        $res = $c->getOauthCallback('facebook');
        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertEquals('/', $res->getTargetUrl());
    }

    /**
     * If a username is required or password is required, the user is redirected
     * to another page to complete this information first.
     */
    public function testOauthCallbackRegisterManual()
    {
        User::$first = false;

        $this->initConfigService();
        config(['taki.social.password_required' => true]);

        $auth = $this->initAuthService();

        \Illuminate\Support\Facades\Auth::swap($auth);

        $rd = $this->initRedirectorService();

        $this->initUserModel();

        $this->setUpSocial();

        $c = new Controller;

        $res = $c->getOauthCallback('facebook');
        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertEquals('/oauth/complete', $res->getTargetUrl());

        $c->oauthCompletedPath = '/oauth/new';
        $res                   = $c->getOauthCallback('facebook');
        $this->assertEquals('/oauth/new', $res->getTargetUrl());
    }

    protected function setUpSocial()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['all', 'only'])
            ->getMock();
        $ses     = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->setSession($ses);

        $user = $this->getMockBuilder(\Laravel\Socialite\Two\User::class)
            ->getMock();

        $fb = $this->getMockBuilder(FacebookProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fb->expects($this->any())
            ->method('user')
            ->willReturn($user);

        $s = $this->initSocialiteService();
        $s->expects($this->any())
            ->method('buildProvider')
            ->with('Laravel\Socialite\Two\FacebookProvider')
            ->willReturn($fb);
    }
}
