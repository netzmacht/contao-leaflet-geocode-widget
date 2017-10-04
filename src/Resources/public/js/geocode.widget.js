/**
 * Geocode backend widget based on Leaflet.
 *
 * @package    netzmacht
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-leaflet-geocode-widget/blob/master/LICENSE
 * @filesource
 */

var LeafletGeocodeWidget = L.Class.extend({
    options: {
        mapTemplate: '<div id="leaflet_geocode_widget_map_{id}" class="" style="width 100%; height: 50vh; min-height: 400px"></div>',
        modalWidth: 800,
        modalTitle: 'Choose coordinates',
        searchPositionLabel: 'Search',
        applyPositionLabel: 'Apply'
    },
    initialize: function (options) {
        L.Util.setOptions(this, options);

        this.element = $(this.options.id);
        this.toggle  = $(this.options.id + '_toggle');
        this.toggle.addEvent('click', this._showMap.bind(this));
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
        var map = L.map('leaflet_geocode_widget_map_' + this.options.id).setView([0, 0], 2);

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

            map.setZoom(16);
            map.panTo(value);
        }

        return map;
    },
    _handleGeoCode: function (modal, map, event) {
        var container = document.createElement('div');
        var link      = document.createElement('button');
        var result    = event.geocode;

        link.set('style', 'margin-left: 10px;');
        link.appendText(this.options.applyPositionLabel);
        link.addEvent('click', function (e) {
            e.stop();

            this.element.set('value', result.center.lat + ',' + result.center.lng);
            modal.hide();
        }.bind(this));

        container.appendHTML(result.html || result.name);
        container.appendChild(link);

        if (this._geocodeMarker) {
            map.removeLayer(this._geocodeMarker);
        }

        map.fitBounds(result.bbox, { padding: [0, 70]});
        map.panTo(result.center);

        this._createMarker(result.center, map);
        this._geocodeMarker.bindPopup(container, {
            keepInView: true,
            autoPanPaddingTopLeft: [0, 70]
        }).openPopup();
    },
    _createMarker: function (position, map) {
        this._geocodeMarker = L.marker(position, {draggable: true}).addTo(map);
        this._geocodeMarker.on('dragend', function (event) {
            this.element.set('value', event.target._latlng.lat + ',' + event.target._latlng.lng);
        }.bind(this));
    }
});
