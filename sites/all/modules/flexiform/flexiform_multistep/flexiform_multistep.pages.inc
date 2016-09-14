<?php
/**
 * @file
 * Page callbacks for Flexiform Multistep.
 */

/**
 * Page callback for CTools Multistep Forms.
 *
 * @param $flexiform
 * @param string $display
 * @param mixed $base_entity_id
 * @param string $step
 *
 * @return array
 *   The page array.
 */
function flexiform_multistep_wrapper($flexiform, $base_entity, $current_step = NULL) {
  global $user;

  // Start setting up our ctools wizard multistep form info.
  ctools_include('wizard');
  ctools_include('object-cache');

  $menu_item = menu_get_item();
  $settings = isset($flexiform->settings['flexiform_multistep']) ? $flexiform->settings['flexiform_multistep'] : array();

  $form_info = array(
    'id' => 'flexiform_multistep_' . $flexiform->form,
    'path' => $menu_item['href'] . '/%step',
  );
  if (isset($settings['form_info'])) {
    $form_info += $settings['form_info'];
  }

  // Add in our pages from the existing field groups.
  $groups = field_group_info_groups('flexiform', $flexiform->form, 'form');
  foreach ($groups as $group) {
    if ($group->format_type == 'flexiform_step') {
      // Skip groups with no children.
      if (!count($group->children)) {
        continue;
      }

      $step = substr($group->group_name, 6);
      $form_info['forms'][$step] = array(
        'form id' => 'flexiform_multistep_step_form',
        'title' => $group->label,
        'weight' => $group->weight,
        'group' => $group,
      );
    }
  }
  uasort($form_info['forms'], 'drupal_sort_weight');
  foreach ($form_info['forms'] as $step => $info) {
    $form_info['order'][$step] = $info['title'];
  }
  $last_step = end(array_keys($form_info['order']));
  $form_info['forms'][$last_step]['submit'] = 'flexiform_multistep_step_form_submit_redirect';

  // Cache important objects to maintain state between steps.
  $cache_key = $form_info['id'];
  if (!empty($base_entity->is_new)) {
    $entity_info = entity_get_info($flexiform->base_entity);
    $cache_key .= ':'.$base_entity->{$entity_info['entity keys']['id']};
  }
  $cache_key .= ':'.$user->uid;

  // Clear cache if were not on a step.
  if (empty($current_step)) {
    ctools_object_cache_clear('flexiform_multistep', $cache_key);
  }
  $object = ctools_object_cache_get('flexiform_multistep', $cache_key);

  if (empty($object['base_entity'])) {
    $object['base_entity'] = $base_entity;
  }

  // Build our form state with our arguments.
  $form_state = array(
    'flexiform_multistep' => TRUE,
    'build_info' => array(
      'args' => array(
        $flexiform,
        $object['base_entity'],
      ),
    ),
    'last_step' => $last_step == $current_step,
  );
  form_load_include($form_state, 'inc', 'flexiform_multistep', 'flexiform_multistep.pages');


  $form_state['object_id'] = $cache_key;
  $form_state['object'] = $object;
  if (isset($object['flexiform_state'])) {
    $form_state['flexiform_state'] = $object['flexiform_state'];
  }

  return ctools_wizard_multistep_form($form_info, $current_step, $form_state);
}

/**
 * Form constructor for a specific step of a multistep flexiform.
 */
