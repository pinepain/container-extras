<?php


namespace Pinepain\Container\Extras;

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
