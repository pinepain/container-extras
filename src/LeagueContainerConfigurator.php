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


class LeagueContainerConfigurator extends AbstractContainerConfigurator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureMany($config)
    {
        foreach ($config as $alias => $options) {
            $this->configureOne($alias, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOne($alias, $options)
    {
        $concrete = $this->resolveConcrete($options);

        $share = is_array($options) && !empty($options['share']);

        // define in the container, with constructor arguments and method calls
        $definition = $this->container->add($alias, $concrete, $share);

        if ($definition instanceof DefinitionInterface) {
            $this->addDefinitionArguments($definition, $options);
        }

        if ($definition instanceof ClassDefinition) {
            $this->addDefinitionMethods($definition, $options);
        }
    }

    /**
     * @param DefinitionInterface $definition
     * @param                     $options
     */
    protected function addDefinitionArguments(DefinitionInterface $definition, $options)
    {
        $arguments = [];

        if (is_array($options)) {
            $arguments = (array_key_exists('arguments', $options)) ? (array)$options['arguments'] : [];
        }

        $definition->withArguments($arguments);
    }

    /**
     * @param ClassDefinition $definition
     * @param                 $options
     */
    protected function addDefinitionMethods(ClassDefinition $definition, $options)
    {
        $methods = [];

        if (is_array($options)) {
            $methods = (array_key_exists('methods', $options)) ? (array)$options['methods'] : [];
        }

        $definition->withMethodCalls($methods);
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
