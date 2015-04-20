<?php

/**
 * @file
 * Blocks available in IP Geolocation Views & Maps.
 */

define('IP_GEOLOC_VISITOR_DEFAULT_FIND_LABEL', 'Find me');
define('IP_GEOLOC_VISITOR_DEFAULT_ADDRESS_LABEL', 'Move me');

/**
 * Implements hook_block_info().
 */
function ip_geoloc_block_info() {
  $blocks['current_visitor_map'] = array(
    'info' => t("IPGV&M: Map (Google) centered on visitor's location"),
    'cache' => DRUPAL_NO_CACHE,
  );
  $blocks['recent_visitors_map'] = array(
    'info' => t('IPGV&M: Map (Google) of @n most recent visitors', array('@n' => variable_get('ip_geoloc_recent_visitors_map_number_of_visitors', 20))),
    'cache' => DRUPAL_NO_CACHE,
  );
  $blocks['address_lookup'] = array(
    'info' => t('IPGV&M: Address lookup based on IP'),
    'cache' => DRUPAL_NO_CACHE,
  );
  $blocks['geocode_address'] = array(
    'info' => t('IPGV&M: Set my location'),
    'cache' => DRUPAL_NO_CACHE,
  );
  return $blocks;
}

/**
 * Implements hook_block_configure().
 *
 * @todo refactor, it's too long.
 */
