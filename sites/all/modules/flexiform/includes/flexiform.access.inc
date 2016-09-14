<?php
/**
 * @file
 * Contains FlexiformAccess
 */

/**
 * Control access conditions for Flexiforms.
 */
class FlexiformAccess {

  protected $settings = array();
  protected $conditions = array();
  protected $flexiform = NULL;
  protected $display = '';

  /**
   * Constructor for the flexiform access handlers.
   */
  public function __construct($flexiform, $display = '') {
    $this->flexiform = $flexiform;
    $this->display = $display;

    $this->settings = !empty($flexiform->access['settings']) ? $flexiform->access['settings'] : array();
    $this->conditions = !empty($flexiform->access['conditions']) ? $flexiform->access['conditions'] : array();

    // @todo: Allow display level overrides of settings.
  }

  /**
   * Prepare contexts.
   */
  public function prepareContexts($base_entity = NULL) {
    ctools_include('context');

    $contexts = array();

    // Build the base entity context.
    if (empty($base_entity)) {
      $context = ctools_context_create_empty('entity:'.$this->flexiform->base_entity);
    }
    else {
      $context = ctools_context_create('entity:'.$this->flexiform->base_entity, $base_entity);
    }
    $contexts['base_entity'] = $context;
    $contexts['base_entity']->identifier = t('Base Entity');
    return $contexts;
  }

  /**
   * Check the Acces for this flexiforms.
   */
  public function checkAccess($base_entity = NULL) {
    $contexts = $this->prepareContexts($base_entity);
    $ctools_settings = $this->getCtoolsSettings();
    $access = ctools_access($ctools_settings, $contexts);
    return $access;
  }

  public function getCtoolsSettings() {
    return array(
      'logic' => !empty($this->settings['logic']) ? $this->settings['logic'] : 'and',
      'plugins' => $this->conditions,
    );
  }

  public function setCtoolsSettings($ctools_settings = array(), $save = TRUE) {
    $this->settings['logic'] = $ctools_settings['logic'];
    $this->conditions = $ctools_settings['plugins'];

    // Write back to flexiform.
    $this->flexiform->access['settings'] = $this->settings;
    $this->flexiform->access['conditions'] = $this->conditions;

    if ($save) {
      $this->flexiform->save();
    }
  }
}

