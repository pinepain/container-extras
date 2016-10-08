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


abstract class AbstractContainerConfigurator implements ContainerConfiguratorInterface
{
    /**
     * {@configure}
     */
    public function configure($config)
    {
        if (!is_array($config) && !($config instanceof Traversable)) {
            throw new InvalidConfigException(
                'You can only load definitions from an array or an object that implements Traversable interface.'
            );
        }

        $this->configureMany($config);
    }

    /**
     * Load configuration
     *
     * @param array|Traversable $config
     */
    abstract protected function configureMany($config);
}