function ip_geoloc_block_configure($delta = '') {
  $form = array();
  if ($delta == 'address_lookup') {
    return $form;
  }

  if ($delta == 'geocode_address') {
    $form['address'] = array(
      '#type' => 'fieldset',
      '#title' => t('Visitor location'),
      '#description' => t('This panel and the next offer a number of ways for the visitor to set the focus of maps created through this module.'),
    );
    $form['address']['ip_geoloc_visitor_find'] = array(
      '#type' => 'checkbox',
      '#title' => t('Find visitor using HTML5 position detection'),
      '#default_value' => variable_get('ip_geoloc_visitor_find', TRUE),
      '#description' => t('Displays a button for the visitor to locate themselves on the map. Takes advantage of WiFi and GPS technologies.'),
    );
    $form['address']['ip_geoloc_visitor_find_label'] = array(
      '#type' => 'textfield',
      '#title' => t('Label for find visitor button'),
      '#size' => 30,
      '#default_value' => filter_xss_admin(variable_get('ip_geoloc_visitor_find_label', '')),
      '#description' => t('Defaults to %default. You may use most HTML tags. Use <em>&lt;none&gt;</em> for no label.', array(
        '%default' => t(IP_GEOLOC_VISITOR_DEFAULT_FIND_LABEL),
      )),
      '#states' => array(
        'visible' => array('input[name="ip_geoloc_visitor_find"]' => array('checked' => TRUE),
      )),
    );
    $form['address']['ip_geoloc_visitor_address_editable'] = array(
      '#type' => 'checkbox',
      '#title' => t('Address editable'),
      '#default_value' => variable_get('ip_geoloc_visitor_address_editable', TRUE),
      '#description' => t('If ticked, the visitor may tweak and re-submit their reverse-geocoded address. It will be geocoded to latitude & longitude to become their new location.<br/>If not ticked, then this field simply displays the current reverse-geocoded address, without a submit button.'),
    );
    $form['address']['ip_geoloc_visitor_address_label'] = array(
      '#type' => 'textfield',
      '#title' => t('Label for editable address'),
      '#size' => 30,
      '#default_value' => filter_xss_admin(variable_get('ip_geoloc_visitor_address_label', '')),
      '#description' => t('Defaults to %default. You may use most HTML tags. Use <em>&lt;none&gt;</em> for no label.', array(
        '%default' => t(IP_GEOLOC_VISITOR_DEFAULT_ADDRESS_LABEL),
      )),
      '#states' => array(
        'visible' => array('input[name="ip_geoloc_visitor_address_editable"]' => array('checked' => TRUE),
      )),
    );
    $form['address']['ip_geoloc_address_element'] = array(
      '#type' => 'radios',
      '#title' => t('UI element displayed to enter and show address'),
      '#options' => array(
        0 => t('text field'),
        1 => t('text area, 1 line'),
        2 => t('text area, 2 lines'),
        3 => t('text area, 3 lines'),
      ),
      '#default_value' => variable_get('ip_geoloc_address_element', 2),
      '#description' => t('Text areas may be resized by the visitor, text fields cannot.')
    );

    $form['address']['ip_geoloc_visitor_address_default'] = array(
      '#type' => 'textarea',
      '#rows' => 2,
      '#title' => t('Default partial address'),
      '#default_value' => check_plain(variable_get('ip_geoloc_visitor_address_default', '')),
      '#description' => t("You may use this to pre-populate the visitor address box with, say, a country, so that both user and geocoding service have a context to the partial address entered. You may use comma's, newlines and blank lines if you wish."),
    );

    $vocabularies = array();
    foreach (taxonomy_get_vocabularies() as $vid => $vocabulary) {
      foreach (field_info_instances('taxonomy_term', $vocabulary->machine_name) as $field_name => $field) {
        $field_info = field_info_field($field_name);
        if ($field_info['type'] == 'geofield') {
          $vocabularies[$vid] = $vocabulary->name;
          break;
        }
      }
    }
    $description = t('The region selector allows the visitor to zoom in on a geographical area, such as a province or wine region, picked from a taxonomy vocabulary. The vocabulary must have a <a target="geofield" href="@url">Geofield</a> attached, providing the latitude & longitude of the centre of the region.', array(
      '@url' => url('http://drupal.org/project/geofield'),
    ));
    $form['region'] = array(
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#title' => t('Geographical region selector'),
      '#description' => $description,
    );
    $form['region']['ip_geoloc_geo_vocabulary_id'] = array(
      '#type' => 'select',
      '#options' => array(0 => '--' . t('None') . '--') + $vocabularies,
      '#default_value' => variable_get('ip_geoloc_geo_vocabulary_id', 0),
    );
    if (empty($vocabularies)) {
      $form['region']['ip_geoloc_geo_vocabulary_id']['#description'] = t('You do not appear to have any vocabularies on which a Geofield is defined. You cannot enable this feature until you do.');
    }
    $form['region']['ip_geoloc_region_autocomplete'] = array(
      '#type' => 'radios',
      '#title' => t('UI element displayed to select geographical region'),
      '#options' => array(
        0 => t('Select (drop-down)'),
        1 => t('Autocomplete textfield'),
      ),
      '#default_value' => variable_get('ip_geoloc_region_autocomplete', 0),
    );
    $form['region']['ip_geoloc_region_parent'] = array(
      '#type' => 'select',
      '#title' => t('Center to use'),
      '#options' => array(
        0 => t('Selected region'),
        1 => t('Parent of selected region'),
        2 => t('Grandparent of selected region'),
        3 => t('Great-grandparent of selected region'),
      ),
      '#default_value' => variable_get('ip_geoloc_region_parent', 0),
      '#description' => t('If the selected parent does not exist the next parent up will be used instead.'),
    );

    $form['redirection'] = array(
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#title' => t('Redirection'),
      '#description' => t('')
    );
    global $base_url;
    $form['redirection']['ip_geoloc_address_redirect'] = array(
      '#type' => 'textfield',
      '#field_prefix' => "$base_url/",
      '#title' => t('Redirect upon submit'),
      '#default_value' => variable_get('ip_geoloc_address_redirect', ''),
      '#description' => t('The page to redirect to after the user has pressed "Go". Use <em>&lt;front&gt;</em> for the front page.'),
    );
    return $form;
  }

  if ($delta == 'recent_visitors_map') {
    $form['ip_geoloc_recent_visitors_map_number_of_visitors'] = array(
      '#type' => 'textfield',
      '#title' => t('Number of recent visitor locations to show on the map.'),
      '#default_value' => variable_get('ip_geoloc_recent_visitors_map_number_of_visitors', 20),
      '#description' => t('Must be greater than 0. Clustering is not supported, all markers are shown individually.'),
    );
  }
  $form['ip_geoloc_' . $delta . '_div_style'] = array(
    '#type' => 'textfield',
    '#title' => t('CSS style attribute(s) applied to the HTML DIV element that is placeholding the map'),
    '#default_value' => variable_get('ip_geoloc_' . $delta . '_div_style', IP_GEOLOC_MAP_DIV_DEFAULT_STYLE),
    '#description' => t('The default %default_style results in a map of 300 pixels high, with a width bounded by the element that contains it. Do not enter quotes or equal signs.',
      array('%default_style' => IP_GEOLOC_MAP_DIV_DEFAULT_STYLE)),
  );

  switch ($delta) {

    case 'current_visitor_map':
      $form['ip_geoloc_current_visitor_map_options'] = array(
        '#type' => 'textfield',
        '#size' => 120,
        '#title' => t('Map options'),
        '#default_value' => variable_get('ip_geoloc_current_visitor_map_options', IP_GEOLOC_CURRENT_VISITOR_MAP_OPTIONS),
        '#description' => t("The default %default_options produces a street map zoomed in to level 15. A list of map options can be found <a href='!google_map_docs'>here</a>. Remember to separate options with comma's, not semi-colons, and make sure your quotes match.",
          array(
            '%default_options' => IP_GEOLOC_CURRENT_VISITOR_MAP_OPTIONS,
            '!google_map_docs' => IP_GEOLOC_DOC_GOOGLE_MAP_OPTIONS,
          )),
      );
      break;

    case 'recent_visitors_map':
      $form['ip_geoloc_recent_visitors_map_options'] = array(
        '#type' => 'textfield',
        '#size' => 120,
        '#title' => t('Map options'),
        '#default_value' => variable_get('ip_geoloc_recent_visitors_map_options', IP_GEOLOC_RECENT_VISITORS_MAP_OPTIONS),
        '#description' => t("The default %default_options produces a world map zoomed in to level 2. A list of map options can be found <a href='!google_map_docs'>here</a>. Remember to separate options with comma's, not semi-colons, and make sure your quotes match.",
          array(
            '%default_options' => IP_GEOLOC_RECENT_VISITORS_MAP_OPTIONS,
            '!google_map_docs' => IP_GEOLOC_DOC_GOOGLE_MAP_OPTIONS,
          )),
      );
      break;
  }
  return $form;
}

