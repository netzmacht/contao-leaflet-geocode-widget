/**
 * Geocode backend widget based on Leaflet.
 *
 * @package    netzmacht
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2018 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-leaflet-geocode-widget/blob/master/LICENSE
 * @filesource
 */

var LeafletGeocodeWidget = L.Class.extend({
    options: {
        mapTemplate: '<div id="leaflet_geocode_widget_map_{id}" class="" style="width 100%; height: 50vh; min-height: 400px"></div>',
        modalWidth: 800,
        modalTitle: 'Choose coordinates',
        searchPositionLabel: 'Search',
        applyPositionLabel: 'Apply',
        radius: null,
        mapOptions: {
            maxZoom: 15,
            minZoom: 2
        },
        bboxPadding: [0, 70]
    },
    initialize: function (options) {
        L.Util.setOptions(this, options);

        this.element = $(this.options.id);
        this.toggle  = $(this.options.id + '_toggle');
        this.toggle.addEvent('click', this._showMap.bind(this));

        if (this.options.radius) {
            this.radius = $(this.options.radius);
        }
    },
    _showMap: function () {
        var content, modal;

        // Create modal window.
        content = L.Util.template(this.options.mapTemplate, this.options);
        modal   = this._createModal();

        modal.show({'title': this.options.modalTitle, 'contents': content});

        // Initialize map after showing modal so element exists.
        this._createMap(modal);
    },
    _createModal: function () {
        return new SimpleModal({
            'width': this.options.modalWidth,
            'hideFooter': true,
            'draggable': false,
            'overlayOpacity': .5,
            'onShow': function () {
                document.body.setStyle('overflow', 'hidden');
            },
            'onHide': function () {
                document.body.setStyle('overflow', 'auto');
            }
        });
    },
    _createMap: function (modal) {
        var map = L.map('leaflet_geocode_widget_map_' + this.options.id, this.options.mapOptions).setView([0, 0], 2);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var geoCoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            collapsed: false,
            placeholder: this.options.searchPositionLabel
        }).addTo(map);

        geoCoder.on('markgeocode', function (event) {
            this._handleGeoCode.call(this, modal, map, event);
        }.bind(this));

        if (this.element.value) {
            var value = this.element.value.split(/,/);
            this._createMarker(value, map);

            if (this.radius) {
                map.fitBounds(this._geocodeMarker.getBounds());
            } else {
                map.setZoom(this.options.mapOptions.maxZoom);
                map.panTo(value);
            }
        }

        return map;
    },
    _handleGeoCode: function (modal, map, event) {
        var container = document.createElement('div');
        var link      = document.createElement('button');
        var result    = event.geocode;

        link.set('class', 'leaflet-submit-btn');
        link.appendText(this.options.applyPositionLabel);
        link.addEvent('click', function (e) {
            e.stop();

            this._updateInputs(result.center);
            modal.hide();
        }.bind(this));

        container.appendHTML(result.html || result.name);
        container.appendChild(link);

        if (this._geocodeMarker) {
            map.removeLayer(this._geocodeMarker);
        }

        map.fitBounds(result.bbox, { padding: this.options.bboxPadding});
        map.panTo(result.center);

        this._createMarker(result.center, map);

        if (!this.radius) {
            this._geocodeMarker.bindPopup(container, {
                keepInView: true,
                autoClose: false,
                closeOnClick: false,
                autoPanPaddingTopLeft: this.options.bboxPadding
            }).openPopup();
        }
    },
    _createMarker: function (position, map) {
        if (this.radius) {
            this._geocodeMarker = L.circle(position, { radius: this.radius.get('value') });
            this._geocodeMarker.addTo(map);
            map.fitBounds(this._geocodeMarker.getBounds());

            this._geocodeMarker.on('pm:markerdragend', function (event) {
                var radius = Math.floor(this._geocodeMarker.getRadius());

                this._updateInputs(event.target._latlng, radius);
                this._geocodeMarker.pm._outerMarker.setTooltipContent(this._formatRadius(radius));
                map.fitBounds(this._geocodeMarker.getBounds());
            }.bind(this));
            this._geocodeMarker.pm.enable({dragable: false});

            this._geocodeMarker.pm._outerMarker.bindTooltip(
                this._formatRadius(this._geocodeMarker.getRadius()),
                {permanent: true, direction: 'right', offset: [10, 0] }
            );
        } else {
            this._geocodeMarker = L.marker(position, {draggable: true}).addTo(map);
            this._geocodeMarker.on('dragend', function (event) {
                this._updateInputs(event.target._latlng);
            }.bind(this));
        }
    },
    _updateInputs: function (coordinates, radius) {
        this.element.set('value', coordinates.lat + ',' + coordinates.lng);

        if (this.radius && typeof(radius) !== 'undefined') {
            this.radius.set('value', radius);
        }
    },
    _formatRadius: function (radius) {
        var unit = 'm';

        radius = Math.floor(radius);

        if (radius > 1000) {
            unit   = 'km';
            radius = (radius / 1000).toFixed(1);
        }

        return radius.toString() + ' ' + unit;
    }
});
