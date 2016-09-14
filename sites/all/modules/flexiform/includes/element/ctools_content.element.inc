<?php

/**
 * @file
 * Contains class for the Custom HTML form element.
 */
class FlexiformElementCtoolsContent extends FlexiformRepeatableElement {

  /**
   * Returns the custom html that is defined in the configure form.
   */
  public function form($form, &$form_state, $entity) {
    ctools_include('content');

    $parents = $form['#parents'];
    $parents[] = 'ctools_content';

    $contexts = $this->getCtoolsContexts($form['#flexiform_entities']);
    $type = $this->element_info['content_type'];
    $subtype = $this->element_info['sub_type'];
    $conf = !empty($this->settings['conf']) ? $this->settings['conf'] : array();
    $plugin_info = ctools_content_get_subtype($type, $subtype);

    if (!empty($plugin_info['required context'])) {
      if (is_array($plugin_info['required context'])) {
        $conf['context'] = array($this->entity_namespace);
      }
      else {
        $conf['context'] = $this->entity_namespace;
      }
    }

    $content = ctools_content_render($type, $subtype, $conf, array(), array(), $contexts)->content;
    if (is_array($content)) {
      $content = drupal_render($content);
    }

    $form[$this->element_namespace] = array(
      '#type' => 'item',
      '#support_flexiform_conditional_fields' => TRUE,
      '#markup' => $content,
      '#parents' => $parents,
    );

    $form = parent::form($form, $form_state, $entity);

    return $form;
  }

  /**
   * Builds the configuration form for the form element.
   */
  public function configureForm($form, &$form_state, $flexiform) {
    ctools_include('content');

    $type = $this->element_info['content_type'];
    $subtype = $this->element_info['sub_type'];
    $conf = !empty($this->settings['conf']) ? $this->settings['conf'] : array();
    $plugin_info = ctools_get_content_type($type);
    $subtype_info = ctools_content_get_subtype($type, $subtype);
    $config_form_callback = $plugin_info['edit form'];
    if (!empty($subtype_info['edit form'])) {
      $config_form_callback = $subtype_info['edit form'];
    }

    if (is_array($config_form_callback)) {
      $config_form_callback = reset($config_form_callback);
    }

    $form_state['plugin'] = ctools_get_content_type($type);
    $form_state['subtype'] = $plugin_info;
    $form_state['conf'] = $conf;
    $form_state['subtype_name'] = $subtype;
    $form_state['op'] = 'edit';
    $form['conf'] = array(
      '#type' => 'container',
      '#tree' => TRUE,
    );
    $form['conf'] = $config_form_callback($form['conf'], $form_state);
    $form = parent::configureForm($form, $form_state, $flexiform);

    return $form;
  }

  /**
   * Validate the form element.
   */
  public function configureFormValidate($form, &$form_state, $flexiform) {
    parent::configureFormValidate($form, $form_state, $flexiform);
  }

  /**
   * Submit the form element.
   */
  public function configureFormSubmit($form, &$form_state, $flexiform) {
    $this->settings['conf'] = $form_state['values']['conf'];
    parent::configureFormSubmit($form, $form_state, $flexiform);
  }
}