/**
 * Implements hook_block_save().
 */
function ip_geoloc_block_save($delta = '', $edit = array()) {
  if ($delta == 'address_lookup') {
    return;
  }
  if ($delta == 'geocode_address') {
    foreach ($edit as $variable => $value) {
      if (strpos($variable, 'ip_geoloc') === 0) {
        variable_set($variable, $value);
      }
    }
    return;
  }
  // Save options common to all maps.
  variable_set('ip_geoloc_' . $delta . '_div_style', $edit['ip_geoloc_' . $delta . '_div_style']);
  ip_geoloc_save_map_options('ip_geoloc_' . $delta . '_options', $edit['ip_geoloc_' . $delta . '_options']);

  switch ($delta) {
    case 'recent_visitors_map':
      variable_set('ip_geoloc_recent_visitors_map_number_of_visitors', $edit['ip_geoloc_recent_visitors_map_number_of_visitors']);
      break;
  }
}

/**
 * Implements hook_block_view().
 */
function ip_geoloc_block_view($delta = '') {
  $block = array();
  $map_style = variable_get('ip_geoloc_' . $delta . '_div_style', IP_GEOLOC_MAP_DIV_DEFAULT_STYLE);

  switch ($delta) {

    case 'current_visitor_map':
      $block['subject'] = t('Your location');
      $map_options = variable_get('ip_geoloc_current_visitor_map_options', IP_GEOLOC_CURRENT_VISITOR_MAP_OPTIONS);
      $location = ip_geoloc_get_visitor_location();
      $block['content'] = theme(array('ip_geoloc_map_current_visitor'), array(
        'div_id' => 'ip-geoloc-block-current-visitor-map',
        'map_options' => $map_options,
        'map_style' => $map_style,
        // Lat,Lon NULL values will instigate HTML5 position retrieval. The
        // user will be prompted by the browser to give permission.
        'latitude'  => isset($location['latitude']) ? (float) $location['latitude'] : (empty($location['fixed_address']) ? NULL : 0.0),
        'longitude' => isset($location['longitude']) ? (float) $location['longitude'] : (empty($location['fixed_address']) ? NULL : 0.0),
        'info_text' => isset($location['formatted_address']) ? $location['formatted_address'] : NULL,
      ));
      break;

    case 'recent_visitors_map':
      $block['subject'] = t('Recent visitors');
      $how_many = variable_get('ip_geoloc_recent_visitors_map_number_of_visitors', 20);
      if ($how_many > 0) {
        $locations = ip_geoloc_get_recent_visitor_locations($how_many);
        $map_options = variable_get('ip_geoloc_recent_visitors_map_options', IP_GEOLOC_RECENT_VISITORS_MAP_OPTIONS);
        $block['content'] = theme(array('ip_geoloc_visitor_map'), array(
          'locations' => $locations,
          'div_id' => 'ip-geoloc-block-recent-visitors-map',
          'map_options' => $map_options,
          'map_style' => $map_style,
        ));
      }
      break;

    case 'address_lookup':
      $block['subject'] = t('Global address lookup');
      $block['content'] = drupal_get_form('ip_geoloc_address_lookup');
      break;

    case 'geocode_address':
      $block['subject'] = t('Set my location');
      $block['content'] = drupal_get_form('ip_geoloc_geocode_address');
      break;
  }
  return $block;
}

