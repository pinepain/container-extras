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


namespace Pinepain\Container\Extras;


use Pinepain\Container\Extras\Exceptions\InvalidConfigException;
use Traversable;


interface ConfiguratorInterface
{
    /**
     * @param array|Traversable $config
     *
     * @throws InvalidConfigException
     */
    public function configure($config);
}
