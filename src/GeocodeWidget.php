<?php

/**
 * Geocode backend widget based on Leaflet.
 *
 * @package    netzmacht
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2018 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-leaflet-geocode-widget/blob/master/LICENSE
 * @filesource
 */


namespace Netzmacht\Contao\Leaflet\GeocodeWidget;

use Netzmacht\Contao\Leaflet\GeocodeWidget\Widget\GeocodeWidget as BaseWidget;

/**
 * Class GeocodeWidget
 *
 * @package Netzmacht\Contao\Leaflet\GeocodeWidget
 * @deprecated
 */
class GeocodeWidget extends BaseWidget
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        @trigger_error(
            sprintf(
                '"%s" is deprecated and will be removed in version 2.0.0. Use "%s" instead.',
                GeocodeWidget::class,
                BaseWidget::class
            ),
            E_USER_DEPRECATED
        );
    }
}