function flexiform_multistep_step_form($form, &$form_state, $flexiform, $base_entity) {
  // Get hold of the full form.
  $form = drupal_retrieve_form('flexiform__' . $flexiform->form, $form_state);

  // Add the flexiform state to the object.
  $form_state['object']['flexiform_state'] = $form_state['flexiform_state'];

  // Clear out the standard submission buttons.
  unset($form['actions']);

  // Get our current group.
  $form_state['step_group'] = $form_state['form_info']['forms'][$form_state['step']]['group'];

  // Set up the more advanced trail.
  if (!empty($form['ctools_trail'])) {
    unset($form['ctools_trail']['#markup']);
    $form['ctools_trail']['#theme'] = 'item_list';
    $form['ctools_trail']['#type'] = 'ol';
    $form['ctools_trail']['#attributes']['class'][] = 'flexiform-multistep-progress';
    $form['ctools_trail']['#attributes']['class'][] = 'clearfix';
    $form['ctools_trail']['#attributes']['class'][] = 'inline';

    $past_current = FALSE;
    foreach ($form_state['form_info']['forms'] as $step => $info) {
      $item = array(
        'class' => array($info['group']->group_name),
      );

      if (!$past_current) {
        $params = ctools_wizard_get_path($form_state['form_info'], $step);
        $params[] = array();
        $item['class'][] = 'available';
        $item['data'] = l($info['group']->label, $params[0], $params[1]);
      }
      else {
        $item['data'] = '<span>' . $info['group']->label . '</span>';
      }

      if ($step == $form_state['step']) {
        $item['class'][] = 'active';
        $past_current = TRUE;
      }

      $form['ctools_trail']['#items'][$step] = $item;
    }
  }

  // Clear out the multi step field groups and groups that are not part of this
  // step.
  foreach ($form['#groups'] as $name => $group) {
    if ($group->format_type == 'flexiform_step') {
      unset($form['#groups'][$name]);
      unset($form['#fieldgroups'][$name]);

      foreach ($form['#group_children'] as $child => $parent) {
        if ($parent == $name) {
          unset ($form['#group_children'][$child]);
        }
      }
    }
  }

  // Set the page title if requested.
  if (!empty($form_state['step_group']->format_settings['instance_settings']['page_title'])) {
    drupal_set_title($form_state['step_group']->format_settings['instance_settings']['page_title']);
  }

  // Hide any elements that aren't in this step.
  // First, get a list of all of the children of this step group.
  $step_children = flexiform_multistep_field_group_descendants($form['#groups'], $form_state['step_group']);

  // Remove groups that are not a child of this step.
  foreach ($form['#groups'] as $name => $group) {
    if (!in_array($name, $step_children)) {
      unset($form['#groups'][$name]);
      unset($form['#fieldgroups'][$name]);
    }
  }
  $old_form = $form;
  foreach (element_children($form) as $key) {
    // Preserve special items.
    if (in_array($key, array('ctools_trail', 'buttons'))) {
      continue;
    }

    if (!in_array($key, $step_children)) {
      unset($form[$key]);
    }
    else if(module_exists('flexiform_conditional_fields')) {
      $el_settings = $form[$key]['#flexiform_element']->getSettings();
      if (empty($el_settings['conditional_fields'])) {
        continue;
      }

      foreach ($el_settings['conditional_fields'] as $dependency_key => $options) {
        list($dependee) = explode('|', $dependency_key);
        if (in_array($dependee, $step_children)) {
          // If the dependee is also on this page then we don't need to do
          // anything.
          continue;
        }
        $condition_met = FALSE;
        $dependee_element = $old_form[$dependee];
        if (!empty($dependee_element[$dependee_element['#language']])) {
          $dependee_element = $dependee_element[$dependee_element['#language']];
        }

        $values = array();
        if (element_children($dependee_element)) {
        }
        else {
          $values = $dependee_element['#default_value'];
          if (!is_array($values)) {
            $values = array(
              $dependee_element['#delta'] => $values,
            );
          }
          foreach ($values as $delta => $value) {
            if (!is_array($value)) {
              $values[$delta] = array(
                $dependee_element['#value_key'] => $value,
              );
            }
          }
        }

        switch ($options['condition']) {
          case '!empty':
            $condition_met = !empty($values);
            break;
          case 'empty':
            $condition_met = empty($values);
            break;
          case 'focused':
            break;
          case '!focused':
            $condition_met = TRUE;
            break;
          case 'checked':
            $condition_met = ($dependee_element['#type'] == 'checkbox' && $dependee_element['#default_value'] == 1);
            break;
          case '!checked':
            $condition_met = ($dependee_element['#type'] == 'checkbox' && !$dependee_element['#default_value'] == 1);
            break;
          case 'value':
            if ($values == $options['value']) {
              $condition_met = TRUE;
            }
            break;
        }

        if ($condition_met) {
          switch ($options['state']) {
            case 'visible':
              break;
            case '!visible':
              $form[$key]['#access'] = FALSE;
              break;
            case '!disabled':
              $form[$key]['#disabled'] = FALSE;
              break;
            case 'disabled':
              $form[$key]['#disabled'] = TRUE;
              break;
            case '!required':
              $form[$key]['#required'] = TRUE;
              break;
            case 'required':
              $form[$key]['#required'] = FALSE;
              break;
          }
        }
        else {
          switch ($options['state']) {
            case 'visible':
              $form[$key]['#access'] = FALSE;
              break;
            case '!visible':
              break;
            case '!disabled':
              $form[$key]['#disabled'] = TRUE;
              break;
            case 'disabled':
              $form[$key]['#disabled'] = FALSE;
              break;
            case '!required':
              $form[$key]['#required'] = TRUE;
              break;
            case 'required':
              $form[$key]['#required'] = FALSE;
              break;
          }
        }
      }
    }
  }

  return $form;
}

