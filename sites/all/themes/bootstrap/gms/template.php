<?php

/**
 * @file
 * template.php
 */

//drupal_add_js(libraries_get_path('highcharts') .'/js/highcharts.js');
//drupal_add_js(drupal_get_path('theme', 'gms') .'/chart.js');
drupal_add_js(drupal_get_path('theme', 'gms') .'/script.js');
//drupal_add_js(libraries_get_path('bcarousel').'/carousel.js',array('group' => JS_THEME, 'every_page' => TRUE));


/*function gms_theme()
{
	return array(
		'project_node_form' => array(
			'arguments' => array('form' => NULL),
			'render element' => 'form',
			'template' => 'templates/project-node-form',
			'path' => drupal_get_path('module', 'gms/templates'),//'project/%/details/edit',
			//'arguments' => array('form' => NULL),
		),
		//'project_node_print' => array();
	);
}*/

function gms_theme($variables) {
	//drupal_set_message(t($variables['title']),'status');
	return array(
		'project_node_form' => array(
			'arguments' => array('form' => NULL),
			'render element' => 'form',
			'path' => $base_path.drupal_get_path('theme',$GLOBALS['theme']).'/templates',
			'template' => 'node--project--edit',
		),
	);
}

// remove fieldset wrapper from date fields
/*function gms_date_combo($variables) {
  return theme('form_element', $variables);
}*/

/*function gms_views_pre_render($view = "leaflet_view_test"){
	if ($view->name == "leaflet_view_test"){
		print_r($view);
	}
}*/

