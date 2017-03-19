<?php
	global $base_url;
/**
 * @file
 * This template handles the layout of the views exposed filter form.
 *
 * Variables available:
 * - $widgets: An array of exposed form widgets. Each widget contains:
 * - $widget->label: The visible label to print. May be optional.
 * - $widget->operator: The operator for the widget. May be optional.
 * - $widget->widget: The widget itself.
 * - $sort_by: The select box to sort the view using an exposed form.
 * - $sort_order: The select box with the ASC, DESC options to define order. May be optional.
 * - $items_per_page: The select box with the available items per page. May be optional.
 * - $offset: A textfield to define the offset of the view. May be optional.
 * - $reset_button: A button to reset the exposed filter applied. May be optional.
 * - $button: The submit button for the form.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($q)): ?>
  <?php
    // This ensures that, if clean URLs are off, the 'q' is added first so that
    // it shows up first in the URL.
    print $q;
  ?>
<?php endif; ?>
<div id="main-map-sidebar">
	<ul id="main-map-sidebar-tab-group" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="main-map-sidebar-tab active"><a href="#filters" aria-controls="home" role="tab" data-toggle="tab">Filter</a></li>
		<li role="presentation" class="main-map-sidebar-tab"><a href="#legend" aria-controls="profile" role="tab" data-toggle="tab">Legend</a></li>
	</ul>
	<!-- Tab panes -->
  <div id="main-map-sidebar-tab-content" class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="filters">
			<div id="filter-description">To select multiple filter categories, hold CTRL (or Command) key and click.</div>
			<div class="views-exposed-form">
				<div class="views-exposed-widgets clearfix panel-group" id="accordion-filters" role="tabllist" aria-multiselectable="true">
					<?php foreach ($widgets as $id => $widget): ?>
						<div id="<?php print $widget->id; ?>-wrapper" class="panel panel-default views-exposed-widget views-widget-<?php print $widget->id; ?>">
							<?php if (!empty($widget->label)): ?>
								<label class="panel-heading" role="tab"  id="heading-<?php print $widget->id; ?>" for="<?php print $widget->id; ?>">
									<a class="heading-text collapsed" role="button" data-toggle="collapse" data-parent="#accordion-filters" href="#collapse-<?php print $widget->id; ?>" aria-expanded="false" aria-controls="collapse-<?php print $widget->id; ?>">
									<?php print $widget->label; ?>
									<span id="toggle-filter-<?php print $widget->id; ?>" class="heading-arrow glyphicon glyphicon-chevron-down pull-right"></span></a>
								</label>
							<?php endif; ?>
							<div id="collapse-<?php print $widget->id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php print $widget->id; ?>">
								<div class="panel-body">
							<?php if (!empty($widget->operator)): ?>
								<div class="views-operator">
									<?php print $widget->operator; ?>
								</div>
							<?php endif; ?>
							<div class="views-widget">
								<?php print $widget->widget; ?>
							</div>
							<?php if (!empty($widget->description)): ?>
								<div class="description">
									<?php print $widget->description; ?>
								</div>
							<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
					<?php if (!empty($sort_by)): ?>
						<div class="views-exposed-widget views-widget-sort-by">
							<?php print $sort_by; ?>
						</div>
						<div class="views-exposed-widget views-widget-sort-order">
							<?php print $sort_order; ?>
						</div>
					<?php endif; ?>
					<?php if (!empty($items_per_page)): ?>
						<div class="views-exposed-widget views-widget-per-page">
							<?php print $items_per_page; ?>
						</div>
					<?php endif; ?>
					<?php if (!empty($offset)): ?>
						<div class="views-exposed-widget views-widget-offset">
							<?php print $offset; ?>
						</div>
					<?php endif; ?>
					<div class="views-exposed-widget views-submit-button">
						<?php print $button; ?>
					</div>
					<?php if (!empty($reset_button)): ?>
						<div class="views-exposed-widget views-reset-button">
							<?php print $reset_button; ?>
						</div>
					<?php endif; ?>
					<div id="filter-summary">
						<div id="filter-summary-heading">No filters selected</div>
						<div id="filter-summary-items"></div>
					</div>
				</div>
			</div>
		</div>
    <div role="tabpanel" class="tab-pane" id="legend">
			<div class="panel-group" id="accordion-legend" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default">
					<label class="panel-heading" role="tab" id="heading-markers">
						<a class="heading-text" role="button" id="toggle-legend-text-markers" data-toggle="collapse" data-parent="#accordion-legend" href="#collapse-markers" aria-expanded="false" aria-controls="collapse-markers">
							Map Markers<span id="toggle-legend-markers" class="heading-arrow glyphicon glyphicon-chevron-down pull-right"></span>
						</a>
					</label>
					<div id="collapse-markers" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-markers">
						<div class="panel-body">
							<div id="marker-legend"><table class="table" id="legend-sectors"><?php
								$sector_taxonomy_vocab = taxonomy_vocabulary_machine_name_load('adb_sector');
								$sector_taxonomy_vocab_id = $sector_taxonomy_vocab->vid;
								//echo $sector_taxonomy_vocab_id."<br/>";
								$sector_taxonomy_tree = taxonomy_get_tree($sector_taxonomy_vocab_id);
								//echo print_r($sector_taxonomy_tree,1)."<br/>";
								//print $base_url;
								foreach ($sector_taxonomy_tree as $term_info){
									$tid = $term_info->tid;
									$term = taxonomy_term_load($tid);
									print "<tr><td class='legend-sector-image'>";
									//print"<pre>".print_r($term,1)."</pre>";
									print "<img src='".$base_url.'/'.$term->field_marker_path[LANGUAGE_NONE][0]['value']."' />";
									print "</td>";
									print "<td class='legend-sector-name'>";
									print $term->name;
									print "</td></tr>";
								}
							?></table></div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<label class="panel-heading" role="tab" id="heading-corridors">
						<a class="heading-text collapsed" id="toggle-legend-text-corridors" role="button" data-toggle="collapse" data-parent="#accordion-legend" href="#collapse-corridors" aria-expanded="false" aria-controls="collapse-corridors">
							Transport &amp; Economic Corridors<span id="toggle-legend-corridors" class="heading-arrow glyphicon glyphicon-chevron-down pull-right"></span>
						</a>
					</label>
					<div id="collapse-corridors" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-corridors">
						<div class="panel-body">
							<table class="table" id="legend-corridors">
							<tbody>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/central.png"></td>
							<td class="legend-corridor-name">Central Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/east-west.png"></td>
							<td class="legend-corridor-name">East-West Economic Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/eastern.png"></td>
							<td class="legend-corridor-name">Eastern Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/eastern_extension.png"></td>
							<td class="legend-corridor-name">North South Economic Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/north-south.png"></td>
							<td class="legend-corridor-name">North South Corridor Extension</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/north-south_extension.png"></td>
							<td class="legend-corridor-name">Northeastern Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/northeastern.png"></td>
							<td class="legend-corridor-name">Northern Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/northern.png"></td>
							<td class="legend-corridor-name">Southern Coastal Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/southern.png"></td>
							<td class="legend-corridor-name">Southern Economic Corridor</td>
							</tr>
							<tr>
							<td class="legend-corridor-image"><img src="<?php print $base_url; ?>/sites/default/files/images/markers/tec/western.png"></td>
							<td class="legend-corridor-name">Western Corridor</td>
							</tr>
							</tbody>
							</table>
						</div>
					</div>
				</div>
				<div id="corridor-toggle"><a href="#" class="btn btn-primary" data-toggle="button" aria-pressed="false" id="kmz-toggle">Corridors OFF</a></div>
			</div>
		</div>
  </div>
</div>