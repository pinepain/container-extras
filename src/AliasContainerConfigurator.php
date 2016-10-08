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


use League\Container\ContainerInterface;
use League\Container\Definition\ClassDefinition;
use League\Container\Definition\DefinitionInterface;
use Pinepain\Container\Extras\Exceptions\InvalidConfigException;
use Traversable;


class AliasContainerConfigurator implements ContainerConfiguratorInterface
{
    /**
     * @var AliasContainerInterface
     */
    private $container;

    /**
     * @param AliasContainerInterface $container
     */
    public function __construct(AliasContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function configure($config)
    {
        if (!is_array($config) && !($config instanceof Traversable)) {
            throw new InvalidConfigException(
                'You can only load definitions from an array or an object that implements Traversable interface.'
            );
        }
        foreach ($config as $alias => $concrete) {

            if (!is_string($concrete)) {
                continue;
            }

            $this->configureOne($alias, $concrete);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureOne($alias, $concrete)
    {
        if (!is_string($alias) || !is_string($concrete)) {
            throw new InvalidConfigException('Alias and concrete should be strings');
        }

        $this->container->add($alias, $concrete);
    }
}
