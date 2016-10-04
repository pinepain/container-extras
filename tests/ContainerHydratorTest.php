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
use Pinepain\Container\Extras\ContainerHydrator;
use stdClass;


class ContainerHydratorTest extends \PHPUnit_Framework_TestCase
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
        $loader = new ContainerHydrator();

        $config = [];

        $container = $this->getContainerMock();

        $container->expects($this->never())->method('add');

        $loader->populate($container, $config);
    }

    public function testPopulateWithTraversable()
    {
        $loader = new ContainerHydrator();

        /** @var \Iterator | \PHPUnit_Framework_MockObject_MockObject $config */
        $config = $this->getMockBuilder('\Iterator')->getMockForAbstractClass();

        $container = $this->getContainerMock();

        $container->expects($this->never())->method('add');

        $loader->populate($container, $config);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You can only load definitions from an array or an object that implements Traversable
     *                           interface.
     */
    public function testPopulateFromNotArrayNorTraversable()
    {
        $loader = new ContainerHydrator();

        $container = $this->getContainerMock();

        $loader->populate($container, new stdClass());
    }

    public function testAddingScalar()
    {
        $loader = new ContainerHydrator();

        $config = [
            'key' => 'value',
        ];

        $container = $this->getContainerMock();

        $container->expects($this->once())
                  ->method('add')
                  ->with('key', 'value', false);

        $loader->populate($container, $config);
    }

    public function testAddingConcrete()
    {
        $loader = new ContainerHydrator();

        $config = [
            'TestInterface' => null,
        ];

        $container = $this->getContainerMock();

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', null, false);

        $loader->populate($container, $config);
    }

    public function testSharingConcrete()
    {
        $loader = new ContainerHydrator();

        $config = [
            'TestInterface' => [
                'share' => true,
            ],
        ];

        $container = $this->getContainerMock();

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', null, true);

        $loader->populate($container, $config);
    }

    public function testAddingAlias()
    {
        $loader = new ContainerHydrator();

        $config = [
            'TestInterface' => 'Test',
        ];

        $container = $this->getContainerMock();

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'Test', false);

        $loader->populate($container, $config);
    }

    public function testAddingShared()
    {
        $loader = new ContainerHydrator();

        $config = [
            'TestInterface' => [
                'class' => 'Test',
                'share' => true,
            ],
        ];

        $container = $this->getContainerMock();

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'Test', true);

        $loader->populate($container, $config);
    }

    public function testAddingAliasWithArgument()
    {
        $loader = new ContainerHydrator();

        $config = [
            'TestInterface' => [
                'class'     => 'TestWithArguments',
                'arguments' => ['test', 'arguments'],
            ],
        ];

        $container = $this->getContainerMock();

        $definition = $this->getDefinitionMock();

        $definition->expects($this->once())
                   ->method('withArguments')
                   ->with(['test', 'arguments'])
                   ->willReturn($definition);

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'TestWithArguments', false)
                  ->willReturn($definition);

        $loader->populate($container, $config);
    }


    public function testAddingAliasWithMethodCalls()
    {
        $loader = new ContainerHydrator();

        $config = [
            'TestInterface' => [
                'class'   => 'TestWithMethodCalls',
                'methods' => [
                    'setValue' => ['test value'],
                ],
            ],
        ];

        $container = $this->getContainerMock();

        $definition = $this->getClassDefinitionMock();

        $definition->expects($this->once())
                   ->method('withMethodCalls')
                   ->with(['setValue' => ['test value']])
                   ->willReturn($definition);

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', 'TestWithMethodCalls', false)
                  ->willReturn($definition);

        $loader->populate($container, $config);
    }

    public function testAddingAliasWithArgumentsAndMethodCalls()
    {
        $loader = new ContainerHydrator();

        $config = [
            'TestInterface' => [
                'class'     => 'TestWithArgumentsAndMethodCalls',
                'arguments' => ['test', 'arguments'],
                'methods'   => [
                    'setValue' => ['test value'],
                ],
            ],
        ];

        $container = $this->getContainerMock();

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

        $loader->populate($container, $config);
    }

    public function testAddDefinition()
    {
        $loader = new ContainerHydrator();

        $definition = $this->getClassDefinitionMock();

        $config = [
            'TestInterface' => [
                'definition' => $definition,
            ],
        ];

        $container = $this->getContainerMock();

        $container->expects($this->once())
                  ->method('add')
                  ->with('TestInterface', $definition, false);

        $loader->populate($container, $config);
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
