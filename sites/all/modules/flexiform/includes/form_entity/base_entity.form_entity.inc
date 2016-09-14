<?php
/**
 * @file
 *  Contains class for a basic entity getter.
 */

/**
 * Form Entity Class for Managing a forms Base Entity.
 */
class FlexiformFormEntityBaseEntity extends FlexiformFormEntityBase {

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    parent::getEntity();
    return $this->manager->getBaseEntity();
  }
}
