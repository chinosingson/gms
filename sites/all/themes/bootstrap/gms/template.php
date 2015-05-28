<?php

/**
 * @file
 * template.php
 */

//drupal_add_js(libraries_get_path('highcharts') .'/js/highcharts.js');
//drupal_add_js(drupal_get_path('theme', 'gms') .'/chart.js');
drupal_add_js(drupal_get_path('theme', 'gms') .'/script.js');
drupal_add_js(libraries_get_path('bcarousel').'/carousel.js',array('group' => JS_THEME, 'every_page' => TRUE));


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
	);
}*/

// remove fieldset wrapper from date fields
/*function gms_date_combo($variables) {
  return theme('form_element', $variables);
}*/

function gms_preprocess_html(&$variables) {
	drupal_add_css('http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700' , array('type' => 'external'));
}

function gms_preprocess_page(&$variables) {
  if (!empty($variables['node']) && $variables['node']->type == 'project') {
    //$variables['show_title'] = FALSE;
		$variables['title'] = FALSE;
  }
	
	if (arg(0) == 'search') {
		//echo print_r($variables,1);
		$keys = arg(2);
		if (!$keys) $keys = $_REQUEST['keys'];
		if ($keys) $variables['title'] = 'Search results for "'.$keys.'"';
	}
	//if(!empty($tabs)){
	
		//$tabs = array();
	//}
	

}

function gms_preprocess_search_results(&$vars) {
	//echo "THIS: ".$GLOBALS['pager_total_items'][0];
	//echo "THIS: ".$GLOBALS['pager_limits'][0];
	//echo "<pre>".print_r($GLOBALS,1)."</pre>";
	$itemsPerPage = $GLOBALS['pager_limits'][0];
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

	// If there is more than one page of results:
	if ($total > $itemsPerPage) {
		$vars['search_totals'] = t('Displaying !start - !end of !total results', array(
			'!start' => $start,
			'!end' => $end,
			'!total' => $total,
		));
	} else {
		// Only one page of results, so make it simpler
		$vars['search_totals'] = t('Displaying !total !results_label', array(
			'!total' => $total,
			// Be smart about labels: show "result" for one, "results" for multiple
			'!results_label' => format_plural($total, 'result', 'results'),
		));
	} 
}


function gms_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
    $form['search_block_form']['#title'] = t(''); // Change the text on the label element
    $form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibility
    $form['search_block_form']['#default_value'] = t(''); // Set a default value for the textfield
    //$form['actions']['submit']['#type'] = 'submit'; 
    //$form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button
    $form['actions']['submit']['#attributes']['alt'] = "Search"; //add alt tag
    $form['search_block_form']['#attributes'] = array("title"=>"Search project portfolio", 'placeholder'=>'');
    //unset($form['actions']['submit']['#value']); // Remove the value attribute from the input tag, since it is not valid when input type = image

    $form['actions']['submit'] = array('#type' => 'submit', '#value' => 'Search');
		//echo "<pre>".print_r($form['search_block_form'],1)."</pre>";
		//echo "<pre>".print_r($form['actions'],1)."</pre>";

// Add extra attributes to the text box
    //$form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search Site';}";
    //$form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search Site') {this.value = '';}";
  }
}

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