/**
 * Save map options to a Drupal variable.
 *
 * @param string $var_name
 *   The variable name to save
 *
 * @param string $map_options
 *   The map options to save
 */
function ip_geoloc_save_map_options($var_name, $map_options) {
  $map_options_decoded = drupal_json_decode($map_options);
  if ($map_options_decoded == NULL) {
    drupal_set_message(t("Sytax error in map options. These map options may not work: '%map_options'", array('%map_options' => $map_options)), 'warning');
  }
  variable_set($var_name, $map_options);
}

/**
 * Get recent visitor locations.
 *
 * @param int $how_many
 *   how many locations to retrieve
 *
 * @return object
 *   object of locations found
 */
function ip_geoloc_get_recent_visitor_locations($how_many) {
  $locations = array();
  if (db_table_exists('accesslog')) {
    // A LEFT JOIN would also pick up new IP addresses that are about to be
    // inserted into the {accesslog}.
    // However a LEFT JOIN in this query can easily make it 100 times slower
    // than the INNER JOIN used below and would only be relevant for the very
    // first click from a new IP address or in the case where the IP address was
    // removed from the {accesslog}.
    $result = db_query_range('SELECT DISTINCT ip_address, latitude, longitude, formatted_address, COUNT(a.timestamp) AS visit_count, MAX(a.timestamp) AS last_visit FROM {ip_geoloc} i INNER JOIN {accesslog} a ON i.ip_address = a.hostname GROUP BY i.ip_address ORDER BY last_visit DESC', 0, $how_many);
    $separator = '<br/>';
    foreach ($result as $location) {
      // Prevent older IP address locations overwriting the latest location.
      if (!isset($locations[$location->ip_address])) {
        $loc_rendered = new stdClass();
        $loc_rendered->latitude = $location->latitude;
        $loc_rendered->longitude = $location->longitude;
        $loc_rendered->balloon_text = t('IP address') . ' ' . $location->ip_address . '<br/>'
         . $location->formatted_address . $separator
         . t('#visits') . ' ' . (empty($location->visit_count) ? '?' : $location->visit_count)
         . ', ' . t('last visit') . ' ' . (empty($location->last_visit) ? '?' : format_date($location->last_visit, 'short'));
        $locations[$location->ip_address] = $loc_rendered;
      }
    }
  }
  return $locations;
}

