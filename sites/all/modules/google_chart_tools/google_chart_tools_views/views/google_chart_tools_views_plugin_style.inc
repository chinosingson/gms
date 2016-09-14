<?php
/**
 * @file
 * Contains the Google Chart Tools display plugin.
 */

/**
 * The views style plugin.
 *
 * @ingroup views_style_plugins
 */
class google_chart_tools_views_plugin_style extends views_plugin_style {

	private static $chartAreas = array('left', 'top', 'width', 'height');
  
	function option_definition() {
    $options = parent::option_definition();

    $options['title'] = array('default' => '');
		$options['titlecolor'] = array('default' => '');
    $options['haxis_title'] = array('default' => '');
    $options['vaxis_title'] = array('default' => '');
    $options['type'] = array('default' => GOOGLE_CHART_TOOLS_DEFAULT_CHART);
		$options['backgroundcolor'] = array('default' => '');
		$options['fontname'] = array('default' => '');
    $options['width'] = array('default' => 600);
    $options['height'] = array('default' => 400);
    $options['curve'] = array('default' => 0);
    $options['3d'] = array('default' => 0);
    $options['isstacked'] = array('default' => FALSE);
    $options['pointsize'] = array('default' => 0);
    $options['colors'] = array('default' => '');
    $options['legend'] = array(
      'legend_position' => '',
      'legend_alignment' => '',
			'textcolor' => '',
		);
    $options['chartArea'] = array(
      'left' => '',
      'top' => '',
      'width' => '',
      'height' => '',
    );
    
    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    // Only allow grouping on the first column.
    $form['grouping'] = array_slice($form['grouping'], 0, 1);
    $form['grouping'][0]['field']['#options'] = array_slice($form['grouping'][0]['field']['#options'], 0, 2);

    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#description' => t('Chart title. You may use "%" in your text to represent values that will be used for contextual filters.'),
      '#default_value' => $this->options['title'],
    );
		$form['titlecolor'] = array(
      '#type' => 'textfield',
      '#title' => t('Title Text Color'),
      '#description' => t('The text color of the chart title. Use a hex value #ff00ff.'),
      '#default_value' => $this->options['titlecolor'],
		);
    $form['haxis_title'] = array(
      '#type' => 'textfield',
      '#title' => t('hAxis Title'),
      '#description' => t('Horizontal axis title. You may use "%" in your text to represent values that will be used for contextual filters.'),
      '#default_value' => $this->options['haxis_title'],
    );
    $form['vaxis_title'] = array(
      '#type' => 'textfield',
      '#title' => t('vAxis Title'),
      '#description' => t('Vertical axis title. You may use "%" in your text to represent values that will be used for contextual filters.'),
      '#default_value' => $this->options['vaxis_title'],
    );
    $form['type'] = array(
      '#type' => 'select',
      '#options' => google_chart_tools_load_types(),
      '#title' => t('Type'),
      '#description' => t('Chart type, see <a href="https://google-developers.appspot.com/chart/interactive/docs/gallery">Google Chart Tools gallery</a>.'),
      '#required' => TRUE,
      '#description' => t('Ex. LineChart, PieChart, ColumnChart, AreaChart, Gauge, BarChart, etc....'),
      '#default_value' => $this->options['type'],
    );
    $form['width'] = array(
      '#type' => 'textfield',
      '#title' => t('Width'),
      '#description' => t('Chart width in pixels'),
      '#size' => 8,
      '#required' => TRUE,
      '#default_value' => $this->options['width'],
    );
    $form['height'] = array(
      '#type' => 'textfield',
      '#title' => t('Height'),
      '#description' => t('Chart height in pixels'),
      '#size' => 8,
      '#required' => TRUE,
      '#default_value' => $this->options['height'],
    );
		
		$form['backgroundcolor'] = array(
			'#type' => 'textfield',
			'#title' => t('Background Color'),
			'#description' => t('The background color of the chart area. Use a hex value #ff00ff.'),
			'#default_value' => $this->options['backgroundcolor'],
		);
		
