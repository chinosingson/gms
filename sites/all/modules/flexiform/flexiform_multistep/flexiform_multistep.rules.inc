<?php
/**
 * @file
 * Rules hooks for multistep.
 */

/**
 * Implements hook_rules_event_info_alter().
 */
function flexiform_multistep_rules_event_info_alter(&$events) {
  foreach ($events as $name => &$event) {
    if (substr($name, 0, 9) == 'flexiform' && !empty($event['variables']['base_entity'])) {
      $event['variables']['flexiform_step'] = array(
        'type' => 'text',
        'label' => t('Step'),
        'description' => t('The step that has been submitted.'),
        'optional' => TRUE,
      );
    }
  }
}
