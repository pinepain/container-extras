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


use League\Container\ImmutableContainerInterface;


interface AliasContainerInterface extends ImmutableContainerInterface
{
    /**
     * Add entry alias.
     *
     * @param string $alias    Entry alias.
     * @param string $concrete Identifier of the entry to look for.
     *
     */
    public function add($alias, $concrete);
}
