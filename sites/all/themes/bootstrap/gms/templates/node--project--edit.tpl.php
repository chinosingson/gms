<?php 
	$node = menu_get_object(); 
	@$nid = $node->nid;

	//drupal_add_js(base_path().drupal_get_path('theme', 'gms') . '/js/leaflet/leaflet.js');
	//drupal_add_css(base_path().drupal_get_path('theme', 'gms') . '/js/leaflet/leaflet.css');
	drupal_add_css('http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css');
	drupal_add_js('http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js');
	drupal_add_js(base_path(). drupal_get_path('theme', 'gms'). '/js/leaflet/app.js');

	drupal_add_css('
	
	  #project-map-container { height: 300px; }
      #map-canvas { width: 100%; height: 300px; z-index: 1;}
	  
	  ', 'inline');
	
	drupal_add_js("jQuery(document).ready(function(){
			
        var map;
		var ctiMapCenter = new google.maps.LatLng(9.622, 111.137);

		function HomeControl(controlDiv, map){
			/* code for Center custom control */
		  // Set CSS styles for the DIV containing the control
		  // Setting padding to 5 px will offset the control
		  // from the edge of the map
		  controlDiv.style.padding = '5px';

		  // Set CSS for the control border
		  var controlUI = document.createElement('div');
		  controlUI.style.backgroundColor = 'white';
		  controlUI.style.borderStyle = 'solid';
		  controlUI.style.borderWidth = '1px';
		  controlUI.style.borderColor = '#aaa';
		  controlUI.style.cursor = 'pointer';
		  controlUI.style.textAlign = 'center';
		  controlUI.title = 'Click to set the map to Home';
		  controlDiv.appendChild(controlUI);

		  // Set CSS for the control interior
		  var controlText = document.createElement('div');
		  controlText.style.fontFamily = 'Arial,sans-serif';
		  controlText.style.fontSize = '12px';
		  controlText.style.paddingLeft = '4px';
		  controlText.style.paddingRight = '4px';
		  controlText.innerHTML = '<b>Center on CTI</b>';
		  controlUI.appendChild(controlText);

		  // Setup the click event listeners: simply set the map to
		  // Chicago
		  google.maps.event.addDomListener(controlUI, 'click', function() {
			map.setCenter(ctiMapCenter)
		  });
			/* code for Center custom control */
		}

		function initialize() {
			var mapDiv = document.getElementById('map-canvas');
			var markers = [];
			var mapOptions = {
				center: ctiMapCenter,
				//center: new google.maps.LatLng(0, 0),
				zoom: 5,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: true,
				panControl: true,
				streetViewControl: false,
				overviewMapControl: true,
				overviewMapControlOptions: {
					opened: true,
				},
				mapTypeControl: false,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.DEFAULT,
					position: google.maps.ControlPosition.BOTTOM_CENTER
				},

				panControl: true,
				panControlOptions: {
					position: google.maps.ControlPosition.LEFT_BOTTOM
				},
				zoomControl: true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.LARGE,
					position: google.maps.ControlPosition.LEFT_BOTTOM
				},				
			};

			map = new google.maps.Map(mapDiv, mapOptions);
			
			// keep map centered on coral triangle on resize
			google.maps.event.addDomListener(window, 'resize', function() {
				var center = map.getCenter();
				google.maps.event.trigger(map, 'resize');
				map.setCenter(center); 
			});

			// Create the DIV to hold the control and
			// call the homeControl() constructor passing
			// in this DIV.
			var homeControlDiv = document.createElement('div');
			var homeControl = new HomeControl(homeControlDiv, map);

			homeControlDiv.index = 2;
			map.controls[google.maps.ControlPosition.LEFT_CENTER].push(homeControlDiv);
			
			
			

			/*var myParser = new geoXML3.parser({//*** ORIGINAL: only {map: map});
                map: map, singleInfoWindow: true,
                createMarker: function(placemark) {
                    //Constructing marker for each Placemark node, and then add it to the markclusterer
                    var point = new google.maps.LatLng(placemark.point.lat, placemark.point.lng);
                    var marker = new google.maps.Marker({position: point});
                    mc.addMarker(marker);

                    google.maps.event.addListener(marker, 'click', function() {
                        var marker_lat = marker.getPosition().lat();
                        var marker_lng = marker.getPosition().lng();
                        infoWindow.close();
                        infoWindow.setOptions({maxWidth: 800});
                        content = '<strong>' + placemark.name + '</strong><br>' + placemark.description;
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });
                    mc.addMarker(marker);
                }
            });

            myParser.parse('http://www.ctimap.org/locations/kml/cti.kml');*/

			

			/*var mcOptions = {gridSize: 50, maxZoom: 15};
			getJSONP('http://www.ctimap.org/locations/json&format=js&callback=?', function(data){
				console.log(data);
			});  
			for (var i = 0; i < 100; i++) {
			  var locationLocations = location.locations[i];
			  var latLng = new google.maps.LatLng(locationLocations.field_coordinates,
				  locationLocations.field_coordinates_1);
			  var marker = new google.maps.Marker({
				position: latLng
			  });
			  markers.push(marker);
			}
			var mc = new MarkerClusterer(map, markers, mcOptions);*/
			var kmlLayer = new google.maps.KmlLayer('http://www.ctimap.org/locations/kml/cti.kml');
			kmlLayer.setMap(map);
						
		}
	
		google.maps.event.addDomListener(window, 'load', initialize);
		
		

		
		});", 'inline');
