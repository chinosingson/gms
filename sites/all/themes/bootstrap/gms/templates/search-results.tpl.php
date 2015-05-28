<?php

/**
 * @file
 * Default theme implementation for displaying search results.
 *
 * This template collects each invocation of theme_search_result(). This and
 * the child template are dependent to one another sharing the markup for
 * definition lists.
 *
 * Note that modules may implement their own search type and theme function
 * completely bypassing this template.
 *
 * Available variables:
 * - $search_results: All results as it is rendered through
 *   search-result.tpl.php
 * - $module: The machine-readable name of the module (tab) being searched, such
 *   as "node" or "user".
 *
 *
 * @see template_preprocess_search_results()
 *
 * @ingroup themeable
 */
 //echo "<pre>".print_r($variables,1)."</pre>";
 //$show_title = $variables['title'];
 //echo "TITLE: ".$title;
?>
<div id="search-page-totals"><?php print $search_totals ?></div>
<div id="search-page-list">
	<div id="search-results-container">
<?php if ($search_results): ?>
  <ul class="search-results <?php print $module; ?>-results">
    <?php print $search_results; ?>
  </ul>
  <?php print $pager; ?>
<?php else : ?>
  <h4 id="search-no-results-header"><?php print t('Your search yielded no results');?></h4>
	<div id="search-no-results-help">
  <?php print search_help('search#noresults', drupal_help_arg()); ?>
	</div>
<?php endif; ?>
	</div>
</div>
