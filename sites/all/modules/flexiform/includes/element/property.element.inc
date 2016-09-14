<?php
/**
 * @file
 * Contains class for the entity property elements.
 */

/**
 * Class to add an element for entity properties.
 */
class FlexiformElementEntityProperty extends FlexiformElement {

  /**
   * @var string
   * Property Name.
   */
  protected $property;

  /**
   * @var array $propertyInfo
   * The property info.
   */
  protected $propertyInfo;

  /**
   * @var string $dataType
   * The data type of the info.
   */
  protected $dataType;

  /**
   * @var bool $isList
   * Is this property a list.
   */
  protected $isList = FALSE;

  /**
   * Overrides FlexiformElement::__construct().
   */
  public function __construct($flexiform, $settings, $element_namespace = '') {
    parent::__construct($flexiform, $settings, $element_namespace);
    $this->property = $this->element_info['property'];
    $this->propertyInfo = $this->wrapper()->getPropertyInfo($this->property);
    $this->dataType = $this->propertyInfo['type'];

    if ($list_type = entity_property_list_extract_type($this->dataType)) {
      $this->dataType = $list_type;
      $this->isList = TRUE;
    }
  }

  /**
   * Return the wrapper for the entity.
   */
  public function wrapper($entity = NULL) {
    return entity_metadata_wrapper($this->entity_type, $entity, array('bundle' => $this->bundle));
  }

  /**
   * Return the property wrapper.
   */
  public function property($entity) {
    return entity_metadata_wrapper($this->entity_type, $entity)->get($this->property);
  }

  /**
   * Return the form element for this FlexiformElement.
   */
  public function form($form, &$form_state, $entity, $language = LANGUAGE_NONE) {
    $parents = $form['#parents'];
    $parents[] = $this->property;

    // Get the value.
    $value = $this->wrapper($entity)->get($this->property)->value();

    // If there is no value work out the default.
    if (empty($value)) {
      $value = '';
      if (!empty($this->settings['default_value']['default_value'])) {
        $value = $this->settings['default_value']['default_value'];
      }
      if (!empty($this->settings['default_value']['use_tokens'])) {
        $value = $this->replaceCtoolsSubstitutions($value, $form['#flexiform_entities']);
      }
    }
    else if (entity_get_info($this->dataType)) {
      $value = entity_id($this->dataType, $value);
    }

    $form[$this->element_namespace] = array(
      '#type' => 'textfield',
      '#parents' => $parents,
      '#title' => $this->label(),
      '#required' => TRUE,
      '#default_value' => $value,
      '#maxlength' => 255,
    );

    // If the property specifies an options list then use it.
    if ($options = $this->property($entity)->optionsList()) {
      // Pick the list widget.
      $widget = 'select';
      if (!empty($this->settings['options']['widget'])) {
        $widget = $this->settings['options']['widget'];
      }

      $form[$this->element_namespace]['#type'] = $widget;
      $form[$this->element_namespace]['#options'] = $options;
      unset($form[$this->element_namespace]['#maxlength']);

      if ($this->isList) {
        $form[$this->element_namespace]['#multiple'] = TRUE;
        if ($widget == 'radios') {
          $form[$this->element_namespace]['#type'] = 'checkboxes';
        }
      }
    }

    $form = parent::form($form, $form_state, $entity);

    return $form;
  }

  /**
   * Validate the form element.
   */
  public function formValidate($form, &$form_state, $entity, $language = LANGUAGE_NONE) {
    $value = $this->formExtractValues($form, $form_state, $entity);
    $this->wrapper($entity)->get($this->property)->validate($value);
  }

  /**
   * Submit the form element.
   */
  public function formSubmit($form, &$form_state, $entity, $language = LANGUAGE_NONE) {
    $value = $this->formExtractValues($form, $form_state, $entity);
    $this->wrapper($entity)->get($this->property)->set($value);
  }

  /**
   * Extract the submitted values for this form element.
   */
  public function formExtractValues($form, &$form_state, $entity) {
    $parents = $form['#parents'];
    $parents[] = $this->getEntityNamespace();
    $parents[] = $this->property;

    $value = drupal_array_get_nested_value($form_state['values'], $parents);
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function configureForm($form, &$form_state, $flexiform) {
    $form = parent::configureForm($form, $form_state, $flexiform);

    $form['default_value'] = array(
      '#tree' => TRUE,
      '#type' => 'fieldset',
      '#title' => t('Default Value'),
      '#weight' => -5,
    );

    $form['default_value']['default_value'] = array(
      '#type' => 'textfield',
      '#title' => $this->label() ? $this->label() : t('Default'),
      '#default_value' => !empty($this->settings['default_value']['default_value']) ? $this->settings['default_value']['default_value'] : '',
      '#maxlength' => 255,
    );

    $form['default_value']['use_tokens'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use Tokens in Default Value'),
      '#default_value' => !empty($this->settings['default_value']['use_tokens']),
    );

    $form['default_value']['contexts'] = array(
      '#title' => t('Substitutions'),
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    $form['default_value']['contexts']['contexts'] = $this->getCtoolsSubstitutionsList();

    // Options for handling options.
    if (!empty($this->propertyInfo['options list'])) {
      $form['options'] = array(
        '#tree' => TRUE,
        '#type' => 'fieldset',
        '#title' => t('Value Selection'),
      );

      $form['options']['widget'] = array(
        '#type' => 'select',
        '#title' => t('Widget'),
        '#description' => t('What sort of widget to you want to use?'),
        '#options' => array(
          'select' => t('Select'),
          'radios' => t('Radios/Checkboxes'),
        ),
        '#default_value' => !empty($this->settings['options']['widget']) ? $this->settings['options']['widget'] : 'select',
      );
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function configureFormSubmit($form, &$form_state, $flexiform) {
    $this->settings['default_value']['default_value'] = $form_state['values']['default_value']['default_value'];
    $this->settings['default_value']['use_tokens'] = $form_state['values']['default_value']['use_tokens'];
    $this->settings['options']['widget'] = $form_state['values']['options']['widget'];

    parent::configureFormSubmit($form, $form_state, $flexiform);
  }
}