/**
 * Generates a simple form for collecting the IP address to be reverse-geocoded.
 *
 * @ingroup forms
 */
function ip_geoloc_address_lookup($form, &$form_state) {
  $last_visit = isset($form_state['storage']['last_visit']) ? $form_state['storage']['last_visit'] : '';
  $formatted_address = isset($form_state['storage']['formatted_address']) ? $form_state['storage']['formatted_address'] : '';
  if (!empty($last_visit) || !empty($formatted_address)) {
    $prefix = '<div class="ip_geoloc_address_lookup">' . $formatted_address . (empty($last_visit) ? '' : "<br/>$last_visit") . '</div>';
  }
  $form['ip_address'] = array(
    '#type' => 'textfield',
    '#title' => t('IP address'),
    '#default_value' => '',
    '#size' => 16,
    '#required' => FALSE,
    '#prefix' => isset($prefix) ? $prefix : NULL,
  );
  if (user_access('administer site configuration')) {
    $form['store'] = array(
      '#type' => 'checkbox',
      '#title' => t('If found, store on IP geolocation database for future reference.'),
      '#default_value' => FALSE,
    );
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Lookup'),
    '#submit' => array('ip_geoloc_lookup'),
  );
  return $form;
}

/**
 * Generates a simple form for collecting the address to be geocoded.
 *
 * The value of the 'fixed_address' radio-button is stored on the location, as
 * stored on the session.
 *
 * @ingroup forms
 */
