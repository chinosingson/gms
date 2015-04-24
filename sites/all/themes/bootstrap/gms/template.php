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
	drupal_add_css('http://fonts.googleapis.com/css?family=Lato:400,700%27', array('type' => 'external'));
}

function gms_preprocess_page(&$variables) {
  if (!empty($variables['node']) && $variables['node']->type == 'project') {
    //$variables['show_title'] = FALSE;
		$variables['title'] = FALSE;
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