<?php

namespace HieuLe\Taki;

use Illuminate\Foundation\Application;
use Illuminate\Config\Repository;
use Illuminate\Validation\Validator;

/**
 * Description of BaseTestCase
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{

    protected $aliases;

    protected function setUp()
    {
        parent::setUp();
        $this->aliases = [];

        $app = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        Application::setInstance($app);

        $app->expects($this->any())
            ->method('make')
            ->willReturnCallback(function($name) {
                if (isset($this->aliases[$name])) {
                    return $this->aliases[$name];
                } else {
                    throw new \RuntimeException("Cannot find the mock object of '{$name}'.");
                }
            });
    }

    protected function tearDown()
    {
        $this->aliases = null;
        parent::tearDown();
    }

    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function initConfigService()
    {
        $config = $this->getMock(Repository::class, []);
        $this->aliases['config'] = $config;
        return $config;
    }

    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function initValidatorService()
    {
        $v = $this->getMockBuilder(Validator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validator = $this->getMockBuilder(\Illuminate\Validation\Factory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $validator->expects($this->any())
            ->method('make')
            ->withAnyParameters()
            ->willReturn($v);

        $this->aliases['Illuminate\Contracts\Validation\Factory'] = $validator;

        return $v;
    }
}