function gms_menu_link__main_menu(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  $element['#attributes']['class'][] = 'menu-' . $element['#original_link']['mlid'];

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
	if ($element['#original_link']['mlid'] == 1099){
		if(arg(0) == "dashboard"){
			$element['#href'] = 'printpdf/dashboard';
		} 

		if(arg(0) == "node"){
			$alias =drupal_get_path_alias('node/'.arg(1));
			$element['#href'] = "printpdf/".$alias;
		}
		
		if(arg(0) == "maps") {
			$element['#href'] = "printpdf/".arg(0)."/".arg(1);
		}
	}
	
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

function gms_preprocess_html(&$variables) {
	drupal_add_css('http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700' , array('type' => 'external'));
}

function gms_preprocess_page(&$variables) {
  if (@is_object($variables['node']) 
	&& count(get_object_vars($variables['node'])) > 0 
	&& @$variables['node']->type == 'project') {
    //$variables['show_title'] = FALSE;
		$variables['title'] = FALSE;
  }
	
	if(arg(1) == 'add'){
		$variables['title'] = FALSE;
	}
	
	//if (@$variables['node']->type == 'project') {
	//if (arg(2)!='edit' || arg(1) != 'add') {
	//	drupal_add_js(libraries_get_path('bcarousel').'/carousel.js',array('group' => JS_THEME, 'every_page' => FALSE));
	//}
	
	// search results title message
	$itemsPerPage = @$GLOBALS['pager_limits'][0];
	$total = isset($GLOBALS['pager_total_items'][0]) ? $GLOBALS['pager_total_items'][0] : 0;
	if (arg(0) == 'search') {
		//echo print_r($variables,1);
		$keys = arg(2);
		if (!$keys) $keys = $_REQUEST['keys'];
		if ($keys) { 
			if ($total > 1 || $total == 0){
				$label = 'projects';
			} else {
				$label = 'project';
			}
			$variables['title'] = 'Your search for "'.$keys.'" returned '.$total.' '.$label.'.';
		}
	}
	
	//if(arg(0) == 'print' && arg(1) == 'dashboard') {   //For node 4
	//	$variables['title'] = "dashboard - preprocess page";
  //  $variables['theme_hook_suggestions'][] =  'page__dashboard';
  //}

}

function gms_preprocess_project_node_form(&$variables){
	$form = $variables['form'];
	//$form['title']['#title'] = t("Project Name");
	$variables['myvar'] = "hello";
	//hide ($form['title']['#title']);

	//$variables['form_title'] = $form['title'];
	//$variables['buttons'] = $variables['form']['actions'];
}

function gms_theme_registry_alter(&$theme_registry){
	$theme_registry['print__dashboard']['template'] = 'print--dashboard';
	$theme_registry['print__dashboard']['path'] = drupal_get_path('theme', 'gms/templates');
}

function gms_preprocess_search_results(&$vars) {
	//echo "THIS: ".$GLOBALS['pager_total_items'][0];
	//echo "THIS: ".$GLOBALS['pager_limits'][0];
	//echo "<pre>".print_r($GLOBALS,1)."</pre>";
	$itemsPerPage = @$GLOBALS['pager_limits'][0];
  $currentPage = isset($_REQUEST['page']) ? $_REQUEST['page']+1 : 1;

	// Get the total number of results from the global pager
	$total = isset($GLOBALS['pager_total_items'][0]) ? $GLOBALS['pager_total_items'][0] : 0;

	// Determine which results are being shown ("Showing results x through y")
	$start = $itemsPerPage * $currentPage - ($itemsPerPage - 1);

	// If on the last page, only go up to $total, not the total that COULD be
	// shown on the page. This prevents things like "Displaying 11-20 of 17".
	//$end = (($itemsPerPage * $currentPage) >= $total) ? $total : ($itemsPerPage * $currentPage);
	$end = $itemsPerPage * $currentPage;
	if ($end > $total){
		$end = $total;
	}

	$keys = arg(2);
	if(!$keys) $keys = $_REQUEST['keys'];
	// If there is more than one page of results:
	if ($total > $itemsPerPage) {
		$vars['search_totals'] = t('Your search for \'!keys\' returned !total !results_label.', array(
			'!keys' => $keys,
			'!start' => $start,
			'!end' => $end,
			'!total' => $total,
			'!results_label' => format_plural($total, 'project', 'projects'),
		));
	} else {
		// Only one page of results, so make it simpler
		$vars['search_totals'] = t('Your search for \'!keys\' returned !total !results_label.', array(
			'!keys' => $keys,
			'!total' => $total,
			// Be smart about labels: show "result" for one, "results" for multiple
			'!results_label' => format_plural($total, 'project', 'projects'),
		));
	} 
}


function gms_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
    $form['search_block_form']['#attributes'] = array("title"=>"Search project portfolio", 'placeholder'=>'');
    $form['actions']['submit'] = array('#type' => 'submit', '#value' => 'Search');
    $form['actions']['submit']['#attributes'] = array('id' => 'search-submit');
    /*$form['search_block_form']['#title'] = t(''); // Change the text on the label element
    $form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibility
    $form['search_block_form']['#default_value'] = t(''); // Set a default value for the textfield
    //$form['actions']['submit']['#type'] = 'submit'; 
    //$form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button
    $form['actions']['submit']['#attributes']['alt'] = "Search"; //add alt tag*/
    //unset($form['actions']['submit']['#value']); // Remove the value attribute from the input tag, since it is not valid when input type = image

		//echo "<pre>".print_r($form['search_block_form'],1)."</pre>";
		//echo "<pre>".print_r($form['actions'],1)."</pre>";

		// Add extra attributes to the text box
    //$form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search Site';}";
    //$form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search Site') {this.value = '';}";
  }
	
	if ($form_id == 'project_node_form'){
		//dpm($form); //['#label'] = t('Start');
		/*$form['field_project_number'] = array(
			'#prefix' => '',
			'#suffix' => '',
		);*/
		
		// HIDE UNNEEDED ELEMENTS
		$form['title']['#title_display'] = 'invisible';
		//$form['field_outputs']['#title_display'] = 'invisible';
		hide($form['field_outputs']['#title']);
		hide($form['field_project_temp_id']);
		hide($form['field_project_approval_date']);
		hide($form['field_project_closing_date']);
		hide($form['field_project_geog_locn']);
		hide($form['field_project_subtype']);
		hide($form['field_project_tgl_no']);
		hide($form['field_adb_primary_subsector']);
		hide($form['field_project_source_cofinancing']);
		hide($form['field_project_other_website']);
		hide($form['field_project_cost_adb_jsf_jfpr']);
		hide($form['field_project_cost_adb_tasf']);
		hide($form['body']);
		hide($form['actions']['preview']);
		
		// ALTER FORM LABELS
		$form['title']['#attributes'] = array('placeholder'=>'Project Name*');
		$form['field_project_type']['und']['#title'] = t('Type');
		$form['field_adb_sector']['und']['#title'] = t('Sector');
		$form['field_project_cost_total']['und'][0]['value']['#title'] = t('Total');
		$form['field_project_cost_adb']['und'][0]['value']['#title'] = t('ADB');
		$form['field_project_cost_government']['und'][0]['value']['#title'] = t('Government');
		$form['field_project_cost_cofinancing_']['und'][0]['value']['#title'] = t('Cofinancing');
		//$form['field_outputs']['#title_display'] = 'invisible';
		//$form['field_impact_stories']['#title_display'] = 'invisible';
		//$form['field_project_number']['und'][0]['value']['#prefix'] = '<div class="my-class">';
		//$form['field_project_number']['und'][0]['value']['#suffix'] = '</div>';
		//$form['field_project_number']['#title']['#prefix'] = '<div class="title-class">';
		//$form['field_project_number']['#title']['#suffix'] = '</div>';
		//$form['field_project_number']['und'][0]['value']['#attributes']['class'][] = 'input_class';
	}
}

