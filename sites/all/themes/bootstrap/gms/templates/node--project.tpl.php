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
	$view_details->set_display('block');
	$view_funding = views_get_view('project_financing');	// funding
	
	$view_photos = views_get_view('project_photos');			// photos
	$view_photos->set_display('carousel1');
	$view_stories = views_get_view('impact_stories');			// impacts
	$view_stories->set_display('block_1');
	$view_outputs = views_get_view('outputs');						// outputs
	$view_outputs->set_display('block');

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
			'fontSize'=>'16',
			'fontName'=>'Source Sans Pro',
			'backgroundColor'=>'#3b444c',
			'legend'=> array(
				'position'=>'right',
				'alignment'=>'center',
				'textStyle'=>array(
					'color'=>'#ffffff'
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
		<div id="project-title" class="col-sm-9"><?php if ($show_title && $title): ?><h2 class="title" id="page-title"><?php print $title; ?></h2><?php endif; ?></div>
		<!--div id="project-function-buttons" class="col-sm-3 pull-right">
			<a href="#" class="btn btn-xs btn-primary disabled" id="btn-edit"><i class="project-btn project-btn-edit" data-toggle="tooltip" data-placement="auto" title="Edit Project Details">&nbsp;</i></a>
			<a href="#" class="btn btn-xs btn-primary disabled" id="btn-print"><i class="project-btn project-btn-print" data-toggle="tooltip" data-placement="top" title="Print Factsheet">&nbsp;</i></a>
			<a href="<?php //print base_path(); ?>" class="btn btn-xs btn-primary" id="btn-home"><i class="project-btn project-btn-portfolio" data-toggle="tooltip" data-placement="auto" title="Back to Portfolio View">&nbsp;</i></a>
		</div-->
		<div id="project-function-buttons" class="col-sm-3 pull-right">
			<a href="#" id="btn-edit" class="disabled">Edit</a>
		</div>
	</div>
	<div class="row">
		<div id="project-map" class="col-lg-12"><?php print views_embed_view('leaflet_view_test', 'block_1', $node->nid); ?></div>
	</div>
	<div class="project-info">
		<div class="row">
			<div id="project-details" class="col-sm-4">
				<h4 class=""><?php print $view_details->get_title(); ?> <?php //print $btnEditDetails ?></h4>
				<?php $view_details->set_arguments(array($node->nid)); $view_details->pre_execute(); $view_details->execute(); print $view_details->render(); ?>
			</div>
			<div id="project-funding-details" class="col-sm-4">
				<h4><?php print $view_funding->get_title(); ?><?php //print $btnEditFunding ?></h4>
				<div id="project-funding-total">
					<span class='funding-total'><?php echo $fundingTotal ?></span>
				</div>
				<div id="project-funding-chart" class="chart">
					<?php //$view_funding->set_display('block'); $view_funding->set_arguments(array($node->nid)); $view_funding->pre_execute(); $view_funding->execute(); print $view_funding->render(); ?>
					
					<?php 
						if (array_sum($fundingSources) != 0) {
							$ret = draw_chart($chartSettings); 
							//echo "meron<br/>";
							echo "";
						} else {
							echo "<div id=\"project-no-funding-sources\">Funding sources for this project have not been determined.<br/></div>";
						//echo print_r($fundingSources,1)."<br/>";
						}	?>
				</div>
			</div>
			<div id="project-photos" class="col-sm-4">
				<h4><?php print $view_photos->get_title(); ?> <?php //print $btnEditPhotos ?></h4>
				<?php $view_photos->set_arguments(array($node->nid)); $view_photos->pre_execute(); $view_photos->execute(); print $view_photos->render(); ?>
			</div>
		</div>
		<div class="row">
			<div id="project-outputs" class="col-sm-4">
				<h4><?php print $view_outputs->get_title(); ?> <?php //print $btnEditOutputs ?></h4>
				<?php $view_outputs->set_arguments(array($node->nid)); $view_outputs->pre_execute(); $view_outputs->execute(); print $view_outputs->render(); ?>
			</div>
			<div id="project-impact-stories" class="col-sm-8">
				<h4><?php print $view_stories->get_title(); ?> <?php //print $btnEditImpacts ?></h4>
				<?php $view_stories->set_arguments(array($node->nid)); $view_stories->pre_execute(); $view_stories->execute(); print $view_stories->render(); ?>
			</div>
		</div>	
	</div>
</div>