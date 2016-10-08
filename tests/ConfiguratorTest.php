<?php

/*
  +----------------------------------------------------------------------+
  | This file is part of the pinepain/container-extras PHP library.      |
  |                                                                      |
  | Copyright (c) 2015-2016 Bogdan Padalko <pinepain@gmail.com>          |
  |                                                                      |
  | Licensed under the MIT license: http://opensource.org/licenses/MIT   |
  |                                                                      |
  | For the full copyright and license information, please view the      |
  | LICENSE file that was distributed with this source or visit          |
  | http://opensource.org/licenses/MIT                                   |
  +----------------------------------------------------------------------+
*/

/* Based on League\Container\Test\ContainerTest
 * v1.x class (https://github.com/thephpleague/container/blob/1.x/tests/ContainerTest.php)
 * which is authored by Phil Bennett (https://github.com/philipobenito)
 * and other contributors (https://github.com/thephpleague/container/contributors).
 */


namespace Pinepain\Container\Extras\Tests;


use League\Container\ContainerInterface;
use League\Container\Definition\ClassDefinition;
use League\Container\Definition\DefinitionInterface;
use Pinepain\Container\Extras\Configurator;
use stdClass;


class ConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    protected $configArray = [
        'League\Container\Test\Asset\Foo' => [
            'class'     => 'League\Container\Test\Asset\Foo',
            'arguments' => ['League\Container\Test\Asset\Bar'],
            'methods'   => [
                'injectBaz' => ['League\Container\Test\Asset\Baz'],
            ],
        ],
        'League\Container\Test\Asset\Bar' => [
            'definition' => 'League\Container\Test\Asset\Bar',
            'arguments'  => ['League\Container\Test\Asset\Baz'],
        ],
        'League\Container\Test\Asset\Baz' => 'League\Container\Test\Asset\Baz',
    ];

    public function testPopulateWithEmpty()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [];

        $container->expects($this->never())->method('add');

        $loader->configure($config);
    }

    public function testPopulateWithTraversable()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        /** @var \Iterator | \PHPUnit_Framework_MockObject_MockObject $config */
        $config = $this->getMockBuilder('\Iterator')->getMockForAbstractClass();

        $container->expects($this->never())->method('add');

        $loader->configure($config);
    }

    /**
     * @expectedException \Pinepain\Container\Extras\Exceptions\InvalidConfigException
     * @expectedExceptionMessage You can only load definitions from an array or an object that implements Traversable
     *                           interface.
     */
    public function testPopulateFromNotArrayNorTraversable()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $loader->configure(new stdClass());
    }

    public function testAddingScalar()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'key' => 'value',
        ];

        $container->expects($this->once())
                  ->method('add')
                  ->with('key', 'value', false);

        $loader->configure($config);
    }

    public function testAddingConcrete()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'TestInterface' => null,
        ];

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', null, false);

        $loader->configure($config);
    }

    public function testSharingConcrete()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'TestInterface' => [
                'share' => true,
            ],
        ];

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', null, true);

        $loader->configure($config);
    }

    public function testAddingAlias()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'TestInterface' => 'Test',
        ];

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'Test', false);

        $loader->configure($config);
    }

    public function testAddingShared()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'TestInterface' => [
                'class' => 'Test',
                'share' => true,
            ],
        ];

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'Test', true);

        $loader->configure($config);
    }

    public function testAddingAliasWithArgument()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'TestInterface' => [
                'class'     => 'TestWithArguments',
                'arguments' => ['test', 'arguments'],
            ],
        ];

        $definition = $this->getDefinitionMock();

        $definition->expects($this->once())
                   ->method('withArguments')
                   ->with(['test', 'arguments'])
                   ->willReturn($definition);

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'TestWithArguments', false)
                  ->willReturn($definition);

        $loader->configure($config);
    }


    public function testAddingAliasWithMethodCalls()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'TestInterface' => [
                'class'   => 'TestWithMethodCalls',
                'methods' => [
                    'setValue' => ['test value'],
                ],
            ],
        ];

        $definition = $this->getClassDefinitionMock();

        $definition->expects($this->once())
                   ->method('withMethodCalls')
                   ->with(['setValue' => ['test value']])
                   ->willReturn($definition);

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'TestWithMethodCalls', false)
                  ->willReturn($definition);

        $loader->configure($config);
    }

    public function testAddingAliasWithArgumentsAndMethodCalls()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $config = [
            'TestInterface' => [
                'class'     => 'TestWithArgumentsAndMethodCalls',
                'arguments' => ['test', 'arguments'],
                'methods'   => [
                    'setValue' => ['test value'],
                ],
            ],
        ];

        $definition = $this->getClassDefinitionMock();

        $definition->expects($this->once())
                   ->method('withArguments')
                   ->with(['test', 'arguments'])
                   ->willReturn($definition);

        $definition->expects($this->once())
                   ->method('withMethodCalls')
                   ->with(['setValue' => ['test value']])
                   ->willReturn($definition);

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'TestWithArgumentsAndMethodCalls', false)
                  ->willReturn($definition);

        $loader->configure($config);
    }

    public function testAddDefinition()
    {
        $container = $this->getContainerMock();
        $loader    = new Configurator($container);

        $definition = $this->getClassDefinitionMock();

        $config = [
            'TestInterface' => [
                'definition' => $definition,
            ],
        ];

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', $definition, false);

        $loader->configure($config);
    }

    /**
     * @return ContainerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getContainerMock()
    {
        return $this->getMockBuilder('\League\Container\ContainerInterface')->getMockForAbstractClass();
    }

    /**
     * @return DefinitionInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDefinitionMock()
    {
        return $this->getMockBuilder('\League\Container\Definition\DefinitionInterface')->getMockForAbstractClass();
    }

    /**
     * @return ClassDefinition | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getClassDefinitionMock()
    {
        return $this->getMockBuilder('\League\Container\Definition\ClassDefinition')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
