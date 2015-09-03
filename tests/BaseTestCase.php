<?php

namespace HieuLe\Taki;

use Illuminate\Foundation\Application;
use Illuminate\Config\Repository;
use Illuminate\Validation\Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\RateLimiter;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

/**
 * Description of BaseTestCase
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{

    protected $aliases;

    /**
     *
     * @var Filesystem;
     */
    protected $fs;

    protected function setUp()
    {
        parent::setUp();
        $this->aliases = [];
        $this->fs      = new Filesystem;

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
        $config                  = $this->getMock(Repository::class, null);
        $this->aliases['config'] = $config;

        $config->set(['taki' => $this->readConfigFile()]);

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

    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function initRateLimiterService()
    {
        $rl = $this->getMockBuilder(RateLimiter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->aliases[RateLimiter::class] = $rl;

        return $rl;
    }

    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function initRedirectorService()
    {
        $u = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $u->expects($this->any())
            ->method('to')
            ->willReturnArgument(0);
        $u->expects($this->any())
            ->method('getRequest')
            ->willReturn(new Request());
        $r = $this->getMockBuilder(Redirector::class)
            ->setConstructorArgs([$u])
            ->setMethods(null)
            ->getMock();

        $s = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();
        $s->expects($this->any())
            ->method('get')
            ->willReturnArgument(1);
        $r->setSession($s);

        $this->aliases['redirect'] = $r;

        return $r;
    }

    /**
     * Return the default config from file
     * 
     * @return array
     */
    protected function readConfigFile()
    {
        return $this->fs->getRequire(__DIR__ . '/../config/taki.php');
    }
}
