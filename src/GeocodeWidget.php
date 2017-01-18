<?php

/**
 * @package    netzmacht
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace Netzmacht\Contao\Leaflet\GeocodeWidget;

/**
 * Class GeocodeWidget
 *
 * @package Netzmacht\Contao\Leaflet\GeocodeWidget
 */
class GeocodeWidget extends \Widget
{
    /**
     * Submit user input.
     *
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Add a for attribute.
     *
     * @var boolean
     */
    protected $blnForAttribute = true;

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * Template name.
     *
     * @var string
     */
    protected $widgetTemplate = 'be_widget_leaflet_geocode';

    /**
     * Validate the input.
     *
     * @param mixed $value Given value.
     *
     * @return mixed
     */
    protected function validator($value)
    {
        $value = parent::validator($value);

        if (!$value) {
            return $value;
        }

        if (
            // See: http://stackoverflow.com/a/18690202
            !preg_match(
                '^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)(,[-+]?\d+)?$',
                $value
            )
        ) {
            $this->addError(
                sprintf(
                    $GLOBALS['TL_LANG']['ERR']['leaflet_invalid_coordinate'],
                    $value
                )
            );
        }

        return $value;
    }

    /**
     * Generate the widget.
     *
     * @return string
     */
    public function generate()
    {
        $template = new \BackendTemplate($this->widgetTemplate);
        $template->setData(
            [
                'widget'     => $this,
                'value'      => specialchars($this->value),
                'class'      => $this->strClass ? ' ' . $this->strClass : '',
                'id'         => $this->strId,
                'attributes' => $this->getAttributes(),
                'wizard'     => $this->wizard,
            ]
        );

        return $template->parse();
    }
}
