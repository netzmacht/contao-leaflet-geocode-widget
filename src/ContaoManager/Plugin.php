<?php

/**
 * @package    netzmacht
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace Netzmacht\Contao\Leaflet\GeocodeWidget\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Netzmacht\Contao\Leaflet\GeocodeWidget\LeafletGeocodeWidgetBundle;

/**
 * Class Plugin
 *
 * @package Netzmacht\Contao\Leaflet\GeocodeWidget\ContaoManager
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(LeafletGeocodeWidgetBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
