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


interface ContainerConfiguratorInterface
{
    /**
     * @param array|Traversable $config
     *
     * @throws InvalidConfigException
     */
    public function configure($config);

    /**
     * Add a definition from a single config entry
     *
     * @param  string $alias
     * @param  mixed  $options
     */
    public function configureOne($alias, $options);
}
