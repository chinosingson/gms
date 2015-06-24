<?php 
	$node = menu_get_object(); 
	@$nid = $node->nid;
?>
<?php
	// something
	dpm($form);
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
  
	//hide($form['field_project_number']);
	hide($form['field_outputs']['#title']); //['#title_display'] = 'invisible';
	hide($form['field_impact_stories']['#title']); //['#title_display'] = 'invisible';
	//$form['field_project_year_start']['und'][0]['#title'] = t('Start');
	//$form['field_project_year_end']['#title'] = t('End');
	//hide($form['group_tab_general']);
	
	//echo print_r($form['actions']);
?>
<div id="project-page" class="form-container container-fluid">
	<div class="row" id="project-title-container">
		<div id="project-title" class="col-sm-9"><?php print drupal_render($form['title']); ?></div>
		<div id="project-function-buttons" class="col-sm-3 pull-right">
			<?php print drupal_render($form['actions']['submit']); ?>
		</div>
	</div>
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
					<?php //print drupal_render($form['field_photos']) ?>
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
		</div>
	</div>
</div>