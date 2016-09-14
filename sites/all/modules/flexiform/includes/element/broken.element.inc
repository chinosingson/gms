<?php
/**
 * @file
 * Contains class for the broken element.
 */

/**
 * Class to handle broken elements on a form.
 */
class FlexiformElementBroken extends FlexiformElement {

  /**
   * {@inheritdoc}
   */
  public function type() {
    return t('Broken/Missing Handler');
  }

  /**
   * Return the form element for this FlexiformElement.
   */
  public function form($form, &$form_state, $entity, $language = LANGUAGE_NONE) {
    return $form;
  }

  /**
   * Validate the form element.
   */
  public function formValidate($form, &$form_state, $entity, $language = LANGUAGE_NONE) {
  }

  /**
   * Submit the form element.
   */
  public function formSubmit($form, &$form_state, $entity, $language = LANGUAGE_NONE) {
  }

  /**
   * Extract the submitted values for this form element.
   */
  public function formExtractValues($form, &$form_state, $entity) {
    return FALSE;
  }
}
