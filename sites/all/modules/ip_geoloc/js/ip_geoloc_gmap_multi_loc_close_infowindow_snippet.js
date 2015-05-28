			// modified by Chino on 2015-05-19
			var infowindow;
      function addMarkerBalloon(map, marker, infoText) {
        google.maps.event.addListener(marker, 'click', function(event) {
					console.log('marker balloon open');
					if (infowindow) infowindow.close();
          infowindow = new google.maps.InfoWindow({
            content: infoText,
            // See [#1777664].
            maxWidth: 200
          });
					infowindow.open(map, marker);
        });
      }
