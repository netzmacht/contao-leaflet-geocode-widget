Leaflet geocode widget
======================

[![Build Status](http://img.shields.io/travis/netzmacht/contao-leaflet-geocode-widget/master.svg?style=flat-square)](https://travis-ci.org/netzmacht/contao-leaflet-geocode-widget)
[![Version](http://img.shields.io/packagist/v/netzmacht/contao-leaflet-geocode-widget.svg?style=flat-square)](http://packagist.org/packages/netzmacht/contao-leaflet-geocode-widget)
[![License](http://img.shields.io/packagist/l/netzmacht/contao-leaflet-geocode-widget.svg?style=flat-square)](http://packagist.org/packages/netzmacht/contao-leaflet-geocode-widget)
[![Downloads](http://img.shields.io/packagist/dt/netzmacht/contao-leaflet-geocode-widget.svg?style=flat-square)](http://packagist.org/packages/netzmacht/contao-leaflet-geocode-widget)
[![Contao Community Alliance coding standard](http://img.shields.io/badge/cca-coding_standard-red.svg?style=flat-square)](https://github.com/contao-community-alliance/coding-standard)

This extension provides an widget to pick coordinates from a map. It uses the leaflet framework.

Changlog
--------

See [CHANGELOG](CHANGELOG.md).

Requirements
------------

 - Contao ~4.4


Install
-------

### 1. Install using composer

```bash
php composer.phar require netzmacht/contao-leaflet-geocode-widget

```

### 2. Update your AppKernel.php
git
If you use the managed edition of Contao you can skip this step.

```php

  // Dependency is automatically installed and has to be registered
  new Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle('leaflet-libs', $this->getRootDir()),
  
  // Register the bundle
  new Netzmacht\Contao\Leaflet\GeocodeWidget\LeafletGeocodeWidgetBundle(),
```

### 3. Update the assets

```bash
bin/console assets:install --symlink
```

### 4. Use the widget

#### Coordinates only

```php
$GLOBALS['TL_DCA']['tl_example']['fields']['coordinates'] = [
    'label'     => ['Koordinaten', 'Geben Sie die Koordinaten ein'],
    'inputType' => 'leaflet_geocode',
    'eval'      => [
        'tl_class' => 'w50',
    ],
    'sql' => 'varchar(255) NOT NULL default \'\''
];
```

#### Coordinates and radius

To pick the radius in meters as well, you have to configure the `eval.radius` option for the related radius field.
The radius field should be a simle text input. The `default`, `minval` and `maxval` flags are passed to the geocode 
widget so that only radius in that boundary can be chosen.

```php
$GLOBALS['TL_DCA']['tl_page']['fields']['coordinates'] = [
    'label'     => ['Koordinaten', 'Geben Sie die Koordinaten ein'],
    'inputType' => 'leaflet_geocode',
    'eval'      => [
        'tl_class' => 'w50',
        'radius'   => 'radius'
    ],
    'sql' => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_page']['fields']['radius'] = [
    'label'     => ['Radius', 'Angabe des Radius in Metern'],
    'inputType' => 'text',
    'eval'      => [
        'rgxp'     => 'natural',
        'default'  => 500,
        'minval'   => 100,
        'maxval'   => 5000,
        'tl_class' => 'w50',
    ],
    'sql' => 'varchar(255) NOT NULL default \'\''
];
```

If you want to add an wizard icon to the radius field as well, a wizard is shipped with. You only have to add the 
callback and define the coordinates field relation. 

```php
$GLOBALS['TL_DCA']['tl_page']['fields']['radius'] = [
    'label'     => ['Radius', 'Angabe des Radius in Metern'],
    'inputType' => 'text',
    'eval'      => [
        'rgxp'        => 'natural',
        'default'     => 500,
        'minval'      => 100,
        'maxval'      => 5000,
        'tl_class'    => 'w50 wizard',
        'coordinates' => 'coordinates'
    ],
    'wizard' => [
        ['netzmacht.contao_leaflet_geocode.listeners.radius_wizard', 'generateWizard'],
    ],
    'sql' => 'varchar(255) NOT NULL default \'\''
];
```
