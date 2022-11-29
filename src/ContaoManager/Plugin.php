<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Leaflet\GeocodeWidget\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Netzmacht\Contao\Leaflet\GeocodeWidget\LeafletGeocodeWidgetBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(LeafletGeocodeWidgetBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
