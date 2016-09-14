<?php
/**
 * @file
 * Contains views integration for tags.
 */

/**
 * Implements hook_views_data_alter().
 */
function flexiform_views_data_alter(&$data) {

  // Add an add URL field.
  $data['flexiform']['add_page_url'] = array(
    'title' => t('Add Page URI'),
    'help' => t('Path to the Add Page.'),
    'field' => array(
      'real field' => 'id',
      'handler' => 'flexiform_handler_field_add_page_url',
      'click sortable' => FALSE,
    ),
  );

  // Add Tags Field
  $data['flexiform']['tags'] = array(
    'title' => t('Tags'),
    'help' => t('Flexible form tags.'),
    'field' => array(
      'real field' => 'id',
      'handler' => 'flexiform_handler_field_tags',
      'click sortable' => FALSE,
    ),
  );

  $data['flexiform_tags'] = array(
    'table' => array(
      'join' => array(
        'flexiform' => array(
          'left_field' => 'id',
          'field' => 'id',
        ),
      ),
    ),
    'tag' => array(
      'group' => 'Flexiform',
      'title' => 'Tags',
      'title short' => 'Tags',
      'help' => 'Flexiform Tags',
      'filter' => array(
        'field' => 'tag',
        'table' => 'flexiform_tags',
        'handler' => 'flexiform_handler_filter_tags',
        'allow empty' => TRUE,
      ),
    ),
  );

  // Add fields for the modal buttons.
  $entity_info = entity_get_info();
  foreach (entity_load('flexiform') as $flexiform) {
    $display = $flexiform->getDisplay('FlexiformDisplayEditModal');
    if (!$display->isEnabled()) {
      continue;
    }

    if (empty($data[$entity_info[$flexiform->base_entity]['base table']])) {
      continue;
    }

    $table = &$data[$entity_info[$flexiform->base_entity]['base table']];
    $table['flexiform__'.$flexiform->form] = array(
      'field' => array(
        'title' => t(
          '!flexiform modal button',
          array(
            '!flexiform' => $flexiform->label,
          )
        ),
        'help' => t('Show a button that opens the flexiform in a modal window.'),
        'flexiform' => $flexiform->form,
        'handler' => 'flexiform_views_handler_field_flexiform_modal_button',
      ),
    );
  }
}
