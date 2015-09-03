<?php

namespace HieuLe\Taki\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use HieuLe\Taki\Stubs\Controller;
use HieuLe\Taki\TakiFacade;

/**
 * Description of TakiAuthenticationTest
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class TakiAuthenticationTest extends \HieuLe\Taki\BaseTestCase
{

    /**
     * Test getting correct field name for login(username/email/login)
     */
    public function testGetLoginUsername()
    {
        $this->initConfigService();
        $c = new Controller;

        // default config
        $this->assertEquals('email', $c->loginUsername());

        // login by username or email
        config(['taki.login_by' => 'both']);
        $this->assertEquals('login', $c->loginUsername());

        // login by username
        config(['taki.login_by' => 'username']);
        $this->assertEquals('username', $c->loginUsername());

        // custom field name
        config(['taki.field.username' => 'input_email']);
        $this->assertEquals('input_email', $c->loginUsername());
    }

    /**
     * Test controller return a redirect if validation failed
     * 
     * @expectedException \Illuminate\Http\Exception\HttpResponseException
     */
    public function testValidationFailed()
    {
        $this->initConfigService();

        $v = $this->initValidatorService();
        $v->expects($this->once())
            ->method('fails')
            ->willReturn(true);

        $request = $this->getMock(Request::class);
        $request->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $c = new Controller;
        $c->postLogin($request);
    }

    /**
     * If there are too many login attempts, a redirect response is returned 
     * with the target URL is set in the `loginPath` property of the controller.
     */
    public function testTooManyLoginAttempts()
    {
        $this->initConfigService();

        $v = $this->initValidatorService();
        $v->expects($this->exactly(2))
            ->method('fails')
            ->willReturn(false);

        $rl = $this->initRateLimiterService();
        $rl->expects($this->exactly(2))
            ->method('tooManyAttempts')
            ->willReturn(true);

        $request = $this->getMock(Request::class);
        $request->expects($this->exactly(2))
            ->method('all')
            ->willReturn([]);

        $this->initRedirectorService();

        $c   = new Controller;
        $res = $c->postLogin($request);
        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertEquals('/auth/login', $res->getTargetUrl());

        $c->loginPath = '/custom/login/path';
        $res          = $c->postLogin($request);
        $this->assertEquals('/custom/login/path', $res->getTargetUrl());
    }

    /**
     * After logging in successfully, redirect the user to the intended url
     */
    public function testLoginSuccess()
    {
        $a = $this->initAuthService();
        TakiFacade::shouldReceive('attempt')
            ->andReturn(true);
        $this->initConfigService();

        $v = $this->initValidatorService();
        $v->expects($this->exactly(1))
            ->method('fails')
            ->willReturn(false);

        $rl = $this->initRateLimiterService();
        $rl->expects($this->exactly(1))
            ->method('tooManyAttempts')
            ->willReturn(false);

        $request = $this->getMock(Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn([]);
        $request->expects($this->any())
            ->method('only')
            ->willReturn([]);

        $this->initRedirectorService();

        $c   = new Controller;
        $res = $c->postLogin($request);
        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertEquals('url.intended', $res->getTargetUrl());
    }
}
