<?php

declare(strict_types=1);

use Netzmacht\Contao\Leaflet\GeocodeWidget\Widget\GeocodeWidget;
use Netzmacht\Contao\Leaflet\GeocodeWidget\Widget\RadiusWidget;

$GLOBALS['BE_FFL']['leaflet_geocode'] = GeocodeWidget::class;
$GLOBALS['BE_FFL']['leaflet_radius']  = RadiusWidget::class;
