<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Leaflet\GeocodeWidget;

use Netzmacht\Contao\Leaflet\GeocodeWidget\Widget\GeocodeWidget as BaseWidget;

class GeocodeWidget extends BaseWidget
{
    /**
     * {@inheritdoc}
     */
    public function __construct(?array $arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        // @codingStandardsIgnoreStart
        @trigger_error(
            sprintf(
                '"%s" is deprecated and will be removed in version 2.0.0. Use "%s" instead.',
                GeocodeWidget::class,
                BaseWidget::class
            ),
            E_USER_DEPRECATED
        );
        // @codingStandardsIgnoreEnd
    }
}
