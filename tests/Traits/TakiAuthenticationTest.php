<?php

namespace HieuLe\Taki\Traits;

use Illuminate\Http\Request;
use HieuLe\Taki\Stubs\Controller;

/**
 * Description of TakiAuthenticationTest
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class TakiAuthenticationTest extends \HieuLe\Taki\BaseTestCase
{

    /**
     * Test controller return a redirect if validation failed
     * 
     * @expectedException \Illuminate\Http\Exception\HttpResponseException
     */
    public function testValidationFailed()
    {
        $c = $this->initConfigService();

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
}
