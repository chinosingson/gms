<?php

	// GMS
	//drupal_add_js(libraries_get_path('bcarousel').'/carousel.js',array('group' => JS_THEME, 'every_page' => FALSE));

	// template for project nodes

	//$node = menu_get_object(); 
	$nid = $node->nid;
	
	//$args = arg($_GET['url']);
	//print_r($args);
	
	//print_r($page);
	
	
	/*$editPerm = user_access('Edit any content');
	if ($editPerm) {
		//echo "123";
		$btnEditDetails = "<a href=\"#overlay=node/".$nid."/edit\" class=\"btn btn-xs btn-default pull-right\" id=\"btn-edit-proj-details\">Edit</a>";
		$btnEditFunding = "<a href=\"#overlay=node/".$nid."/edit\" class=\"btn btn-xs btn-default pull-right\" id=\"btn-edit-proj-funding\">Edit</a>";
		$btnEditPhotos  = "<a href=\"#overlay=node/".$nid."/edit\" class=\"btn btn-xs btn-default pull-right\" id=\"btn-edit-proj-photos\">Edit</a>";
		$btnEditOutputs = "<a class=\"btn btn-xs btn-default pull-right\" id=\"btn-edit-proj-outputs\">Edit</a>";
		$btnEditImpacts = "<a class=\"btn btn-xs btn-default pull-right\" id=\"btn-edit-proj-impacts\">Edit</a>";
	} else {
		$btnEditDetails = "";
		$btnEditFunding = "";
		$btnEditPhotos  = "";
		$btnEditOutputs = "";
		$btnEditImpacts = "";
	}*/
	
	// Project Views
	$view_map = views_get_view('leaflet_view_test');			// locations
	$view_map->set_display('block_1');
	
	$view_proj_node_loc = views_get_view('project_node_locations');
	$view_proj_node_loc->set_display('block_proj_node_loc');
	
	$view_details = views_get_view('project_details');		// details
	$view_details->set_display('block');
	$view_funding = views_get_view('project_financing');	// funding
	
	$view_photos = views_get_view('project_photos');			// photos
	$view_photos->set_display('carousel1');
	$view_stories = views_get_view('impact_stories');			// impacts
	$view_stories->set_display('block_1');
	$view_outputs = views_get_view('outputs');						// outputs
	$view_outputs->set_display('block');
	
	$project_type = $node->field_project_type['und'][0]['taxonomy_term']->name;
	$project_status = $node->field_project_status['und'][0]['taxonomy_term']->name;
	// FUNDING
	// Pie Chart
	// sources
	$fundingSources = array(
		'adb'=> (!empty($node->field_project_cost_adb) ? $node->field_project_cost_adb['und'][0]['value'] : 0),
		'adb_jsf_jfpr'=> (!empty($node->field_project_cost_adb_jsf_jfpr) ? $node->field_project_cost_adb_jsf_jfpr['und'][0]['value'] : 0),
		'adb_tasf'=> (!empty($node->field_project_cost_adb_tasf) ? $node->field_project_cost_adb_tasf['und'][0]['value'] : 0),
		'gov'=> (!empty($node->field_project_cost_government) ? $node->field_project_cost_government['und'][0]['value'] : 0),
		'cof'=> (!empty($node->field_project_cost_cofinancing_) ? $node->field_project_cost_cofinancing_['und'][0]['value'] : 0),
	);
	
	//if ($fundingSources['adb_jsf_jfpr']>0 || $fundingSources['adb_tasf']>0){
	//	$header = array('ADB-JSF/JFPR','ADB-TASF','Government','Cofinancing');
	//	$chartFunding = array(
	//		'adb_jsf_jfpr'=> (!empty($node->field_project_cost_adb_jsf_jfpr) ? $node->field_project_cost_adb_jsf_jfpr['und'][0]['value'] : 0),
	//		'adb_tasf'=> (!empty($node->field_project_cost_adb_tasf) ? $node->field_project_cost_adb_tasf['und'][0]['value'] : 0),
	//		'gov'=> (!empty($node->field_project_cost_government) ? $node->field_project_cost_government['und'][0]['value'] : 0),
	//		'cof'=> (!empty($node->field_project_cost_cofinancing_) ? $node->field_project_cost_cofinancing_['und'][0]['value'] : 0),
	//	);
	//} else {
		$header = array('ADB','Government','Cofinancing');
		$chartFunding = array(
			'adb'=> (!empty($node->field_project_cost_adb) ? $node->field_project_cost_adb['und'][0]['value'] : 0),
			'gov'=> (!empty($node->field_project_cost_government) ? $node->field_project_cost_government['und'][0]['value'] : 0),
			'cof'=> (!empty($node->field_project_cost_cofinancing_) ? $node->field_project_cost_cofinancing_['und'][0]['value'] : 0),
		);
	//}
	
	//echo print_r($header,1);
	//echo print_r($chartFunding,1);
	
	if (arg(0) == 'print') {
		$chartFont = 'Arial';
		$chartFontColor = '#000000';
		$chartBgcolor = '#ffffff';
	} else {
		$chartFont = 'Source Sans Pro';
		$chartFontColor = '#ffffff';
		$chartBgcolor = '#3b444c';
	}
	
	// settings
	$chartSettings['chart']['chartOne'] = array(  
		'header' => $header,
		'rows' => array(array_values($chartFunding)),
		'columns' => array('Funding Sources'),
		'chartType' => 'PieChart',
		'containerId' =>  'project-funding-chart',
		'options' => array(
			'format' => '###,###',
			'fontSize'=>'16',
			'fontName'=>$chartFont,
			'backgroundColor'=>$chartBgcolor,
			'legend'=> array(
				'position'=>'right',
				'alignment'=>'center',
				'textStyle'=>array(
					'color'=>$chartFontColor
				)
			),
			'forceIFrame' => FALSE, 
			'pieSliceText'=> 'value',
			'pieSliceBorderColor' => 'transparent',
			'title' => '',
			'width' => 'auto',
			'height' => '300',
			//'colors' => ['#ff3333','#ff6600','#ffcc00','#99cc33','#33cc33','#66CCFF','#0066CC','#666699','#FF6699','#FFFF00','#00CCCC','#999999'],
			'colors' => ['#64994B','#3CB6A3','#188AD2','#356FAB','#9279BA','#7C569A','#A25462','#E25279','#E08255','#E2BB3C','#FBED90','#AFB363'],
			'chartArea' => array(
				'left'=>5,
				'top'=>7,
				'width'=>'82%',
				'height'=>'82%'
			)
		)   
	);
	
	// total - reformatted for block heading
	if (isset($node->field_project_cost_total['und'][0]['value'])){
		$fundingTotal = "US$('000) ".number_format($node->field_project_cost_total['und'][0]['value']);
	} else {
		$fundingTotal = "";
	}
	$show_title = $variables['title'];