/*function gms_date_part_label_date($vars) {
  $part_type = $vars['year'];
  $element = $vars['element'];
  return t('Date', array(), array('context' => 'datetime'));
	//print print_r($vars,1);
}*/

/*function gms_date_text_parts(&$variables){ //, $hook) {
	//if($variables['element']['#field_name'] == 'field_project_year_start') {
	//if($variables['element']['#field_name'] == 'field_project_cost_adb') {
	//	$variables['label'] = "your label name";
	//}
  //$element = $variables['element'];
	//if ($element['#field_name'] == 'field_project_year_start'){
	//	$element['year']['label'] = t('Start');
	//}
  $rows = array();
  foreach (date_granularity_names() as $key => $part) {
    if ($element[$key]['#type'] == 'hidden') {
      $rows[] = drupal_render($element[$key]);
    }
    else {
      $rows[] = array($part, drupal_render($element[$key][0]), drupal_render($element[$key][1]));
    }
  }
  if ($element['year']['#type'] == 'hidden') {
    return implode($rows) . drupal_render_children($element);
  }
  else {
    $header = array(t('Date part'), t('Select list'), t('Text field'));
    return theme('table', array('header' => $header, 'rows' => $rows)) . drupal_render_children($element);
  }
}*/

function gms_page_alter(&$page) {
	
	// remove search form in search results page
  // This assumes everything being output in the "content" page region.
  // Logged in
  if (!empty($page['content']['system_main']['content']['search_form'])) {
    unset($page['content']['system_main']['content']['search_form']);
  }

  // Not logged in
  if (!empty($page['content']['system_main']['search_form'])) {
    unset($page['content']['system_main']['search_form']);
  }
}

/*function gms_preprocess_views_exposed_form(&$vars, $hook) {

	//echo '<pre>'.print_r($vars['form'],1).'</pre>';
  // only alter the jobs search exposed filter form
  if ($vars['form']['#id'] == 'views-exposed-form-project-locations-maps-projects') {

		
    // Change the text on the submit button
    $vars['form']['submit']['#value'] = t('Filter');

    // Rebuild the rendered version (submit button, rest remains unchanged)
    unset($vars['form']['submit']['#printed']);
    $vars['button'] = drupal_render($vars['form']['submit']);
  }
}*/