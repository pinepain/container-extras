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


namespace Pinepain\Container\Extras\Tests;


use Pinepain\Container\Extras\AliasContainerConfigurator;
use Pinepain\Container\Extras\AliasContainerInterface;


class AliasContainerConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    public function testPopulateWithEmpty()
    {
        $container = $this->getContainerMock();
        $loader    = new AliasContainerConfigurator($container);

        $config = [];

        $container->expects($this->never())->method('add');

        $loader->configure($config);
    }

    public function testPopulateWithAliases()
    {
        $container = $this->getContainerMock();
        $loader    = new AliasContainerConfigurator($container);

        $config = [
            'alias 1' => 'concrete 1',
            'alias 2' => 'concrete 2',
        ];

        $container->expects($this->exactly(2))
                  ->method('add')
                  ->withConsecutive(['alias 1', 'concrete 1'], ['alias 2', 'concrete 2']);

        $loader->configure($config);
    }

    public function testPopulateWithMixed()
    {
        $container = $this->getContainerMock();
        $loader    = new AliasContainerConfigurator($container);

        $config = [
            'alias 1' => 'concrete 1',
            'alias'   => ['options'],
            'alias 2' => 'concrete 2',
        ];

        $container->expects($this->exactly(2))
                  ->method('add')
                  ->withConsecutive(['alias 1', 'concrete 1'], ['alias 2', 'concrete 2']);

        $loader->configure($config);
    }

    /**
     * @expectedException \Pinepain\Container\Extras\Exceptions\InvalidConfigException
     * @expectedExceptionMessage Alias and concrete should be strings
     */
    public function testConfigureOneWithNonStringAlias()
    {
        $container = $this->getContainerMock();
        $loader    = new AliasContainerConfigurator($container);

        $loader->configureOne([], 'test');
    }

    /**
     * @expectedException \Pinepain\Container\Extras\Exceptions\InvalidConfigException
     * @expectedExceptionMessage Alias and concrete should be strings
     */
    public function testConfigureOneWithNonStringConcrete()
    {
        $container = $this->getContainerMock();
        $loader    = new AliasContainerConfigurator($container);

        $loader->configureOne('test', []);
    }

    /**
     * @expectedException \Pinepain\Container\Extras\Exceptions\InvalidConfigException
     * @expectedExceptionMessage Alias and concrete should be strings
     */
    public function testConfigureOneWithBothNonString()
    {
        $container = $this->getContainerMock();
        $loader    = new AliasContainerConfigurator($container);

        $loader->configureOne([], []);
    }

    /**
     * @return AliasContainerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getContainerMock()
    {
        return $this->getMockBuilder('\Pinepain\Container\Extras\AliasContainerInterface')->getMockForAbstractClass();
    }
}
