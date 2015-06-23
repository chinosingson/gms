		<div class="print-content container-fluid">123456
			<div class="col-md-6">
				<h3>Portfolio Indicators</h3>
				<div class="print-chart row"><h4>Projects Per Country</h4><?php print views_embed_view('projects_per_country','block_1'); ?></div>
				<div class="print-chart row"><h4>Projects Per Country</h4><?php print views_embed_view('projects_per_country','block_3'); ?></div>
				<div class="print-chart row"><h4>Projects Per Type</h4><?php print views_embed_view('projects_per_type','block_1'); ?></div>
				<div class="print-chart row"><h4>Projects Per Sector</h4><?php print views_embed_view('projects_per_sector','block_1'); ?></div>
			</div>
			<div class="col-md-6">
				<h3>Outcomes</h3>
			<?php $block = module_invoke('block', 'block_view', '2');
					print render($block['content']);
				?>	
			</div>
		</div>