		$form['fontname'] = array(
			'#type' => 'textfield',
			'#title' => t('Font Name'),
			'#description' => t('The font face to use for this chart.'),
			'#default_value' => $this->options['fontname'],
		);
		
		
    $form['curve'] = array(
      '#type' => 'checkbox',
      '#title' => t('Curve'),
      '#description' => t('Use a curve function'),
      '#default_value' => $this->options['curve'],
    );
    $form['3d'] = array(
      '#type' => 'checkbox',
      '#title' => t('3D'),
      '#description' => t('Make chart 3D'),
      '#default_value' => $this->options['3d'],
    );
    $form['isstacked'] = array(
      '#type' => 'checkbox',
      '#title' => t('Stack results'),
      '#description' => t('Render Bar Chart items on top of each other'),
      '#default_value' => $this->options['isstacked'],
    );
    $form['pointsize'] = array(
      '#type' => 'textfield',
      '#title' => t('Data point size'),
      '#description' => t('Pixel radius to allow for datapoints to be sized'),
      '#size' => 10,
      '#default_value' => $this->options['pointsize'],
    );
    $form['colors'] = array(
      '#type' => 'textfield',
      '#title' => t('Colors'),
      '#description' => t('A color strings separated by commas. Ex. red, #004411'),
      '#size' => 32,
      '#default_value' => $this->options['colors'],
    );
    
    // Legend
    
    $form['legend']['position'] = array(
      '#type' => 'select',
      '#title' => t('Legend position'),
      '#options' => array(
        '' => t('Automatic'),
        'right' => t('Right'),
        'top' => t('Top'),
        'bottom' => t('Bottom'),
        'in' => t('In'),
        'none' => t('None'),
      ),
      '#default_value' => $this->options['legend']['position'],
    );
    
    $form['legend']['alignment'] = array(
      '#type' => 'select',
      '#title' => t('Legend alignment'),
      '#options' => array(
        'automatic' => t('Automatic'),
        'start' => t('Start'),
        'center' => t('Center'),
        'end' => t('End'),
      ),
      '#default_value' => $this->options['legend']['alignment'],
    );
		
		$form['legend']['textcolor'] = array(
			'#type' => 'textfield',
			'#title' => t('Legend Text Color'),
			//'#description' => t('The color of the text inside the legend. Use a hex value #ff00ff'),
			'#default_value' => $this->options['legend']['textcolor'],
		);
    
    // Chart area
    
    $form['chartArea'] = array(
      '#type' => 'fieldset',
      '#title' => t('Chart area'),
      '#tree' => TRUE,
      '#collapsible' => TRUE,
    );
    
