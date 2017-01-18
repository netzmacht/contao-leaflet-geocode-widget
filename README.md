Leaflet geocode widget
======================

This extension provides an widget to pick coordinates from a map. It uses the leaflet framework.


Requirements
------------

Contao 4.3


Install
-------

### 1. Install using composer

```bash
php composer.phar require netzmacht/contao-leaflet-geocode-widget

```

### 2. Update your AppKernel.php

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

```php
$GLOBALS['TL_DCA']['tl_example']['fields']['coordinates'] = [
    'label'     => ['Koordinaten', 'Geben Sie die Koordinaten ein'],
    'inputType' => 'leaflet_geocode',
    'eval'      => [
        'tl_class' => 'w50 wizard',
    ],
    'sql' => 'varchar(255) NOT NULL default \'\''
];
```