?>
<?php
	// something
	//dpm($form);
  //print drupal_render_children($form);
	//hide($form['body']);
	//hide($form);
  //echo $myvar;
	
	//print drupal_render($form['title']);
  //print drupal_render($form['field_adb_sector']);
  //print drupal_render_children($form['field_adb_sector']);
	//$form = drupal_get_form('project_node_form');
	//print render($form['title']);
	//echo "<pre>".print_r($form,1)."</pre>";
  //print drupal_render_children($form['field_custom_image']);
  
	// ALTER FORM LABELS
	$form['field_project_year_start']['und'][0]['value']['year']['#title'] = 'Start';
	$form['field_project_year_end']['und'][0]['value']['year']['#title'] = 'End';
	// HIDE UNNEEDED FIELDS
	//hide($form['field_project_number']);
	//hide($form['field_outputs']['#title']); //['#title_display'] = 'invisible';
	hide($form['field_photos']['#title']); //['#title_display'] = 'invisible';
	//$form['field_outputs']['#title_display'] = 'invisible';
	//$form['field_project_cost_adb']['#prefix'] = "";
	//hide($form['group_tab_general']);
	
	//echo print_r($form['actions']);
?>
<div id="project-page" class="form-container container-fluid">
	<div class="row" id="project-title-container">
		<div id="project-title" class="col-sm-9"><?php print drupal_render($form['title']); ?></div>
		<div id="project-function-buttons" class="col-sm-3 pull-right">
			<?php 
					$link_cancel = array(
						'#theme' => 'link',
						'#text' => 'Cancel',
						'#path' => drupal_get_path_alias('node/'.$nid),
						'#options' => array('attributes' => array('class' => 'btn btn-primary', 'id' => 'btn-edit')),
					);
					
					print drupal_render($form['actions']['submit']);
					print render($link_cancel);

			?>
		</div>
	</div>
	<div id="project-map-container"><div id="map-canvas"></div></div>
	<div id="project-info-container">
		<div class="row">
			<div id="project-map" class="col-lg-12"><?php
				// Project Views
				$view_map = views_get_view('leaflet_view_test');			// locations
				$view_map->set_display('block_1');
			
			
			if($view_map){ 
				//$block = module_invoke('views', 'leaflet_view_test', 'block_1');
				//if ($block){
				//print render($block['content']);
				//}
				//print views_embed_view('leaflet_view_test', 'block_1', $nid);
				//$content .= views_embed_view('leaflet_view_test', 'block_1', $nid);
				$view_map->set_arguments(array($nid)); $view_map->pre_execute(); $view_map->execute();
				//print $view_map->render(); 
			}
			?></div>
		</div>
		<div class="project-info">
			<div class="row">
				<div id="project-details" class="col-sm-4">
					<h4 class="">Details</h4>
					<?php //echo "DETAILS" ?>
					<?php print drupal_render($form['field_project_number']['und'][0]['value']);?>
					<?php print drupal_render($form['field_project_type']);?>
					<?php print drupal_render($form['field_adb_sector']);?>
					<?php print drupal_render($form['field_country']);?>
					<?php print drupal_render($form['field_project_year_start']['und'][0]['value']['year']);?>
					<?php print drupal_render($form['field_project_year_end']['und'][0]['value']['year']);?>
					<?php print drupal_render($form['field_project_status']);?>
					<?php print drupal_render($form['field_project_adb_website']);?>
				</div>
				<div id="project-funding-details" class="col-sm-4">
					<h4>Funding</h4>
					<div id="project-funding-total">
						<span class='funding-total'><?php //print drupal_render($form['field_project_cost_total']); ?></span>
					</div>
					<div id="project-funding-chart" class="chart">
						<?php print drupal_render($form['field_project_cost_total']); ?>
						<?php print drupal_render($form['field_project_cost_adb']); ?>
						<?php print drupal_render($form['field_project_cost_government']); ?>
						<?php print drupal_render($form['field_project_cost_cofinancing_']); ?>
					</div>
				</div>
				<div id="project-photos" class="col-sm-4">
					<h4>Photos</h4>
					<?php print drupal_render($form['field_photos']) ?>
					<?php //print_r($form['field_photos']); ?>
				</div>
			</div>
			<div class="row">
				<div id="project-outputs" class="col-sm-4">
					<h4>Outputs</h4>
					<?php print drupal_render($form['field_outputs']) ?>
				</div>
				<div id="project-impact-stories" class="col-sm-8">
					<h4>Impact Stories</h4>
					<?php print drupal_render($form['field_impact_stories']) ?>
				</div>
			</div>	
		<?php print drupal_render_children($form); ?>
		</div>
	</div>
</div>
