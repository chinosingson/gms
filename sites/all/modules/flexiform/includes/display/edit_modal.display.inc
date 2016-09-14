<?php
/**
 * @file
 * Define Display plugin for the add page.
 */

/**
 * Class for add page displays.
 */
class FlexiformDisplayEditModal extends FlexiformDisplayCreateModal {

  /**
   * {@inheritdoc}
   */
  public function hook_menu() {
    $items = parent::hook_menu();

    $items['flexiform/'.$this->getFlexiform()->form.'/%ctools_js/%']
      = $items['flexiform/'.$this->getFlexiform()->form.'/%ctools_js'];

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  protected function getMenuArguments() {
    $args = parent::getMenuArguments();
    $args[3] = 3;
    return $args;
  }

  /**
   * {@inheritdoc}
   */
  public function getBaseEntity($context = array()) {
    $base_entity = entity_load_single($this->getFlexiform()->base_entity, $context['base_entity_id']);

    //@todo: Check the entity is the right bundle.
    return $base_entity;
  }
}
