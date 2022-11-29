<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Leaflet\GeocodeWidget\Widget;

use Contao\BackendTemplate;
use Contao\StringUtil;
use Contao\TextField;

use function is_array;
use function is_numeric;
use function round;
use function trim;

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
     */
    public function generate(): string
    {
        $wrapperClass = $this->coordinates ? 'wizard' : '';

        if (! $this->multiple || ! $this->size) {
            $this->size = 1;
        } else {
            $wrapperClass .= ' wizard_' . $this->size;
        }

        if (! is_array($this->value)) {
            $this->value = [$this->value];
        }

        $buffer = '';

        for ($index = 0; $index < $this->size; $index++) {
            $template = new BackendTemplate($this->widgetTemplate);
            $template->setData(
                [
                    'wrapperClass' => trim($wrapperClass),
                    'widget'       => $this,
                    'value'        => StringUtil::specialchars($this->value[$index]),
                    'class'        => $this->strClass ? ' ' . $this->strClass : '',
                    'id'           => $this->strId . ($this->size > 1 ? '_' . $index : ''),
                    'name'         => $this->strName . ($this->size > 1 ? '[]' : ''),
                    'attributes'   => $this->getAttributes(),
                    'wizard'       => $this->wizard,
                    'label'        => $this->strLabel,
                    'coordinates'  => $this->coordinates,
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
            $varInput = $steps * round($varInput / $steps);
        }

        return parent::validator($varInput);
    }
}
