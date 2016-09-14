<?php

/**
 * @file
 * template.php
 */

//drupal_add_js(libraries_get_path('highcharts') .'/js/highcharts.js');
//drupal_add_js(drupal_get_path('theme', 'gms') .'/chart.js');
drupal_add_js(drupal_get_path('theme', 'gms') .'/script.js');
drupal_add_js(libraries_get_path('google_markerclusterer').'/src/markerclusterer.js');
//drupal_add_js(libraries_get_path('bcarousel').'/carousel.js',array('group' => JS_THEME, 'every_page' => TRUE));



function gms_theme($variables) {
	
	return array(
		'project_node_form' => array(
			'arguments' => array('form' => NULL),
			'render element' => 'form',
			//'path' => base_path().drupal_get_path('theme','gms').'/templates',
			'path' => drupal_get_path('theme','gms').'/templates',
			'template' => 'node--project--edit'),
		'user_login' => array(
			'render element' => 'form',
			'path' => drupal_get_path('theme','gms').'/templates',
			'template' => 'user-login',
			//template' => 'page--user--login',
			'preprocess functions' => array('gms_preprocess_user_login'),
		),
	);
}

function gms_form_element_label($variables) {
  $element = $variables ['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element ['#title']) || $element ['#title'] === '') && empty($element ['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element ['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element ['#title']);

  $attributes = array();
  // Style the label as class option to display inline with the element.
  if ($element ['#title_display'] == 'after') {
    $attributes ['class'] = 'option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element ['#title_display'] == 'invisible') {
    $attributes ['class'] = 'element-invisible';
  }

  if (!empty($element ['#id'])) {
    $attributes ['for'] = $element ['#id'];
		//echo print_r($element['#id'],1);
		switch($element['#id']){
			case 'edit-name':
			case 'edit-pass':
				$attributes['class'][] = 'col-md-2';
				break;
		}
  }

  // The leading whitespace helps visually separate fields from inline labels.
  return ' <label' . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
}

function gms_form_element($variables) {
  $element = &$variables ['element'];

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element ['#markup']) && !empty($element ['#id'])) {
    $attributes ['id'] = $element ['#id'];
  }

	if (in_array($element['#id'], array('edit-name','edit-pass'))){
		$attributes['class'][] = 'row';
	}
	
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes ['class'] = array('form-item');
  if (!empty($element ['#type'])) {
    $attributes ['class'][] = 'form-type-' . strtr($element ['#type'], '_', '-');
  }
  if (!empty($element ['#name'])) {
    $attributes ['class'][] = 'form-item-' . strtr($element ['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element ['#attributes']['disabled'])) {
    $attributes ['class'][] = 'form-disabled';
  }
	
	//echo print_r($element['#id'],1)."<br/>";
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element ['#title'])) {
    $element ['#title_display'] = 'none';
  }
  $prefix = isset($element ['#field_prefix']) ? '<span class="field-prefix">' . $element ['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element ['#field_suffix']) ? ' <span class="field-suffix">' . $element ['#field_suffix'] . '</span>' : '';

  switch ($element ['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element ['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element ['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element ['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element ['#description'])) {
    $output .= '<div class="description">' . $element ['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}

function gms_preprocess_user_login(&$variables){
	//echo "<pre>".print_r($variables['form'],1)."</pre>"; //['name']['#value']
  $variables['intro_text'] = t('Only registered users are allowed to enter and edit data in the GMS Program database. Please enter your username and password below.');
	$variables['form']['name']['#attributes']['class'][] = 'login-gms-form-input';
	$variables['form']['name']['#attributes']['class'][] = 'col-md-10';
	$variables['form']['name']['#prefix'] = '<div class="row login-gms-form-group">';
	$variables['form']['name']['#suffix'] = '</div>';
	$variables['form']['name']['#description'] = t('');
	$variables['form']['pass']['#attributes']['class'][] = 'login-gms-form-input';
	$variables['form']['pass']['#attributes']['class'][] = 'col-md-10';
	$variables['form']['pass']['#prefix'] = '<div class="row login-gms-form-group">';
	$variables['form']['pass']['#suffix'] = '</div>';
	$variables['form']['pass']['#description'] = t('');
	$variables['form']['actions']['submit']['#prefix'] = '<div id="gms-login-actions" class="row login-gms-form-group">';
	$variables['form']['actions']['submit']['#suffix'] = '</div>';
  //$variables['rendered'] = drupal_render_children($variables['form']);
}

function gms_menu_link__main_menu(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

	$element['#localized_options']['html'] = FALSE;
	//$element['#localized_options']['language'] = LANGUAGE_NONE;
  $element['#attributes']['class'][] = 'menu-' . $element['#original_link']['mlid'];

	global $user;
	if ($element['#title'] == 'Login'){
		if ($user->uid != 0) {
			$element['#title'] = t('Log out');
			$element['#href'] = 'user/logout';
    }
	}
	
	/*if($element['#title'] == 'Add Project'){
		if ($user->uid == 0){
			//$element['#original_link']['options']['attributes']['class'][] = 'btn';
			//$element['#original_link']['options']['attributes']['class'][] = 'disabled';
			$element['#attributes']['class'][] = 'btn';
			$element['#attributes']['class'][] = 'disabled';
			$element['#href'] = '#';
			//echo "<pre>".print_r($element,1)."</pre>";
		}
	}*/
	
	if ($element['#original_link']['mlid'] == 1099){
		if(arg(0) == "dashboard"){
			$element['#href'] = 'printpdf/dashboard';
		}

		if(arg(0) == "node"){
			$alias =drupal_get_path_alias('node/'.arg(1));
			$element['#href'] = "printpdf/".$alias;
		}
		
		if(arg(0) == "maps" || arg(0) == "user" ) {
			//$element['#href'] = "printpdf/".arg(0)."/".arg(1);
			//$element['#original_link']['hidden'] = TRUE;
			$element['#attributes']['class'][] = 'btn';
			$element['#attributes']['class'][] = 'disabled';
			$element['#attributes']['class'][] = 'element-invisible';
			$element['#href'] = '';
			$element['#localized_options']['html'] = TRUE;
			$element['#title'] = "<span class='element-invisible'>" . $element['#title'] . "</span>";
			//echo '<pre>'.print_r($element,1).'</pre>';
		}
	}

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }

	//print print_r($element['#localized_options'],1)."<br/>";
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

function gms_menu_link($variables) {
  $element = $variables ['element'];
  $sub_menu = '';

	if ($element['#original_link']['mlid'] == 1084){
		$element['#localized_options']['fragment'] = 'none';
	}

  if ($element ['#below']) {
    $sub_menu = drupal_render($element ['#below']);
  }
  $output = l($element ['#title'], $element ['#href'], $element ['#localized_options']);
  return '<li' . drupal_attributes($element ['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

function gms_preprocess_html(&$variables) {
	drupal_add_css('http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700' , array('type' => 'external'));
	
	
}

function gms_preprocess_page(&$variables) {
	//dpm($variables);
  if (@is_object($variables['node']) 
	&& count(get_object_vars($variables['node'])) > 0 
	&& @$variables['node']->type == 'project') {
    //$variables['show_title'] = FALSE;
		$variables['title'] = FALSE;
		// hide system messages for project nodes
		$variables['show_messages'] = FALSE;
  }
	
	if(arg(1) == 'add' || arg(2) == 'edit'){
		$variables['title'] = FALSE;
		$variables['show_messages'] = FALSE;
	}
	
	
	//if (@$variables['node']->type == 'project') {
	//if (arg(2)!='edit' || arg(1) != 'add') {
	//	drupal_add_js(libraries_get_path('bcarousel').'/carousel.js',array('group' => JS_THEME, 'every_page' => FALSE));
	//}
	/*if ($variables['messages']){
		echo "<pre>".print_r($variables['messages'],1)."</pre>";
	}*/
	
	
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
	//print_r($variables['links']);

}

function gms_preprocess_node(&$variables) {
	$variables['messages'] = theme('status_messages');
}
function gms_preprocess_project_node_form(&$variables){
	$form = $variables['form'];
	//$form['title']['#title'] = t("Project Name");
	$variables['myvar'] = "hello";
	//hide ($form['title']['#title']);
	$variables['show_messages'] = TRUE;
	$variables['messages'] = theme('status_messages');

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
	/*if ($form_id == 'user_login'){
		//echo "<pre>".print_r($form,1)."</pre>";
	}*/

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
		//hide($form['field_outputs']['#title']);
		hide($form['field_project_temp_id']);
		//hide($form['field_project_approval_date']);
		//hide($form['field_project_closing_date']);
		hide($form['field_project_geog_locn']);
		hide($form['field_project_subtype']);
		hide($form['field_project_tgl_no']);
		hide($form['field_adb_primary_subsector']);
		//hide($form['field_project_source_cofinancing']);
		hide($form['field_project_other_website']);
		hide($form['field_project_cost_adb_jsf_jfpr']);
		hide($form['field_project_cost_adb_tasf']);
		//hide($form['field_project_locations']);
		//hide($form['body']);
		
		hide($form['field_project_financing_avail']);
		hide($form['field_project_implementation']);
		//hide($form['field_project_approved_by']);
		hide($form['field_project_completed']);
		hide($form['actions']['preview']);
		
		// ALTER FORM LABELS
		//$form['field_photos']['und'][0]['remove_button']['#value'] = t('x');
		//echo print_r($form['field_photos']['und'][0]['remove_button'],1);
		$form['title']['#attributes'] = array('placeholder'=>'Project Name*');
		$form['field_project_type']['und']['#title'] = t('Type');
		$form['field_adb_sector']['und']['#title'] = t('Sector');
		$form['field_project_cost_total']['und'][0]['value']['#title'] = t('Total');
		$form['field_project_cost_adb']['und'][0]['value']['#title'] = t('ADB');
		$form['field_project_cost_government']['und'][0]['value']['#title'] = t('Government');
		$form['field_project_cost_cofinancing_']['und'][0]['value']['#title'] = t('Cofinancing');
		$form['field_project_source_cofinancing']['und'][0]['value']['#title'] = t('Cofinancing Source');
		$form['field_project_approved_by']['und'][0]['value']['#title'] = t('Approved By');
		//$form['field_outputs']['#title_display'] = 'invisible';
		//$form['field_impact_stories']['#title_display'] = 'invisible';
		//$form['field_project_number']['und'][0]['value']['#prefix'] = '<div class="my-class">';
		//$form['field_project_number']['und'][0]['value']['#suffix'] = '</div>';
		//$form['field_project_number']['#title']['#prefix'] = '<div class="title-class">';
		//$form['field_project_number']['#title']['#suffix'] = '</div>';
		//$form['field_project_number']['und'][0]['value']['#attributes']['class'][] = 'input_class';
		//echo "<pre>".print_r($form['field_photos'],1)."</pre>";
	}
	
	return $form;
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

/*
 * Implements theme_image_style().
 */

function gms_image_style($variables){
	$variables['attributes']['class'][] ='img-responsive';
	$variables['attributes']['class'][] ='col-lg-12';
	return theme('image', $variables);
}

/*
 * Implements theme_image_widget().
 */
function gms_image_widget($variables) {
  $element = $variables['element'];
	if ($element['#entity']->type == 'project'){
		$output = '';
		$output .= '<div class="image-widget form-managed-file clearfix edit-photos-container">';
		//$element['upload_button']['#value']= t('Upload photos');
		//$element['remove_button']['#value'] = t('x');
		//$element['upload_button']['#attributes']['class'][] = 'btn btn_primary';
		/*$output .= '<div class="proj-photo-upload-form col-sm-8">';
		$output .= drupal_render($element['upload']); //."<br/>";
		$output .= '</div>';
		$output .= '<div class="proj-photo-upload-button col-sm-4">';
		$output .= drupal_render($element['upload_button']); //."<br/>";
		$output .= '</div>';*/

		//hide($element['upload']);
		//hide($element['upload_button']);

		if (isset($element['preview'])) {
			$output .= '<div class="image-preview">';
			$output .= drupal_render($element['preview']);
			$output .= '</div>';
		}

		
		//$element['remove_button']['#value'] = 'x';
		//$element['remove_button'] = FALSE;
		//$output .= "<pre>".print_r($element['remove_button'],1)."</pre>";
		$output .= '<div class="image-widget-data">'; //.$element['fid']['#value'];
		if ($element['fid']['#value'] != 0) {
			$element['alt']['#title'] = 'Source';
			$element['alt']['#attributes']['class'][] = 'edit-project-photo-alt';
			//$output .= "<pre>".print_r($element['alt'],1)."</pre>";
			$output .= '<div class="edit-project-photo-alt-container">';
			$output .= drupal_render($element['alt']);
			$output .= '</div>';

			$element['title']['#attributes']['class'][] = 'edit-project-photo-title';
			
			$output .= '<div class="edit-project-photo-title-container">';
			$output .= drupal_render($element['title']);
			$output .= '</div>';
			//$output .= "<pre>".print_r($element['title'],1)."</pre>";
			hide($element['filename']);
			//$element['filename']['#markup'] .= ' <span class="file-size">(' . format_size($element['#file']->filesize) . ')</span> ';
		} /*else {
			//$output .= "<pre>".print_r(array_keys($element),1)."</pre>";
			//$output .= "<pre>".print_r($variables,1)."</pre>";
		}*/
		
		//$element['upload']['#theme_wrappers'] = array('image_widget');
		
		$output .= drupal_render_children($element);
		$output .= '</div>';
		$output .= '</div>';
	}

	return $output;
}

/*function gms_get_login_block($variables) {
    $form = drupal_get_form('user_login_block');
    return theme_status_messages($variables).$form;
}*/

/*function gms_field_widget_form_alter(&$element, &$form_state, $context) {
  // Add a css class to widget form elements for all fields of type mytype.
  if ($context ['field']['type'] == 'form-type-mfw-managed-file') {
    // Be sure not to overwrite existing attributes.
    $element ['#attributes']['class'][] = 'myclass';
  }
}*/

/*function gms_file_widget_multiple($variables) {
	$element = $variables ['element'];

  // Special ID and classes for draggable tables.
  $weight_class = $element ['#id'] . '-weight';
  $table_id = $element ['#id'] . '-table';

  // Build up a table of applicable fields.
  $headers = array();
  $headers [] = t('File information');
  if ($element ['#display_field']) {
    $headers [] = array(
      'data' => t('Display'),
      'class' => array('checkbox'),
    );
  }
  $headers [] = t('Weight');
  $headers [] = t('Operations');

  // Get our list of widgets in order (needed when the form comes back after
  // preview or failed validation).
  $widgets = array();
  foreach (element_children($element) as $key) {
    $widgets [] = &$element [$key];
  }
  usort($widgets, '_field_sort_items_value_helper');

  $rows = array();
  foreach ($widgets as $key => &$widget) {
    // Save the uploading row for last.
    if ($widget ['#file'] == FALSE) {
      $widget ['#title'] = $element ['#file_upload_title'];
      $widget ['#description'] = $element ['#file_upload_description'];
      continue;
    }
		

    // Delay rendering of the buttons, so that they can be rendered later in the
    // "operations" column.
    $operations_elements = array();
    foreach (element_children($widget) as $sub_key) {
      if (isset($widget [$sub_key]['#type']) && $widget [$sub_key]['#type'] == 'submit') {
        hide($widget [$sub_key]);
        $operations_elements [] = &$widget [$sub_key];
      }
    }

    // Delay rendering of the "Display" option and the weight selector, so that
    // each can be rendered later in its own column.
    if ($element ['#display_field']) {
      hide($widget ['display']);
    }
    hide($widget ['_weight']);

    // Render everything else together in a column, without the normal wrappers.
    $widget ['#theme_wrappers'] = array();
    $information = drupal_render($widget);

    // Render the previously hidden elements, using render() instead of
    // drupal_render(), to undo the earlier hide().
    $operations = '';
    foreach ($operations_elements as $operation_element) {
      $operations .= render($operation_element);
    }
    $display = '';
    if ($element ['#display_field']) {
      unset($widget ['display']['#title']);
      $display = array(
        'data' => render($widget ['display']),
        'class' => array('checkbox'),
      );
    }
    $widget ['_weight']['#attributes']['class'] = array($weight_class);
    $weight = render($widget ['_weight']);

    // Arrange the row with all of the rendered columns.
    $row = array();
    $row [] = $information;
    if ($element ['#display_field']) {
      $row [] = $display;
    }
    $row [] = $weight;
    $row [] = $operations;
    $rows [] = array(
      'data' => $row,
      'class' => isset($widget ['#attributes']['class']) ? array_merge($widget ['#attributes']['class'], array('draggable')) : array('draggable'),
    );
  }

  drupal_add_tabledrag($table_id, 'order', 'sibling', $weight_class);

  $output = '';
  $output = empty($rows) ? '' : theme('table', array('header' => $headers, 'rows' => $rows, 'attributes' => array('id' => $table_id)));
  $output .= drupal_render_children($element);
  return $output;
}*/


/*function gms_filefield_widget_field_widget_form(&$form, &$form_state, $field, $instance, $langcode, $items, $delta, $element) {
	i
	$elements['#file_upload_title'] = t('Add a new photo');
	return $elements;
}*/

/*function gms_field_widget_form_alter(&$element, &$form_state, $context){
	$output .= "<pre>".print_r($element,1)."</pre>";
}*/

/*function gms_image_field_widget_process($element, &$form_state, $form) {
  //dpm($element);
  // Hide the remove button
  $element['remove_button']['#type'] = 'hidden';
	echo "<pre>".print_r($element['remove_button'],1)."</pre>";

  // Return the altered element
  return $element;
}*/

/*function gms_image_widget_multiple($variables){
	dpm($variables);
	$element = $variables ['element'];
	//hide($headers);
	$output .= "<pre>".print_r($element,1)."</pre>";
	$output .= drupal_render_children($element);
	return $output;
}*/

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

// remove fieldset wrapper from date fields
/*function gms_date_combo($variables) {
  return theme('form_element', $variables);
}*/

/*function gms_views_pre_render($view = "leaflet_view_test"){
	if ($view->name == "leaflet_view_test"){
		print_r($view);
	}
}*/

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

