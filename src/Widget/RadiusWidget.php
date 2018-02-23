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

namespace Netzmacht\Contao\Leaflet\GeocodeWidget\Widget;

use Contao\BackendTemplate;
use Contao\TextField;

/**
 * Class RadiusWidget
 */
class RadiusWidget extends TextField
{
    /**
     * Template name.
     *
     * @var string
     */
    protected $widgetTemplate = 'be_widget_leaflet_radius';

    /**
     * {@inheritdoc}
     */
    public function __get($strKey)
    {
        if ($strKey === 'rgxp') {
            return 'natural';
        }

        return parent::__get($strKey);
    }

    /**
     * Generate the widget.
     *
     * @return string
     */
    public function generate()
    {
        $wrapperClass = $this->coordinates ? 'wizard' : '';

        if (!$this->multiple || !$this->size) {
            $this->size = 1;
        } else {
            $wrapperClass .= ' wizard_' . $this->size;
        }

        if (!is_array($this->value)) {
            $this->value = [$this->value];
        }

        $buffer = '';

        for ($index = 0; $index < $this->size; $index++) {
            $template = new BackendTemplate($this->widgetTemplate);
            $template->setData(
                [
                    'wrapperClass' => trim($wrapperClass),
                    'widget'       => $this,
                    'value'        => \StringUtil::specialchars($this->value[$index]),
                    'class'        => $this->strClass ? ' ' . $this->strClass : '',
                    'id'           => $this->strId . (($this->size > 1) ? '_' . $index : ''),
                    'name'         => $this->strName . (($this->size > 1) ? '[]' : ''),
                    'attributes'   => $this->getAttributes(),
                    'wizard'       => $this->wizard,
                    'label'        => $this->strLabel,
                    'coordinates'  => $this->coordinates
                ]
            );

            $buffer .= $template->parse();
        }

        return $buffer;
    }

    /**
     * {@inheritdoc}
     */
    protected function validator($varInput)
    {
        if (is_numeric($varInput) && $this->steps > 0) {
            $steps    = (int) $this->steps;
            $varInput = (int) $varInput;
            $varInput = ($steps * round($varInput / $steps));
        }

        return parent::validator($varInput);
    }
}
