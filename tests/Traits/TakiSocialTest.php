<?php

namespace HieuLe\Taki\Traits;

use Laravel\Socialite\Two\FacebookProvider;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HieuLe\Taki\BaseTestCase;
use HieuLe\Taki\Stubs\Controller;

/**
 * Description of TakiSocialTest
 *
 * @author Hieu Le <hieu@codeforcevina.com>
 */
class TakiSocialTest extends BaseTestCase
{

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
}