function ip_geoloc_geocode_address($form, &$form_state) {

  $location = ip_geoloc_get_visitor_location();

  $has_find_visitior = variable_get('ip_geoloc_visitor_find', TRUE);
  $is_address_editable = variable_get('ip_geoloc_visitor_address_editable', TRUE);
  $geo_vocabulary_id = variable_get('ip_geoloc_geo_vocabulary_id', 0);

  $find_visitor_label = variable_get('ip_geoloc_visitor_find_label', '');
  if ($has_find_visitior && $find_visitor_label != '<none>') {
    $options[0] = $find_visitor_label ? filter_xss_admin($find_visitor_label) : t(IP_GEOLOC_VISITOR_DEFAULT_FIND_LABEL);
  }
  $editable_address_label = variable_get('ip_geoloc_visitor_address_label', '');
  if ($is_address_editable && $editable_address_label != '<none>') {
    $options[1] = $editable_address_label ? filter_xss_admin($editable_address_label) : t(IP_GEOLOC_VISITOR_DEFAULT_ADDRESS_LABEL);
  }
  if ($geo_vocabulary_id) {
    $options[2] = t('Region');
  }
  if (!empty($options)) {
    $form['fixed_address'] = array(
      '#type' => 'radios',
      '#options' => $options,
      '#default_value' => isset($location['fixed_address']) ? (int) $location['fixed_address'] : key($options),
    );
  }
  if ($geo_vocabulary_id) {
    $regions = array(0 => '--' . t('none') . '--');
    foreach (taxonomy_get_tree($geo_vocabulary_id) as $term) {
      $regions[$term->tid] = str_repeat('-', $term->depth) . $term->name;
      if (isset($location['region']) && $location['region'] == $term->tid) {
        $current_region_name = $term->name;
      }
    }
    if (variable_get('ip_geoloc_region_autocomplete')) {
      $not_set = ''; // <' . t('not set') . '>';
      // Use an autocomplete textfield, instead of a drop-down selector.
      $form['region'] = array(
        '#type' => 'textfield',
        '#size' => 29,
        '#default_value' => isset($current_region_name) ? $current_region_name : $not_set,
        '#description' => t('Type the first letters of a region.'),
        // Refer to hook_menu().
        '#autocomplete_path' => 'ip_geoloc/region_autocomplete',
        '#executes_submit_callback' => TRUE,
      );
    }
    else {
      $form['region'] = array(
        '#type' => 'select',
        '#options' => $regions,
        '#default_value' => isset($location['region']) ? $location['region'] : 0,
      );
    }
    $form['region']['#states'] = array(
      'visible' => array('input[name="fixed_address"]' => array('value' => 2)),
    );
  }
  $textarea = variable_get('ip_geoloc_address_element', 2);
  $default_address = check_plain(variable_get('ip_geoloc_visitor_address_default', ''));
  $form['street_address'] = array(
    '#type' => $textarea ? 'textarea' : 'textfield',
    '#rows' => $textarea,
    '#default_value' => empty($location['formatted_address']) ? $default_address : $location['formatted_address'],
    '#disabled' => isset($location['fixed_address']) ? $location['fixed_address'] != 1 : !$is_address_editable,
    '#states' => array(
      'enabled' => array('input[name="fixed_address"]' => array('value' => 1)),
      'invisible' => array('input[name="fixed_address"]' => array('value' => 2)),
    ),
  );
  if ($has_find_visitior || $is_address_editable || $geo_vocabulary_id) {
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Go'),
      '#submit' => array('ip_geoloc_geocode'),
    );
  }
  $form['#attributes']['class'][] = 'ip-geoloc-address';
  $form['#attached']['css'][] = drupal_get_path('module', 'ip_geoloc') . '/css/ip_geoloc_all.css';
  return $form;
}

/**
 * Lookup geoloc data.
 */
function ip_geoloc_lookup($form, &$form_state) {
  $store = !empty($form_state['values']['store']);
  $location = ip_geoloc_get_location_by_ip(trim($form_state['values']['ip_address']), TRUE, $store);
  if (db_table_exists('accesslog')) {
    $last_visit = db_query('SELECT MAX(timestamp) FROM {accesslog} WHERE hostname = :ip_address', array(':ip_address' => $location['ip_address']))->fetchField();
    if ($last_visit) {
      $last_visit = format_date($last_visit, 'short');
      $form_state['storage']['last_visit'] = t('<strong>Last visit:</strong> %date', array('%date' => $last_visit));
    }
  }
  $form_state['storage']['formatted_address']
    = isset($location['formatted_address']) ? t('%ip: <strong>%address</strong>', array('%ip' => $location['ip_address'], '%address' => $location['formatted_address'])) : '';
  // To preserve entered values and storage array.
  $form_state['rebuild'] = TRUE;
}

/**
 * Geocode the submitted street address (if any), using the Geocoder API.
 *
 * Stores the geocoded lat/long and address info on the session.
 *
 * @param array $form
 *   A form array
 * @param array $form_state
 *   The form's current state
 */
