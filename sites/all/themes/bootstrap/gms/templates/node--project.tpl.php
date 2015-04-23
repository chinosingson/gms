<?php
	// GMS
	// template for project nodes
	$node = menu_get_object(); 
	$nid = $node->nid;
	
	$editPerm = user_access('Edit any content');
	if ($editPerm) {
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
	}
	
	//echo "<pre>";
	//echo print_r($node,1);
	//echo "</pre>";
	
	// Project Views
	$view_map = views_get_view('leaflet_view_test');			// locations
	$view_details = views_get_view('project_details');		// details
	$view_funding = views_get_view('project_financing');	// funding
	$view_photos = views_get_view('project_photos');			// photos
	$view_stories = views_get_view('impact_stories');			// impacts
	$view_outputs = views_get_view('outputs');						// outputs

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
	

	// settings
	$chartSettings['chart']['chartOne'] = array(  
		'header' => array('ADB', 'ADB-JSF/JFPR', 'ADB-TASF', 'Government', 'Cofinancing'),
		'rows' => array(
			array(
				$fundingSources['adb'],
				$fundingSources['adb_jsf_jfpr'],
				$fundingSources['adb_tasf'],
				$fundingSources['gov'],
				$fundingSources['cof']
			)
		),
		'columns' => array('Funding Sources'),
		'chartType' => 'PieChart',
		'containerId' =>  'project-funding-chart',
		'numberFormat' => 'short',
		'options' => array(
			'backgroundColor'=>'#3b444c',
			'legend'=> array(
				'position'=>'bottom',
				'alignment'=>'center',
				'textStyle'=>array(
					'color'=>'#ffffff'
				)
			),
			'forceIFrame' => FALSE, 
			'pieSliceText'=> 'value',
			'title' => '',
			'width' => 'auto',
			'height' => '300',
			'colors' => ['#ff3333','#ff6600','#ffcc00','#99cc33','#33cc33','#66CCFF','#0066CC','#666699','#FF6699','#FFFF00','#00CCCC','#999999'],
			'chartArea' => array(
				'left'=>5,
				'top'=>7,
				'width'=>'auto',
				'height'=>'82%'
			)
		)   
	);
	
	// total - reformatted for block heading
	if (isset($node->field_project_cost_total['und'][0]['value'])){
		$fundingTotal = "<span>: USD ".number_format($node->field_project_cost_total['und'][0]['value']/1000)."M</span>";
	} else {
		$fundingTotal = "";
	}
	

	
	
?>
<div id="project-page" class="container-fluid">
	<div class="row">
		<div id="project-authoring" class="col-sm-12 small">Last Edited <?php print date('[m/d/Y]',$node->revision_timestamp) ?> by <?php print $node->name ?></span>
	</div>
	<div class="row">
		<div id="project-map" class="col-lg-12"><?php print views_embed_view('leaflet_view_test', 'block_1', $node->nid); ?></div>
	</div>
	<div class="project-info">
		<div class="row">
			<div id="project-details" class="col-sm-4">
				<h4 class=""><?php print $view_details->get_title(); ?> <?php //print $btnEditDetails ?></h4>
				<?php print views_embed_view('project_details', 'block', $nid); ?>
			</div>
			<div id="project-funding-details" class="col-sm-4">
				<h4><?php print $view_funding->get_title(); ?><?php echo $fundingTotal ?><?php //print $btnEditFunding ?></h4>
				<div id="project-funding-chart" class="chart">
					<?php $ret = draw_chart($chartSettings); 	?>
				</div>
			</div>
			<div id="project-photos" class="col-sm-4">
				<h4><?php print $view_photos->get_title(); ?> <?php //print $btnEditPhotos ?></h4>
				<?php print views_embed_view('project_photos','block', $nid); ?>
			</div>
		</div>
		<div class="row">
			<div id="project-outputs" class="col-sm-4">
				<h4><?php print $view_outputs->get_title(); ?> <?php //print $btnEditOutputs ?></h4>
				<?php print views_embed_view('outputs', 'block', $nid); ?>
			</div>
			<div id="project-impact-stories" class="col-sm-8">
				<h4><?php print $view_stories->get_title(); ?> <?php //print $btnEditImpacts ?></h4>
				<?php print views_embed_view('impact_stories','block_1', $nid); ?>
			</div>
		</div>	
	</div>
	
	<div id="edit-project-details">
				<?php
					//if ($editPerm) {
						//$block = module_invoke('afb', 'block_view', 2);
						//$block = module_invoke('flexiform', 'block_view', 'project_details', $node->nid);
						//print render($block['content']); 
					//}
				?>
	</div>
	
	<div class="modal fade" id="dialog-edit-project-details" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			</div>
		</div>
	</div>

	
	<!--div class="row">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Node Info</a>
					</h4>
				</div>
				<div id="collapse1" class="panel-collapse collapse">
					<div class="panel-body"><pre><?php echo print_r($node,1); ?></pre></div>
				</div>
			</div>
		</div>
	</div-->
</div>