<?php

namespace HieuLe\Taki\Traits;

use Illuminate\Http\Request;
use HieuLe\Taki\Stubs\ValidatingMockController;
use HieuLe\Taki\Stubs\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Description of TakiRegistrationTest
 *
 * @author Hieu Le <hieu@codeforcevina.com>
 */
class TakiRegistrationTest extends \HieuLe\Taki\BaseTestCase
{

    // Test getting correct validation rules when create new user
    public function testValidateCreatingRules()
    {
        $this->initConfigService();

        $c = new ValidatingMockController();


        $this->assertArrayHasKey('email', $c->validateCreating());
        $this->assertArrayHasKey('password', $c->validateCreating());
        $this->assertArrayHasKey('password_confirmation', $c->validateCreating());
        $this->assertArrayNotHasKey('username', $c->validateCreating());

        // if the username is required
        config(['taki.username.required' => true]);
        $this->assertArrayHasKey('username', $c->validateCreating());
    }

    /**
     * Test registering new account with confirmation
     */
    public function testRegisterWithoutConfirmation()
    {
        $this->initConfigService();
        $auth = $this->initAuthService();
        \Illuminate\Support\Facades\Auth::swap($auth);

        $rd = $this->initRedirectorService();

        $pt = $this->initPasswordTokenService();
        $pt->expects($this->never())
            ->method('createNewToken');

        $v = $this->initValidatorService();
        $v->expects($this->exactly(2))
            ->method('fails')
            ->willReturn(false);


        $c = new Controller;

        $request = $this->getMock(Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn([]);

        $res = $c->postRegister($request);
        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertEquals('/home', $res->getTargetUrl());

        $c->postRegisterRedirect = '/register/success';
        $res                     = $c->postRegister($request);
        $this->assertEquals('/register/success', $res->getTargetUrl());
    }

    /**
     * Test registering new account with email confirmation
     */
    public function testRegisterWithConfirmation()
    {
        $this->initConfigService();
        config(['taki.confirm_after_created' => true]);

        $auth = $this->initAuthService();
        \Illuminate\Support\Facades\Auth::swap($auth);

        $rd = $this->initRedirectorService();

        $pt = $this->initPasswordTokenService();
        $pt->expects($this->once())
            ->method('createNewToken')
            ->willReturn(str_random());

        $v = $this->initValidatorService();
        $v->expects($this->once())
            ->method('fails')
            ->willReturn(false);

        $m = $this->initMailService();
        $m->expects($this->once())
            ->method('queue')
            ->willReturn(true);

        $c = new Controller;

        $request = $this->getMock(Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn([]);

        $res = $c->postRegister($request);
        $this->assertInstanceOf(RedirectResponse::class, $res);
        $this->assertEquals('/home', $res->getTargetUrl());
    }
}
