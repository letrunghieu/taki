<?php

namespace HieuLe\Taki;

use Illuminate\Auth\AuthManager;
use Illuminate\Session\Store;
use HieuLe\Taki\BaseTestCase;

/**
 * Description of AuthTest
 *
 * @author Hieu Le <hieu@codeforcevina.com>
 */
class AuthTest extends BaseTestCase
{

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $auth;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $store;

    /**
     * Default case, login by email
     */
    public function testDefault()
    {
        $this->initConfigService();

        $taki = $this->createTaki();

        $this->auth->expects($this->once())
            ->method('attempt')
            ->with([
                'email'    => 'john@something.com',
                'password' => 'password',
            ])
            ->willReturn(true);

        $taki->attempt([
            'email'    => 'john@something.com',
            'password' => 'password',
        ]);
    }

    /**
     * user provides an email and we allows both email and username
     * 
     */
    public function testLoginByEmailWhenAllowingBoth()
    {
        $this->initConfigService();
        config(['taki.login_by' => 'both']);

        $taki = $this->createTaki();

        $this->auth->expects($this->once())
            ->method('attempt')
            ->with([
                'email'    => 'john@something.com',
                'password' => 'password',
            ])
            ->willReturn(true);

        $taki->attempt([
            'login'    => 'john@something.com',
            'password' => 'password',
        ]);
    }

    /**
     * user provides an username and we allows both email and username
     * 
     */
    public function testLoginByUsernameWhenAllowingBoth()
    {
        $this->initConfigService();
        config(['taki.login_by' => 'both']);

        $taki = $this->createTaki();

        $this->auth->expects($this->once())
            ->method('attempt')
            ->with([
                'username' => 'john',
                'password' => 'password',
            ])
            ->willReturn(true);

        $taki->attempt([
            'login'    => 'john',
            'password' => 'password',
        ]);
    }

    /**
     * 
     * @return \HieuLe\Taki\Auth
     */
    protected function createTaki()
    {
        $this->auth = $this->getMockBuilder(AuthManager::class)
            ->setMethods(['attempt'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->store = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $taki = new Auth($this->auth, $this->store);

        return $taki;
    }
}
