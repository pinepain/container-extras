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


use stdClass;


class AbstractContainerConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    public function testPopulateWithTraversable()
    {
        $loader = $this->getConfiguratorMock();

        /** @var \Iterator | \PHPUnit_Framework_MockObject_MockObject $config */
        $config = $this->getMockBuilder('\Iterator')->getMockForAbstractClass();

        $loader->expects($this->once())
               ->method('configureMany')
               ->with($config);

        $loader->configure($config);
    }

    /**
     * @expectedException \Pinepain\Container\Extras\Exceptions\InvalidConfigException
     * @expectedExceptionMessage You can only load definitions from an array or an object that implements Traversable
     *                           interface.
     */
    public function testPopulateFromNotArrayNorTraversable()
    {
        $loader = $this->getConfiguratorMock();

        $loader->expects($this->never())
               ->method('configureMany');

        $loader->configure(new stdClass());
    }

    /**
     * @return \Pinepain\Container\Extras\AbstractContainerConfigurator | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getConfiguratorMock()
    {
        return $this->getMockBuilder('\Pinepain\Container\Extras\AbstractContainerConfigurator')
                    ->setMethods(['configureMany'])
                    ->getMockForAbstractClass();
    }

}