    foreach (google_chart_tools_views_plugin_style::$chartAreas as $area) {
      $form['chartArea'][$area] = array(
        '#type' => 'textfield',
        '#title' => ucfirst($area),
        '#description' => t('Two formats are supported: a number, or a number followed by %. A simple number is a value in pixels; a number followed by % is a percentage. Leave blank for auto size.'),
        '#default_value' => $this->options['chartArea'][$area],
      );
    }
  }

  function render() {
    $header = array();
    $item = array();
    // Special treat to OrgChart.
    if ($this->options['type'] == 'OrgChart') {
      foreach ($this->render_fields($this->view->result) as $row_index => $row) {
        foreach ($row as $key => $field) {
          if (!$this->view->field[$key]->options['exclude']) {
            $item[$row_index][] = $field;
          }
        }
      }
    }
    // Default chart treat.
    else {
      foreach ($this->view->field as $key => $field) {
        if ($field->position !== 0 && !$field->options['exclude']) {
          $column[] = !empty($field->options['label']) ? $field->options['label'] : $field->definition['title'];
        }
      }

      foreach ($this->render_fields($this->view->result) as $row_index => $row) {
        foreach ($row as $key => $field) {
          if (!$this->view->field[$key]->options['exclude']) {
            if ($this->view->field[$key]->position === 0) {
              $header[$row_index] = $field;
            }
            else {
              $item[$row_index][] = strip_tags($field);
            }
          }
        }
      }

      $item = _google_chart_tools_flip($item);
    }

    // Account for grouping field being set.
    if (isset($this->options['grouping'][0])) {
      _google_chart_tools_apply_grouping_conversion($header, $item);
    }
    // More flexible to allow for future option replacement.
    $option_substitutions = array('vaxis_title', 'haxis_title', 'title');
    // Allow the use of view build info substitutions.
    $tokens = array();
    if (!empty($this->view->build_info['substitutions'])) {
      $tokens = $this->view->build_info['substitutions'];
    }
    $count = 0;
    foreach ($this->view->display_handler->get_handlers('argument') as $arg => $handler) {
      $token = '%' . ++$count;
      if (!isset($tokens[$token])) {
        $tokens[$token] = '';
      }

      // Use strip tags as there should never be HTML in the title.
      // However, we need to preserve special characters like " that
      // were removed by check_plain().
      $tokens['!' . $count] = isset($this->view->args[$count - 1]) ? strip_tags(decode_entities($this->view->args[$count - 1])) : '';
    }

    // Loop through and replace view-tokens.
    foreach ($option_substitutions as $option_name) {
      $this->options[$option_name] = str_replace(array_keys($tokens), array_values($tokens), $this->options[$option_name]);
    }

    $settings['chart'][drupal_clean_css_identifier($this->view->name . '-' . $this->display->id)] = array(
      'header' => !empty($header) ? $header : '',
      'rows' => $item,
      'columns' => !empty($column) ? $column : '',
      'chartType' => $this->options['type'],
      'options' => array(
        'vAxis' => array('title' => $this->options['vaxis_title']),
        'hAxis' => array('title' => $this->options['haxis_title']),
        'forceIFrame' => FALSE,
				'pieSliceBorderColor' => 'transparent',
        'curveType' => $this->options['curve'] ? "function" : "none",
        'is3D' => $this->options['3d'],
        'isStacked' => $this->options['isstacked'],
        'pointSize' => $this->options['pointsize'],  
        'colors' => $this->options['colors'] ? explode(",", str_replace(' ', '', $this->options['colors'])) : NULL,
        'title' => $this->options['title'],
				'titleTextStyle' => array('color'=>$this->options['titlecolor']),
				'backgroundColor' => $this->options['backgroundcolor'],
				'fontName' => $this->options['fontname'],
        'width' => $this->options['width'],
        'height' => $this->options['height'],
        'allowHtml' => TRUE
      )
    );

    if (isset($this->options['legend']['position']) && $this->options['legend']['position'] != '') {
      $settings['chart'][drupal_clean_css_identifier($this->view->name . '-' . $this->display->id)]['options']['legend']['position'] = $this->options['legend']['position'];
    }
    
    if (isset($this->options['legend']['alignment']) && $this->options['legend']['alignment'] != 'automatic') {
      $settings['chart'][drupal_clean_css_identifier($this->view->name . '-' . $this->display->id)]['options']['legend']['alignment'] = $this->options['legend']['alignment'];
    }
		
		if (isset($this->options['legend']['textcolor']) && $this->options['legend']['textcolor'] != ''){
      $settings['chart'][drupal_clean_css_identifier($this->view->name . '-' . $this->display->id)]['options']['legend']['textStyle']['color'] = $this->options['legend']['textcolor'];
		}
    
    foreach (google_chart_tools_views_plugin_style::$chartAreas as $area) {
      if (isset($this->options['chartArea'][$area]) && $this->options['chartArea'][$area] != '') {
        $settings['chart'][drupal_clean_css_identifier($this->view->name . '-' . $this->display->id)]['options']['chartArea'][$area] = $this->options['chartArea'][$area];
      }
    }
    
    if (strpos($_GET['q'], 'admin/structure/views/nojs/preview') === FALSE) {
       // Draw it.
      $ret = draw_chart($settings);
      return $ret['markup'];
    }
  }
}

function _google_chart_tools_flip($arr) {
  $out = array();
  foreach ($arr as $key => $subarr) {
    foreach ($subarr as $subkey => $subvalue) {
      $out[$subkey][$key] = $subvalue;
    }
  }
  return $out;
}

/**
 * SUM datasets based on grouping value.
 */
function _google_chart_tools_apply_grouping_conversion(&$header, &$item) {
  // establish overlap by header name
  $tmp_header = array_flip($header);
  foreach ($header as $value) {
    $tmp_header[$value] = array();
  }
  // populate tmp_header with the array key matches of headerr
  foreach ($header as $key => $value) {
    $tmp_header[$value][] = $key;
  }
  $tmp_item = array();
  // SUM the items into their respective totals
  foreach ($item as $item_key => $datapoints) {
    $tmp_item[$item_key] = array();
    foreach ($tmp_header as $header_key => $header_array) {
      $tmp_item[$item_key][$header_key] = NULL;
      foreach ($header_array as $datapointkey) {
        $tmp_item[$item_key][$header_key] += $item[$item_key][$datapointkey];
      }
    }
  }
  // reset the header so we can merge it
  $header = array();
  foreach ($tmp_header as $header_key => $header_array) {
    // quotes ensure that header_key will be a string
    $header[] = "$header_key";
  }
  // convert the name aggregates back to generic keys
  foreach ($tmp_item as $item_key => $datapoints) {
    $tmp_item[$item_key] = array_values($datapoints);
  }
  // set item to the new datapoints array
  $item = $tmp_item;
}