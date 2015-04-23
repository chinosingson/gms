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
function gms_date_combo($variables) {
  return theme('form_element', $variables);
}