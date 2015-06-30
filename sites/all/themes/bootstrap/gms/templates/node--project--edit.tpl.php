<?php 
	$node = menu_get_object(); 
	@$nid = $node->nid;

	//drupal_add_css(base_path().drupal_get_path('theme', 'gms') . '/js/leaflet/leaflet.css', array('type'=>'file','group'=>CSS_THEME));
	//drupal_add_js(base_path().drupal_get_path('theme', 'gms') . '/js/leaflet/leaflet.js');
	//drupal_add_js('http://maps.google.com/maps/api/js?v=3.2&sensor=false');
	//drupal_add_js('http://matchingnotes.com/javascripts/leaflet-google.js');
	drupal_add_css('http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css', array('type'=>'external', 'group'=>CSS_DEFAULT));
	drupal_add_js('http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js');
	drupal_add_css(base_path().drupal_get_path('theme','gms').'/js/leaflet-draw/leaflet.draw.css', array('type'=>'file', 'group'=>CSS_DEFAULT));
	drupal_add_js(base_path().drupal_get_path('theme','gms').'/js/leaflet-draw/leaflet.draw.js');
	//drupal_add_js(base_path(). drupal_get_path('theme', 'gms'). '/js/leaflet/app.js');
	drupal_add_js(base_path(). drupal_get_path('theme', 'gms'). '/js/leaflet/appgms.js');

	drupal_add_css('
	
	  #project-map-container { height: 300px; }
      #map-canvas { width: 100%; height: 300px; z-index: 1; }
		.leaflet-map-pane { border: 1px dotted red; width: 100%; height: 300px; }
	  
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
	<div id="project-info-container">
		<div class="row">
			<div id="project-map" class="col-lg-12">
			<div id="project-map-container"><div id="map-canvas"></div></div>
			<!--div id="map-canvas"></div-->
			<?php
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
