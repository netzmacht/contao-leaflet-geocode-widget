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

namespace Netzmacht\Contao\Leaflet\GeocodeWidget\EventListener;

use Contao\DataContainer;

/**
 * Class RadiusWizardCallbackListener
 *
 * @package Netzmacht\Contao\Leaflet\GeocodeWidget\EventListener
 */
class RadiusWizardCallbackListener
{
    /**
     * Generate the wizard for the radius widget.
     *
     * @param DataContainer $dataContainer Data container driver.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function generateWizard($dataContainer)
    {
        if (!isset($GLOBALS['TL_DCA'][$dataContainer->table]['fields'][$dataContainer->field]['eval']['coordinates'])) {
            return '';
        }

        return sprintf(
            '<a href="#" onclick="$(\'ctrl_%s_toggle\').click();return false;"><img src="%s"></a>',
            $GLOBALS['TL_DCA'][$dataContainer->table]['fields'][$dataContainer->field]['eval']['coordinates'],
            'bundles/leafletgeocodewidget/img/map.png'
        );
    }
}
