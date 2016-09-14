<?php
/**
 * @file
 *  Contains class for a basic entity getter.
 */

/**
 * Form Entity Class for getting new commerce line items.
 */
class FlexiformFormEntityNewCommerceLineItem extends FlexiformFormEntityBase {

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    parent::getEntity();

    // Get the base order
    $order = $this->getParam('base');

    // Check we have enough information to load the entity.
    if (!$order) {
      return FALSE;
    }

    $target_entity = commerce_line_item_new($this->settings['bundle'], $order->order_id);
    return $this->checkBundle($target_entity) ? $target_entity : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function saveEntity($entity) {
    // If the entity is still false do not save it!
    if ($entity === FALSE) {
      return;
    }

    $order = $this->getParam('base');
    if (empty($entity->order_id)) {
      if (empty($order->order_id)) {
        commerce_order_save($order);
      }

      $entity->order_id = $order->order_id;
    }

    // Make sure commerce_unit_price has components.
    if (!empty($entity->commerce_unit_price[LANGUAGE_NONE][0]) && empty($entity->commerce_unit_price[LANGUAGE_NONE][0]['data']['components'])) {
      $price = &$entity->commerce_unit_price[LANGUAGE_NONE][0];
      $price['data'] = commerce_price_component_add($price, 'base_price', $price, TRUE);
    }

    commerce_line_item_save($entity);
    $order->commerce_line_items[LANGUAGE_NONE][] = array(
      'line_item_id' => $entity->line_item_id,
    );
    commerce_order_save($order);
  }
}
