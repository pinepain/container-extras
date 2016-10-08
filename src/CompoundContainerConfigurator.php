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

class CompoundContainerConfigurator extends AbstractContainerConfigurator
{
    /**
     * @var LeagueContainerConfigurator
     */
    private $leagueContainerConfigurator;
    /**
     * @var AliasContainerConfigurator
     */
    private $aliasContainerConfigurator;

    /**
     * @param LeagueContainerConfigurator $leagueContainerConfigurator
     * @param AliasContainerConfigurator  $aliasContainerConfigurator
     */
    public function __construct(
        LeagueContainerConfigurator $leagueContainerConfigurator,
        AliasContainerConfigurator $aliasContainerConfigurator
    ) {
        $this->leagueContainerConfigurator = $leagueContainerConfigurator;
        $this->aliasContainerConfigurator  = $aliasContainerConfigurator;
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
        if (is_string($options)) {
            $this->aliasContainerConfigurator->configureOne($alias, $options);
        } else {
            $this->leagueContainerConfigurator->configureOne($alias, $options);
        }
    }
}
