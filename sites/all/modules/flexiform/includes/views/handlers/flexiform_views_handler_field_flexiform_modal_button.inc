<?php
/**
 * @file
 * Contains flexiform_views_handler_field_flexiform_modal_button
 */

class flexiform_views_handler_field_flexiform_modal_button extends views_handler_field {
  function construct() {
    parent::construct();

    $this->flexiform = entity_load_single('flexiform', $this->definition['flexiform']);

    $entity_info = entity_get_info($this->flexiform->base_entity);
    $this->additional_fields[$entity_info['entity keys']['id']] = $entity_info['entity keys']['id'];
  }

  function option_definition() {
    $options = parent::option_definition();
    $options['button_class'] = array('default' => 'button');
    $options['button_text'] = array('default' => '');
    return $options;
  }

  function options_form(&$form, &$form_state) {
    $form['button_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Button Text'),
      '#description' => t('The text to display on the button.'),
      '#default_value' => $this->options['button_text'],
    );

    $form['button_class'] = array(
      '#type' => 'textfield',
      '#title' => t('Button Class'),
      '#description' => t('CSS classes to apply to the button.'),
      '#default_value' => $this->options['button_class'],
    );

    parent::options_form($form, $form_state);
  }

  function query() {
    $this->ensure_my_table();
    $this->add_additional_fields();
  }

  function render($values) {
    $entity_info = entity_get_info($this->flexiform->base_entity);
    $id = $this->get_value($values, $entity_info['entity keys']['id']);

    $tokens = $this->get_render_tokens(array());
    $title = strtr($this->options['button_text'], $tokens);
    $class = strtr($this->options['button_class'], $tokens);

    if (empty($title)) {
      $title = $this->flexiform->getDisplay('FlexiformDisplayEditModal')->title();
    }

    ctools_include('ajax');
    ctools_include('modal');

    $path = "flexiform/{$this->flexiform->form}/ajax/".$id;
    $content = '';

    if (drupal_valid_path($path)) {
      $content = ctools_modal_text_button($title, url($path, array('absolute' => TRUE)), $title, $class);
    }

    return $content;
  }
}
