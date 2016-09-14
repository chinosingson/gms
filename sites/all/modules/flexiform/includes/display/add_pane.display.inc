<?php
/**
 * @file
 * Define Display plugin for the add panels pane.
 */

/**
 * Class for add panel panes.
 */
class FlexiformDisplayAddPane extends FlexiformDisplayBase {

  /**
   * {@inheritdoc}
   */
  public function configForm($form, &$form_state) {
    $form = parent::configForm($form, $form_state);

    $form['access'] = array(
      '#type' => 'fieldset',
      '#title' => t('Access'),
      '#tree' => TRUE,
      '#description' => t('Advanced Access Settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    $form['access']['bypass'] = array(
      '#type' => 'checkbox',
      '#title' => t('By-pass Flexiform Access'),
      '#description' => t('By-pass Flexiform Access settings. This can be useful if you would prefer to use panels visibility rules to control access to this form.'),
      '#default_value' => !empty($this->configuration['access']['bypass']),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function access($context = array()) {
    return !empty($this->configuration['access']['bypass']) || parent::access($context);
  }

}