function ip_geoloc_geocode($form, &$form_state) {

  $location = _ip_geoloc_get_session_value('location');

  $geocode_address = isset($form_state['values']['fixed_address'])
    ? $form_state['values']['fixed_address'] == 1
    : variable_get('ip_geoloc_visitor_address_editable', TRUE) && !variable_get('ip_geoloc_visitor_find', TRUE);

  if ($geocode_address) {
    // Note the use of 'input' rather than 'values'. We don't want to read
    // what WAS, but what IS typed now.
    $entered_address = check_plain($form_state['input']['street_address']);
    if (empty($entered_address)) {
      $location = array(
        'provider' => 'user',
        'ip_address' => ip_address(),
      );
    }
    elseif (module_exists('geocoder')) {
      // Use Google server-side API to retrieve lat/long from entered address.
      $point = geocoder('google', $entered_address);

      if (!$point) {
        drupal_set_message(t('The address %address could not be geocoded to a location.', array('%address' => $entered_address)), 'warning');
        return;
      }
      $location = array(
        'provider' => 'user+google',
        'is_updated' => TRUE,
        'ip_address' => ip_address(),
        'latitude' => $point->coords[1],
        'longitude' => $point->coords[0],
        'formatted_address' => $point->data['geocoder_formatted_address'],
        'accuracy' => $point->data['geocoder_accuracy'],
      );
      // Flatten the point object into a straight location array.
      foreach ($point->data['geocoder_address_components'] as $component) {
        if (!empty($component->long_name)) {
          $type = $component->types[0];
          $location[$type] = $component->long_name;
          if ($type == 'country' && !empty($component->short_name)) {
            $location['country_code'] = $component->short_name;
          }
        }
      }
    }
  }
  elseif (isset($form_state['values']['fixed_address']) && $form_state['values']['fixed_address'] == 2) {
    // "Region" pressed
    $region = $form_state['values']['region'];
    if (!is_numeric($region)) {
      $geo_vocabulary_id = variable_get('ip_geoloc_geo_vocabulary_id', 0);
      foreach (taxonomy_get_tree($geo_vocabulary_id) as $term) {
        if (strcasecmp($term->name, $region) === 0) {
          $region = $term->tid;
          break;
        }
      }
    }
    $parent = variable_get('ip_geoloc_region_parent', 0);
    $location = ip_geoloc_get_location_from_term($region, $parent);
  }
  // If "Find me" was pressed, we set a flag indicating to ip_geoloc_init() that
  // we want to find the visitor's current position using their HTML5-based
  // browser immediately, not waiting for the next cycle.
  $reverse_geocode_now = variable_get('ip_geoloc_visitor_find', TRUE) && empty($form_state['values']['fixed_address']);
  _ip_geoloc_set_session_value('reverse_geocode_now', (int) $reverse_geocode_now);

  $location['fixed_address'] = isset($form_state['values']['fixed_address'])
    ? $form_state['values']['fixed_address']
    : $geocode_address;

  _ip_geoloc_set_session_value('location', $location);

  $redirect = variable_get('ip_geoloc_address_redirect');
  if (!empty($redirect)) {
    $form_state['redirect'] = $redirect;
  }

  // No need to remember the form state. It's all kept on the session.
  $form_state['rebuild'] = FALSE;
}

/**
 * Returns the location object belonging to supplied region taxonomy term id.
 *
 * @param int $tid
 *   Taxonomy term identifier
 * @param int $return_parent
 *   To return location of the parent (1) or grand-parent (2) rather than the
 *   supplied region term.
 *
 * @return array, location info
 */
function ip_geoloc_get_location_from_term($tid, $parent = 0) {

  $parents = taxonomy_get_parents_all($tid);
  if (empty($parents)) {
    return array('provider' => 'taxonomy', 'ip_address' => ip_address());
  }
  $term = $parents[min($parent, count($parents) - 1)];

  // Get lat,lng from the Geofield on this term
  $geo_vocabulary_id = variable_get('ip_geoloc_geo_vocabulary_id', 0);
  $vocabulary = taxonomy_vocabulary_load($geo_vocabulary_id);
  foreach (field_info_instances('taxonomy_term', $vocabulary->machine_name) as $field_name => $field_instance) {
    $field = field_info_field($field_name);
    if ($field['type'] == 'geofield') {
      $value = reset($term->{$field_name});
      $value = reset($value);
      return array(
        'provider' => 'taxonomy',
        'ip_address' => ip_address(),
        'region' => $term->tid,
        'latitude' => $value['lat'],
        'longitude' => $value['lon'],
      //'formatted_address' => $term->name,
      );
    }
  }
}
