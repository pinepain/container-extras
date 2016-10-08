<?php


namespace Pinepain\Container\Extras\Tests;


use Pinepain\Container\Extras\CompoundContainerConfigurator;


class CompoundContainerConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    public function testPopulateWithEmpty()
    {
        $league_configurator = $this->getLeagueConfiguratorMock();
        $alias_configurator  = $this->getAliasConfiguratorMock();

        $league_configurator->expects($this->never())
                            ->method('configureOne');

        $alias_configurator->expects($this->never())
                           ->method('configureOne');

        $config = [];

        $loader = new CompoundContainerConfigurator($league_configurator, $alias_configurator);

        $loader->configure($config);
    }

    public function testPopulate()
    {
        $league_configurator = $this->getLeagueConfiguratorMock();
        $alias_configurator  = $this->getAliasConfiguratorMock();

        $league_configurator->expects($this->once())
                            ->method('configureOne')
                            ->with('league alias', ['league options']);

        $alias_configurator->expects($this->once())
                           ->method('configureOne')
                           ->with('alias alias', 'alias concrete');

        $loader = new CompoundContainerConfigurator($league_configurator, $alias_configurator);

        $config = [
            'league alias' => ['league options'],
            'alias alias'  => 'alias concrete',
        ];

        $loader->configure($config);
    }

    /**
     * @return \Pinepain\Container\Extras\LeagueContainerConfigurator | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getLeagueConfiguratorMock()
    {
        return $this->getMockBuilder('\Pinepain\Container\Extras\LeagueContainerConfigurator')
                    ->disableOriginalConstructor()
                    ->setMethods(['configureOne'])
                    ->getMockForAbstractClass();
    }

    /**
     * @return \Pinepain\Container\Extras\AliasContainerConfigurator | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAliasConfiguratorMock()
    {
        return $this->getMockBuilder('\Pinepain\Container\Extras\AliasContainerConfigurator')
                    ->disableOriginalConstructor()
                    ->setMethods(['configureOne'])
                    ->getMockForAbstractClass();
    }
}