/**
 * Form submission handler for flexiform_multistep_step_form().
 */
function flexiform_multistep_step_form_submit(&$form, &$form_state) {
  /** @var Flexiform $flexiform */
  $flexiform = $form['#flexiform'];

  /** @var FlexiformBuilderInterface $builder */
  $builder = $form['#flexiform_builder'];
  $form_settings = isset($flexiform->settings['flexiform_multistep']) ? $flexiform->settings['flexiform_multistep'] : array();
  $step_settings = isset($form_state['step_group']->format_settings['instance_settings']) ? $form_state['step_group']->format_settings['instance_settings'] : array();

  // Tweak our flexiform state so we can skip saving particular items.
  $flexiform_state = &$builder->getFlexiformState($form, $form_state);
  $entity_manager = $builder->getEntityManager($flexiform_state);
  $entities = array_keys($flexiform->entities);
  $save_entities = array();

  // If we are using the default and we're not saving on each step we can take a
  // simple approach.
  if (empty($step_settings['save_entities']) && empty($form_settings['save_entities'])) {
    // Save everything on the last step, nothing otherwise.
    if (!empty($form_state['last_step'])) {
      $save_entities = $entities;
    }
  }
  // If we've got a manual list, copy that over.
  elseif ($step_settings['save_entities'] == 'c') {
    $save_entities = array_filter($step_settings['save_entities_custom']);
  }
  // Any case other than not saving anything, we now pull the entities based on
  // the elements that have been shown on this step.
  elseif ($step_settings['save_entities'] != 'n') {
    foreach (element_children($form) as $element) {
      if (!empty($form[$element]['#flexiform_element'])) {
        $namespace = $form[$element]['#flexiform_element']->getEntityNamespace();
        $save_entities[$namespace] = $namespace;
      }
    }
  }

  // If not saving everything, remove items we don't want.
  foreach (array_diff($entities, $save_entities) as $namespace) {
    $entity_manager->skipOnSave($namespace, TRUE);
  }

  $builder->formSubmit($form, $form_state);

  $object = $form_state['object'];
  $object['flexiform_state'] = $form_state['flexiform_state'];
  $object['base_entity'] = $form['#flexiform_base_entity'];
  ctools_object_cache_set('flexiform_multistep', $form_state['object_id'], $object);
}

/**
 * Form submission handler for flexiform_multistep_step_form().
 */
function flexiform_multistep_step_form_submit_redirect(&$form, &$form_state) {
  // Deal with the completion redirect.
  $form_settings = isset($form['#flexiform']->settings['flexiform_multistep']) ? $form['#flexiform']->settings['flexiform_multistep'] : array();

  if (!empty($form_settings['redirect'])) {
    $builder = $form['#flexiform_builder'];

    // See if we can skip the weight of substitutions.
    if (strpos($form_settings['redirect'], '%') === FALSE) {
      $form_state['redirect'] = $form_settings['redirect'];
    }
    // Otherwise build the tokens an replace.
    else {
      $flexiform_state = &$builder->getFlexiformState($form, $form_state);
      $entity_manager = $builder->getEntityManager($flexiform_state);
      $replacements = array();
      foreach ($entity_manager->getEntities() AS $namespace => $entity) {
        if ($entity) {
          $wrapper = entity_metadata_wrapper($entity_manager->getEntityType($namespace), $entity);
          $replacements['%' . $namespace] = $wrapper->getIdentifier();
        }
      }
      $form_state['redirect'] = strtr($form_settings['redirect'], $replacements);
    }
  }
}

/**
 * Get all descendants of a field group.
 *
 * @param array $groups
 *   An array of group objects keyed by machine name, e.g. $form['#groups'].
 * @param $group
 *   Either the machine name or a group object we want the descendants of.
 *
 * @return array
 *   An array of element names that are descendants of $group.
 */
function flexiform_multistep_field_group_descendants($groups, $group) {
  if (!is_object($group)) {
    $group = $groups[$group];
  }

  // Get the children from this group.
  $children = $group->children;

  // See if there are any groups as children and, if so, recurse.
  foreach ($group->children as $child) {
    if (isset($groups[$child])) {
      $children = array_merge($children, flexiform_multistep_field_group_descendants($groups, $child));
    }
  }

  return $children;
}
