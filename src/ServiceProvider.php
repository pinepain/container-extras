<?php

/*
  +------------------------------------------------------------------------+
  | This file is part of the pinepain/league-container-extras PHP library. |
  |                                                                        |
  | Copyright (c) 2015-2016 Bogdan Padalko <pinepain@gmail.com>            |
  |                                                                        |
  | Licensed under the MIT license: http://opensource.org/licenses/MIT     |
  |                                                                        |
  | For the full copyright and license information, please view the        |
  | LICENSE file that was distributed with this source or visit            |
  | http://opensource.org/licenses/MIT                                     |
  +------------------------------------------------------------------------+
*/


namespace Pinepain\League\Container\Extras;


use League\Container\ServiceProvider\ServiceProviderAggregate;

/**
 * This implementation allows service providers constructor arguments resolving
 */
class ServiceProvider extends ServiceProviderAggregate
{
    /**
     * {@inheritdoc}
     */
    public function add($provider)
    {
        if (is_string($provider) && $this->getContainer()->has($provider)) {
            $provider = $this->getContainer()->get($provider);
        }

        parent::add($provider);
    }
}
