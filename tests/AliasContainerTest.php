<?php

/*
  +------------------------------------------------------------------------+
  | This file is part of the pinepain/league-container-extras PHP library. |
  |                                                                        |
  | Copyright (c) 2015-2016 Bogdan Padalko <pinepain@gmail.com>            |
  |                                                                        |
  | Licensed under the MIT license: http://opensource.org/licenses/MIT     |
  |                                                                        |
  | For the full copyright and license information, please view the        |
  | LICENSE file that was distributed with this source or visit            |
  | http://opensource.org/licenses/MIT                                     |
  +------------------------------------------------------------------------+
*/


namespace Pinepain\League\Container\Extras\Tests;


use Pinepain\League\Container\Extras\AliasContainer;


class AliasContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Interop\Container\ContainerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;
    /**
     * @var AliasContainer
     */
    private $obj;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder('\Interop\Container\ContainerInterface')
                                ->setMethods(['get'])
                                ->getMockForAbstractClass();

        $this->obj = new AliasContainer();

        $this->obj->setContainer($this->container);
    }

    public function testAdd()
    {
        $c = $this->obj;

        $this->assertFalse($c->has('alias'));
        $this->assertFalse($c->has('nonexistent'));

        $c->add('alias', 'orig');

        $this->assertTrue($c->has('alias'));
        $this->assertFalse($c->has('nonexistent'));
    }

    public function testGet()
    {
        $c = $this->obj;

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('orig')
                        ->willReturn('orig resolved');

        $c->add('alias', 'orig');

        $this->assertSame('orig resolved', $c->get('alias'));
    }

    public function testGetWithArguments()
    {
        $c = $this->obj;

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('orig', ['with', 'arguments'])
                        ->willReturn('orig resolved with arguments');

        $c->add('alias', 'orig');

        $this->assertSame('orig resolved with arguments', $c->get('alias', ['with', 'arguments']));
    }

    /**
     * @expectedException \League\Container\Exception\NotFoundException
     * @expectedExceptionMessage Alias (nonexistent) is not registered and therefore cannot be resolved
     */
    public function testGetNonexistent()
    {
        $c = $this->obj;

        $c->get('nonexistent');
    }
}
