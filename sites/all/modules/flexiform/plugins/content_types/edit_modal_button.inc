<?php

/**
 * @file
 * Plugin to handle attached entity content types
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('Flexiform Edit Pane'),
  'defaults' => array(),
  'content type' => 'flexiform_edit_modal_button_content_type_content_type',
);

/**
 * Get one sub type of this flexiform edit pane.
 */
function flexiform_edit_modal_button_content_type_content_type($subtype) {
  $types = flexiform_edit_modal_button_content_type_content_types();
  if (isset($types[$subtype])) {
    return $types[$subtype];
  }
}

/**
 * Return all flexiform edit pane sub types
 */
function flexiform_edit_modal_button_content_type_content_types() {
  $types = &drupal_static(__FUNCTION__, array());
  if (!empty($types)) {
    return $types;
  }

  // This will hold all the individual field content types.
  $forms = entity_load('flexiform');

  foreach ($forms as $form) {
    if (!($display = $form->getDisplay('FlexiformDisplayEditModal')) || !$display->isEnabled()) {
      continue;
    }

    $types[$form->form] = array(
      'category' => t('Flexiforms'),
      'title' => t('Flexiform Modal Button: @form_label (@form)', array(
        '@form_label' => $form->label,
        '@form' => $form->form,
      )),
      'required context' => new ctools_context_required(t('Base Entity'), $form->base_entity),
      'description' => t('Flexiform form modal button.'),
      'edit form' => 'flexiform_edit_modal_button_content_type_options',
    );
  }

  return $types;
}

/**
 * Options form for the pane.
 */
function flexiform_edit_modal_button_content_type_options($form, &$form_state) {
  $form['button_class'] = array(
    '#type' => 'textfield',
    '#title' => t('Button Classes'),
    '#description' => t('Enter classes required on the button. Seperate classes with a space.'),
    '#default_value' => (!empty($form_state['conf']['button_class'])) ? $form_state['conf']['button_class'] : 'button',
  );

  return $form;
}

/**
 * Options form submit.
 */
function flexiform_edit_modal_button_content_type_options_submit($form, &$form_state) {
  $form_state['conf']['button_class'] = $form_state['values']['button_class'];
}

/**
 * Returns the administrative title for a type.
 */
function flexiform_edit_modal_button_content_type_admin_title($subtype, $conf, $context) {
  $flexiform = entity_load_single('flexiform', $subtype);
  return t('Flexiform Modal Button: @form', array('@form' => $flexiform->label));
}

/**
 * Render the custom content type.
 */
function flexiform_edit_modal_button_content_type_render($subtype, $conf, $panel_args, $context) {
  if (empty($context) || empty($context->data)) {
    return;
  }

  $flexiform = entity_load_single('flexiform', $subtype);
  $wrapper = entity_metadata_wrapper($flexiform->base_entity, $context->data);
  $title = $flexiform->getDisplay('FlexiformDisplayEditModal')->title();

  ctools_include('ajax');
  ctools_include('modal');

  ctools_modal_add_js();

  $path = "flexiform/{$subtype}/ajax/".$wrapper->getIdentifier();
  $content = '';

  if (drupal_valid_path($path)) {
    $content = ctools_modal_text_button($title, url($path, array('absolute' => TRUE)), $title, $conf['button_class']);
  }

  // Build the content type block.
  $block = new stdClass();
  $block->module  = 'flexiform';
  $block->title = '';
  $block->content = $content;
  $block->delta   = $subtype;

  return $block;
}
