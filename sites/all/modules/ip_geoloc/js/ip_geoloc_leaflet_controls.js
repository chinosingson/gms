
// Inspired by Roger Codina and
// http://gis.stackexchange.com/questions/87199/how-does-one-reset-map-fitbounds-zoomtofeature-to-the-original-zoom

(function ($) {

  Drupal.behaviors.leafletAddMapReset = {
    attach: function (context, settings) {
      var maps = settings.leaflet;
      for (var i = 0; i < maps.length; i++) {
        var map = maps[i].lMap;
        var mapSettings = maps[i].map.settings;
        if (map && mapSettings) {
          if (mapSettings.zoomIndicator) {
            map.addControl(L.control.zoomIndicator(mapSettings.zoomIndicator));
          }
          if (mapSettings.resetControl) {
            map.addControl(L.control.reset(mapSettings.resetControl));
          }
          if (mapSettings.scaleControl) {
            map.addControl(L.control.scale(mapSettings.scaleControl));
          }
        }
      }
    }
  }
})(jQuery);

L.Control.Reset = L.Control.extend({

  options: {
    position: 'topleft',
    label: 'R'
  },

  onAdd: function(map) {
    map._initialBounds = map.getBounds();

    var button = L.DomUtil.create('a', 'leafet-control-reset leaflet-bar');
    if (this.options.label.length <= 2) {
      button.innerHTML = this.options.label;
    }
    else {
      L.DomUtil.addClass(button, this.options.label);
    }
    button.setAttribute('title', Drupal.t('Reset'));
    button.onclick = function() {
      map.fitBounds(map._initialBounds);
    };
    return button;
  },
});

L.control.reset = function(options) {
  return new L.Control.Reset(options);
};

L.Control.ZoomIndicator = L.Control.extend({

  options: {
    position: 'topleft'
  },

  onAdd: function(map) {
    this.indicator = L.DomUtil.create('div', 'leafet-control-zoom-indicator leaflet-bar');
    this.indicator.setAttribute('title', Drupal.t('Zoom level'));
    this.indicator.innerHTML = 'z' + map.getZoom();
    map.on('zoomend', this.update, this);
    return this.indicator;
  },

  update: function(event) {
    this.indicator.innerHTML = event.target.getZoom();
  }
});

L.control.zoomIndicator = function(options) {
  return new L.Control.ZoomIndicator(options);
};
