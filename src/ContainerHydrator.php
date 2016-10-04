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

/* Based on League\Container\Container v1.x class (https://github.com/thephpleague/container/blob/1.x/src/Container.php)
 * which is authored by Phil Bennett (https://github.com/philipobenito)
 * and other contributors (https://github.com/thephpleague/container/contributors).
 */


namespace Pinepain\Container\Extras;


use League\Container\ContainerInterface;
use League\Container\Definition\ClassDefinition;
use League\Container\Definition\DefinitionInterface;
use Traversable;


class ContainerHydrator
{
    /**
     * Populate the container with items from config
     *
     * @param ContainerInterface $container
     * @param  array|Traversable $config
     */
    public function populate(ContainerInterface $container, $config)
    {
        if (!is_array($config) && !($config instanceof Traversable)) {
            throw new \InvalidArgumentException(
                'You can only load definitions from an array or an object that implements Traversable interface.'
            );
        }

        if (empty($config)) {
            return;
        }

        $this->populateFromTraversable($container, $config);
    }

    protected function populateFromTraversable($container, $traversable)
    {
        foreach ($traversable as $alias => $options) {
            $this->createDefinition($container, $options, $alias);
        }
    }

    /**
     * Create a definition from a config entry
     *
     * @param ContainerInterface $container
     * @param  mixed             $options
     * @param  string            $alias
     *
     */
    protected function createDefinition(ContainerInterface $container, $options, $alias)
    {
        $concrete  = $this->resolveConcrete($options);
        $share     = false;
        $arguments = [];
        $methods   = [];

        if (is_array($options)) {
            $share     = !empty($options['share']);
            $arguments = (array_key_exists('arguments', $options)) ? (array)$options['arguments'] : [];
            $methods   = (array_key_exists('methods', $options)) ? (array)$options['methods'] : [];
        }

        // define in the container, with constructor arguments and method calls
        $definition = $container->add($alias, $concrete, $share);

        if ($definition instanceof DefinitionInterface) {
            $definition->withArguments($arguments);
        }

        if ($definition instanceof ClassDefinition) {
            $definition->withMethodCalls($methods);
        }
    }

    /**
     * Resolves the concrete class
     *
     * @param mixed $concrete
     *
     * @return mixed
     */
    protected function resolveConcrete($concrete)
    {
        if (is_array($concrete)) {
            if (array_key_exists('definition', $concrete)) {
                $concrete = $concrete['definition'];
            } elseif (array_key_exists('class', $concrete)) {
                $concrete = $concrete['class'];
            } else {
                $concrete = null;
            }
        }

        // if the concrete doesn't have a class associated with it then it
        // must be either a Closure or arbitrary type so we just bind that
        return $concrete;
    }
}