?>

<div id="project-page" class="container-fluid">
	<div class="row" id="project-title-container">
		<div id="project-title" class="col-sm-12">
			<?php if ($show_title && $title): ?>
			<h2 class="title" id="page-title"><?php print $title; ?>
			<?php endif; ?>
			<span id="project-function-buttons"> <!-- class="col-sm-3 pull-right"-->
				<!--a href="#" class="btn btn-primary" id="btn-edit" class="disabled">Edit</a-->
				<?php 
					if (node_access('update',$node) && $user->uid != 0){
						//create link for current node edit
						$link_edit = array(
							'#theme' => 'link',
							'#text' => 'Edit',
							'#path' => 'node/' . $node->nid . '/edit',
							'#options' => array('attributes' => array('class' => 'btn btn-primary', 'id' => 'btn-edit'), 'html' => FALSE, 'language'=>LANGUAGE_NONE),
						);
						print render($link_edit);
					}
				?>
			</span></h2>
		</div>
	</div>
	<div id="project-info-container">
		<div class="row">
			<div id="messages"><?php if ($messages) print $messages; ?></div>
			<div id="project-map" class="col-lg-12"><?php
			
			//echo print_r($node->field_project_locations,1)."<br/>";
			//if($view_map){ 
			//if (count($view_proj_node_loc->result) > 0){
			if (count($node->field_project_locations) > 0){
				//echo "field_project_locations<br/>";
				//echo "<pre>".print_r($view_proj_node_loc,1)."</pre>";
				/*$projLoc = views_get_view_result('project_node_locations', 'block_proj_node_loc', $node->nid);
				//echo print_r($projLoc,1);
				foreach($projLoc as $result){
					echo "MERON PA!<br/>";
					echo print_r($result,1)."<br/>";
				}*/
				$view_proj_node_loc->set_arguments(array($nid)); $view_proj_node_loc->pre_execute(); $view_proj_node_loc->execute();
				print $view_proj_node_loc->render(); 
			} else {
				//echo "leaflet_view_test";
				//$block = module_invoke('views', 'leaflet_view_test', 'block_1');
				//if ($block){
				//print render($block['content']);
				//}
				//print views_embed_view('leaflet_view_test', 'block_1', $nid);
				//$content .= views_embed_view('leaflet_view_test', 'block_1', $nid);
				$view_map->set_arguments(array($nid)); $view_map->pre_execute(); $view_map->execute();
				print $view_map->render(); 
			}
			
			
			?></div>
		</div>
		<!--div id="body"><?php //print "node->body:".$node->body['und'][0]['value']; ?></div-->
		<!--div id="body"><?php //print "node->field_project_locations:".print_r($node->field_project_locations,1); ?></div-->
		<div class="project-info">
			<div class="row" id="project-row-1">
				<div id="project-details" class="col-sm-4">
					<h4 class=""><?php print $view_details->get_title(); ?> <?php //print $btnEditDetails ?></h4>
					<?php $view_details->set_arguments(array($nid)); $view_details->pre_execute(); $view_details->execute(); print $view_details->render(); ?>
				</div>
				<div id="project-funding-details" class="col-sm-4">
					<h4><?php print $view_funding->get_title(); ?><?php //print $node->field_project_cost_cofinancing_['und'][0]['value'] ?></h4>
					<div id="project-funding-total">
						<span class='funding-total'><?php echo $fundingTotal ?></span>
					</div>
					<?php if ($node->field_project_cost_cofinancing_['und'][0]['value'] > 0 && !is_null($node->field_project_source_cofinancing)): ?>
					<div id="project-source-confinancing">Confinancing Source: <?php print $node->field_project_source_cofinancing['und'][0]['value']; ?></div>
					<?php endif; ?>
					<div id="project-funding-chart" class="chart">
						<?php 
							if (array_sum($fundingSources) != 0) {
								$ret = draw_chart($chartSettings); 
								//echo "pie chart";
							} else {
								print "<div id=\"project-no-funding-sources\">Funding sources for this project have not been determined.<br/></div>";
							}	?>
					</div>
				</div>
				<div class="col-sm-4" id="project-description">
					<h4>Project Description</h4>
					<p><?php echo $node->body[LANGUAGE_NONE][0]['value']; ?></p>
					<!--p>The Asian Development Bank (ADB), at the request of the Government of the Republic of the Union of Myanmar, provided financial assistance for the improvement of 66.4 kilometer (km) of road between Eindu and Kawkareik, in Kayin State. The project road will be improved to the Greater Mekong Subregion (GMS) road network standard of two lanes with appropriate width paved shoulders, suitable for all standard highway traffic. The section will require complete reconstruction, with some short sections of realignment for improved vehicle operating speed and road safety. The project also includes design features for climate resilience in this flood-prone area, including raising the road to a level that takes into account potentially higher future flood levels to provide year-round access. </p -->
				</div>
			</div>
			<div class="row" id="project-row-2">
				<div id="project-progress" class="col-sm-4">
					<h4>Progress</h4>
					<div>
						<?php if ($project_status == "Future" || $project_status == "Ongoing"): ?>
						<table class="table table-condensed">
							<tbody>
								<?php if ($project_type == "Loan"): ?>
								<tr><th class="project-progress-title" colspan="2">Feasibility Study</th></tr>
								<tr>
									<th class="fs col-sm-5">Commenced</th>
									<td class="col-sm-7"><?php echo (!empty($node->field_project_fs_start) ? date_format(date_create($node->field_project_fs_start['und'][0]['value']),'d M Y'): "-"); ?></td>
								</tr>
								<tr>
									<th class="fs col-sm-5">Completed</th>
									<td class="col-sm-7"><?php echo (!empty($node->field_project_fs_end) ? date_format(date_create($node->field_project_fs_end['und'][0]['value']),'d M Y'): "-"); ?></td>
								</tr>
								<?php endif; ?>
								<tr>
									<th class="col-sm-5">Financing Available</th>
									<td class="col-sm-7"><?php echo (!empty($node->field_project_financing_avail) ? date_format(date_create($node->field_project_financing_avail['und'][0]['value']),'d M Y'): "-"); ?></td>
								</tr>
								<tr>
									<th class="col-sm-5">Project Approved</th>
									<td class="col-sm-7"><?php if (!empty($node->field_project_approval_date)) {
										$ad = new DateTime($node->field_project_approval_date['und'][0]['value']); echo $ad->format("d M Y"); 
										} else { echo "-";
										} ?></td>
								</tr>
								<tr><th class="project-progress-title" colspan="2">Project Implementation</th></tr>
								<tr>
									<th class="pi col-sm-5">Commenced</th>
									<td class="col-sm-7"><?php echo (!empty($node->field_project_implementation) ? date_format(date_create($node->field_project_implementation['und'][0]['value']),'d M Y'): "-"); ?></td>
								</tr>
								<?php if ($project_type != "Loan"): ?><tr>
									<th class="pi col-sm-5">Completed</th>
									<td class="col-sm-7"><?php echo (!empty($node->field_project_completed) ? date_format(date_create($node->field_project_completed['und'][0]['value']),'d M Y'): "-"); ?></td>
								</tr><?php endif; ?>
								<tr>
									<th class="col-sm-5">Notes/Narrative</th>
									<td class="col-sm-7" id="project-notes-narrative"><p><?php echo (!empty($node->field_project_progress_notes) ? $node->field_project_progress_notes['und'][0]['value']: "-"); ?></p></td>
								</tr>
								<tr>
									<th class="col-sm-5">Report Submitted By</th>
									<td class="col-sm-7"><?php echo (!empty($node->field_project_report_submitter) ? $node->field_project_report_submitter['und'][0]['value']: "-"); ?></td>
								</tr>
							</tbody>
						</table>
						<?php else: ?>
						Project <?php echo strtolower($project_status); ?>
						<?php endif; ?>
					</div>
				</div>
				<div id="project-contacts" class="col-sm-4">
					<h4>Contacts</h4>
					<div>
						<?php 
							//$node_wrapper = entity_metadata_wrapper('node', $node);
							//$raw_coll = $node_wrapper->field_project_contact[0]->value();
							//$coll = entity_metadata_wrapper('field_collection_item',$raw_coll);
							$contact1 = entity_load('field_collection_item',array($node->field_project_contact['und'][0]['value']));
							$contact2 = entity_load('field_collection_item',array($node->field_project_contact['und'][1]['value']));
							//echo "<pre>".print_r($contact1[1]->field_contact_name['und'][0]['value'],1)."</pre>";
							//echo "<pre>".print_r($contact2[2]->field_contact_name['und'][0]['value'],1)."</pre>";
						?>
						<table class="table-condensed">
							<tbody>
								<tr><td colspan="2" class="project-contact-title">Contact 1</td></tr>
								<tr>
									<th class="ch col-sm-5">Contact Name</th>
									<td class="col-sm-7"><?php echo (!empty($contact1) ? $contact1[1]->field_contact_name['und'][0]['value'] : "-"); ?></td>
								</tr>
								<tr>
									<th class="ch col-sm-5">Organization</th>
									<td class="col-sm-7"><?php echo (!empty($contact1) ? $contact1[1]->field_contact_organization['und'][0]['value'] : "-"); ?></td>
								</tr>
								<tr>
									<th class="ch col-sm-5">Email</th>
									<td class="col-sm-7"><?php echo (!empty($contact1) ? $contact1[1]->field_contact_email['und'][0]['value'] : "-"); ?></td>
								</tr>
								<tr><td colspan="2" class="project-contact-title">Contact 2</td></tr>
								<tr>
									<th class="ch col-sm-5">Contact Name</th>
									<td class="col-sm-7"><?php echo (!empty($contact2) ? $contact2[2]->field_contact_name['und'][0]['value'] : "-"); ?></td>
								</tr>
								<tr>
									<th class="ch col-sm-5">Organization</th>
									<td class="col-sm-7"><?php echo (!empty($contact2) ? $contact2[2]->field_contact_organization['und'][0]['value'] : "-"); ?></td>
								</tr>
								<tr>
									<th class="ch col-sm-5">Email</th>
									<td class="col-sm-7"><?php echo (!empty($contact2) ? $contact2[2]->field_contact_email['und'][0]['value'] : "-"); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="project-photos" class="col-sm-4">
					<h4>News and Multimedia<?php //print $view_photos->get_title(); ?> <?php //print $btnEditPhotos ?></h4>
					<?php $view_photos->set_arguments(array($nid)); $view_photos->pre_execute(); $view_photos->execute(); print $view_photos->render(); ?>
					<br/>
					<?php 
					//echo "<pre>".print_r($node->field_impact_stories,1)."</pre>";
					if (count($node->field_impact_stories) > 0): ?>
					<div class="project-impact-stories">
					<ul>
					<?php foreach($node->field_impact_stories[LANGUAGE_NONE] as $is){
					 echo "<li class='views-row'><span class='views-field views-field-body'><span class='field-content'>".$is['value']."</span></span></li>";
					 } ?>
					</ul>
					</div>
					<?php else:
						$view_stories->set_arguments(array($nid)); $view_stories->pre_execute(); $view_stories->execute(); //print $view_stories->render();
					 endif; ?>
				</div>
			</div>
			<div class="row element-invisible">
				<div id="project-outputs" class="col-sm-4">
					<h4><?php //print $view_outputs->get_title(); ?> <?php //print $btnEditOutputs ?></h4>
					<?php 
						//echo "<pre>".print_r($node->field_outputs,1)."</pre>";
						if (count($node->field_outputs) > 0): ?>
					<div class="project-outputs">
					<ul>
					<?php foreach($node->field_outputs[LANGUAGE_NONE] as $op){
					 //echo "<li class='views-row'><span class='views-field views-field-body'><span class='field-content'>".$op['value']."</span></span><div class=\"list-spacer\">&nbsp;</div></li>"; 
					} 
					?>
					</ul>
					</div>
					<?php else: $view_outputs->set_arguments(array($nid)); $view_outputs->pre_execute(); $view_outputs->execute(); //print $view_outputs->render(); 
					endif; ?>
				</div>
				<div id="project-impact-stories" class="col-sm-8">
					<h4><?php //print $view_stories->get_title(); ?> <?php //print $btnEditImpacts ?></h4>
					<?php 
					//echo "<pre>".print_r($node->field_impact_stories,1)."</pre>";
					if (count($node->field_impact_stories) > 0): ?>
					<div class="project-impact-stories">
					<ul>
					<?php foreach($node->field_impact_stories[LANGUAGE_NONE] as $is){
					 //echo "<li class='views-row'><span class='views-field views-field-body'><span class='field-content'>".$is['value']."</span></span></li>";
					 } ?>
					</ul>
					</div>
					<?php else:
						//$view_stories->set_arguments(array($nid)); $view_stories->pre_execute(); $view_stories->execute(); //print $view_stories->render();
					 endif; ?>
				</div>
			</div>	
		</div>
	</div>
</div>