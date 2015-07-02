<?php 
	$node = menu_get_object(); 
	@$nid = $node->nid;
	global $base_url;
	
	drupal_add_css(libraries_get_path('leaflet') . '/leaflet.css', array('type'=>'file','group'=>CSS_DEFAULT));
	drupal_add_js(libraries_get_path('leaflet') . '/leaflet.js');
	//drupal_add_js('http://maps.google.com/maps/api/js?v=3.2&sensor=false');
	drupal_add_js('http://matchingnotes.com/javascripts/leaflet-google.js');
	drupal_add_css(libraries_get_path('leaflet.draw').'/dist/leaflet.draw.css', array('type'=>'file', 'group'=>CSS_DEFAULT));
	drupal_add_js(libraries_get_path('leaflet.draw').'/dist/leaflet.draw.js');
	drupal_add_js(base_path(). drupal_get_path('theme', 'gms'). '/js/map.js');
	drupal_add_js(array('pathToTheme' => array('pathToTheme' => $base_url."/".path_to_theme())), 'setting');

	drupal_add_css('
		#project-map-container { height: 300px; }
		#map-canvas { width: 100%; height: 300px; }
		.leaflet-map-pane { border: 0px dotted red; width: 100%; height: 300px; z-index: 1 !important; }
		.leaflet-google-layer { z-index: 0 !important; }
		.leaflet-top.leaflet-left .leaflet-control-zoom,
		.leaflet-top.leaflet-right .leaflet-draw.leaflet-control {
			margin-top: 10px !important;
		}	  
	  ', array('type'=>'inline'));

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
						'#options' => array('attributes' => array('class' => 'btn btn-primary', 'id' => 'btn-edit'), 'html' => FALSE, 'language'=>LANGUAGE_NONE),
					);
					
					print drupal_render($form['actions']['submit']);
					print l($link_cancel['#text'],$link_cancel['#path'],$link_cancel['#options']);

			?>
		</div>
	</div>
	<div id="project-info-container">
		<div class="row">
			<div id="project-map" class="col-lg-12">
			<div id="project-map-container"><div id="map-canvas"></div></div>
			<?php
			
				if (count($node->field_project_locations)>0){
					echo print_r($node->field_project_locations,1)."<br/>";
				} else {
					// Project Location Views
					$view_map = views_get_view('leaflet_view_test');			// locations
					$view_map->set_display('block_1');
					//$view_map->set_arguments(array($nid));
					$view_map_result = views_get_view_result('leaflet_view_test','block_1', $nid);
					$x=0;
					$markers = array();
					foreach ($view_map_result as $locations){
						//if (count($coord_info)<>0){
						if (is_object($locations)){
							$coord_info = $locations->field_field_google_coordinates;
							//$latlngpair = number_format($coord_info[0]['raw']['lat'], 2, '.', '').",".number_format($coord_info[0]['raw']['lng'], 2, '.', '');
							$latlngpair = array((float)number_format($coord_info[0]['raw']['lng'], 4, '.', ''),(float)number_format($coord_info[0]['raw']['lat'], 4, '.', ''));
							//$latlngs[]=$latlngpair;
							//$markers[]=array('type'=>'Feature','Properties'=>array('id'=>'marker_'.$x),'geometry'=>array('type'=>'Point','coordinates'=>$latlngpair));
							
							$markers[] = array(
								"type"=>"Feature",
								"properties"=> array(
									"id"=> 'marker_'.$x,
								),
								"geometry"=>array(
									"type"=>"Point",
									"coordinates"=>$latlngpair
								)
							);
							//$markers[]=array('id'=>"marker_".$x,'lat'=>number_format($coord_info[0]['raw']['lat']),'lon'=>number_format($coord_info[0]['raw']['lng']));
							//print $x.". ".$latlngpair."<br/>";
							//if ($x == 50) break 1;
							$x++;
						}
				
						//echo print_r($locations->field_field_google_coordinates,1)."<br/>";
					}

					if (count($markers) > 0){
						drupal_add_js(array('locations' => array('markers'=>$markers)),'setting'); // json_encode($markers);
					} else {
						drupal_add_js(array('locations' => array('markers'=>NULL)),'setting'); // json_encode($markers);
					}
				}
				
				if(@count($node->field_adb_sector) > 0){
					$projectSector = taxonomy_term_load($node->field_adb_sector['und'][0]['tid']);
					$markerPath = $projectSector->field_marker_path['und'][0]['value'];
					drupal_add_js(array('mapMarker' => array('imagePath' => $base_url."/".$markerPath)), 'setting');
				} else {
					drupal_add_js(array('mapMarker' => array('imagePath' => $base_url."/".path_to_theme()."/js/leaflet/images/marker-icon.png")), 'setting');
				}
			
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
			?><!--/div-->
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
					<?php print drupal_render($form['field_photos']); ?>
					<?php //print_r($form['field_photos']); ?>
				</div>
			</div>
			<div class="row">
				<div id="project-outputs" class="col-sm-4">
					<!--h4>Outputs</h4-->
					<?php print drupal_render($form['field_outputs']) ?>
				</div>
				<div id="project-impact-stories" class="col-sm-8">
					<!--h4>Impact Stories</h4-->
					<?php print drupal_render($form['field_impact_stories']) ?>
				</div>
			</div>	
		<?php print drupal_render_children($form); ?>
		</div>
	</div>
</div>
