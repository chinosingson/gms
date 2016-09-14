<?php
/**
 * @file
 * Define Display plugin for the add page.
 */

/**
 * Class for add page displays.
 */
class FlexiformDisplayCreateModal extends FlexiformDisplayPageBase {

  public function hook_menu() {
    $items = array();

    $path = 'flexiform/'.$this->getFlexiform()->form.'/%ctools_js';
    $items[$path] = array(
      'title callback' => 'flexiform_page_title',
      'title arguments' => $this->getMenuArguments(),
      'page callback' => 'flexiform_modal_page_wrapper',
      'page arguments' => $this->getMenuArguments(),
      'access callback' => 'flexiform_page_access',
      'access arguments' => $this->getMenuArguments(),
      'theme callback' => 'ajax_base_page_theme',
      'file' => 'flexiform.pages.inc',
    );

    // Add the access settings.
    if (!empty($this->configuration['access']['permission'])) {
      $items[$path]['access callback'] = 'user_access';
      $items[$path]['access arguments'] = array($this->configuration['access']['permission']);
    }

    return $items;
  }

  /**
   * Get the page/title arguments for hook_menu.
   *
   * @see FlexiformDisplayPageBase::hook_menu()
   */
  protected function getMenuArguments() {
    return array(
      $this->getFlexiform()->form,
      get_class($this),
      2,
      NULL,
    );
  }

  /**
   * Get config form.
   */
  public function configForm($form, &$form_state) {
    $form = parent::configForm($form, $form_state);

    unset($form['path']);
    unset($form['type']);
    unset($form['menu_name']);
    unset($form['weight']);

    $form['ajax_behavior'] = array(
      '#title' => t('Submit Behavior'),
      '#description' => t('Default behavior when the form is submitted. Note that if a destination or redirect is set by the form code then this will take precedant.'),
      '#type' => 'select',
      '#default_value' => !empty($this->configuration['ajax_behavior']) ? $this->configuration['ajax_behavior'] : 'reload',
      '#options' => array(
        'modal_dismiss' => t('Close Modal'),
        'reload' => t('Reload'),
      ),
    );

    return $form;
  }

  /**
   * Get the title.
   */
  public function title($context = array()) {
    return $this->configuration['title'];
  }

  /**
   * Build the form ready for rendering.
   */
  public function build($context = array()) {
    if (empty($context['js'])) {
      return parent::build($context);
    }

    // Get the base entity ready.
    $base_entity = $this->getBaseEntity($context);
    module_invoke_all('flexiform_prepare_base_entity', $base_entity, $this->flexiform, $this);

    ctools_include('ajax');
    ctools_include('modal');

    // Prepare form id and form args.
    $form_id = "flexiform__{$this->flexiform->form}";
    $args = isset($context['args']) ? $context['args'] : array();
    array_unshift($args, $base_entity);
    array_unshift($args, $this->flexiform);

    // Setup form state.
    $form_state = array(
      'ajax' => TRUE,
      'build_info' => array(
        'args' => $args,
      ),
    );

    $commands = ctools_modal_form_wrapper($form_id, $form_state);

    if (!empty($form_state['executed']) && empty($form_state['rebuild'])) {
      // The form has been executed, so let's redirect to the destination page.
      $commands = array();
      if (!empty($_GET['destination'])) {
        $commands[] = ctools_ajax_command_redirect($_GET['destination']);
      }
      elseif (!empty($form_state['redirect'])) {
        $commands[] = ctools_ajax_command_redirect($form_state['redirect']);
      }
      elseif ($this->configuration['ajax_behavior'] == 'modal_dismiss') {
        $commands[] = ctools_modal_command_dismiss();
      }
      else {
        $commands[] = ctools_ajax_command_reload();
      }
    }

    drupal_alter('flexiform_modal_submit_commands', $commands, $flexiform, $form_state);
    ajax_deliver(array(
      '#type' => 'ajax',
      '#commands' => $commands,
    ));
    drupal_exit();
  }
}
