<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Leaflet\GeocodeWidget\EventListener;

use Contao\DataContainer;

use function sprintf;

class RadiusWizardCallbackListener
{
    /**
     * Generate the wizard for the radius widget.
     *
     * @param DataContainer $dataContainer Data container driver.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function generateWizard(DataContainer $dataContainer): string
    {
        $fields = $GLOBALS['TL_DCA'][$dataContainer->table]['fields'];

        if (! isset($fields[$dataContainer->field]['eval']['coordinates'])) {
            return '';
        }

        return sprintf(
            '<a href="#" onclick="$(\'ctrl_%s_toggle\').click();return false;"><img src="%s"></a>',
            $GLOBALS['TL_DCA'][$dataContainer->table]['fields'][$dataContainer->field]['eval']['coordinates'],
            'bundles/leafletgeocodewidget/img/map.png'
        );
    }
}
