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

/**
 * Class GeocodeWidget
 *
 * @property int  size
 * @property bool multiple
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
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function validator($value)
    {
        $value = parent::validator($value);

        if (!$value) {
            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = $this->validator($val);
            }

            return $value;
        }

        // See: http://stackoverflow.com/a/18690202
        if (!preg_match(
            '#^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)(,[-+]?\d+)?$#',
            $value
        )) {
            $this->addError(
                sprintf(
                    $GLOBALS['TL_LANG']['ERR']['leafletInvalidCoordinate'],
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
        $wrapperClass = 'wizard';

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
            $template = new \BackendTemplate($this->widgetTemplate);
            $template->setData(
                [
                    'wrapperClass' => $wrapperClass,
                    'widget'       => $this,
                    'value'        => \StringUtil::specialchars($this->value[$index]),
                    'class'        => $this->strClass ? ' ' . $this->strClass : '',
                    'id'           => $this->strId . (($this->size > 1) ? '_' . $index : ''),
                    'name'         => $this->strName . (($this->size > 1) ? '[]' : ''),
                    'attributes'   => $this->getAttributes(),
                    'wizard'       => $this->wizard,
                    'label'        => $this->strLabel,
                ]
            );

            $buffer .= $template->parse();
        }

        return $buffer;
    }
}
