<?php
/**
 * @file
 * Contains the flipped table style plugin.
 */

/**
 * Style plugin to render each item as a column in a table.
 *
 * @ingroup views_style_plugins
 */
class views_flipped_table_plugin_style_flipped_table extends views_plugin_style_table {
  function option_definition() {
    $options = parent::option_definition();

    $options['flipped_table_header_first_field'] = array('default' => TRUE);

    $options['column_class_special'] = array('default' => TRUE);

    return $options;
  }

  /**
   * Render the given style.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    $form['flipped_table_header_first_field'] = array(
      '#type' => 'checkbox',
      '#title' => t("Show the first field as the table header"),
      '#default_value' => $this->options['flipped_table_header_first_field'],
      '#description' => t("Outputs the flipped table's row for the first field inside a table header element."),
    );

    $form['column_class_special'] = array(
      '#title' => t('Add striping (odd/even), first/last column classes'),
      '#description' => t('Add css classes to the first and last column, as well as odd/even classes for striping.'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['column_class_special'],
    );
  }
}
