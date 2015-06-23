<?php
/**
 * @file
 * Default theme implementation to display a printer-friendly version page.
 *
 * This file is akin to Drupal's page.tpl.php template. The contents being
 * displayed are all included in the $content variable, while the rest of the
 * template focuses on positioning and theming the other page elements.
 *
 * All the variables available in the page.tpl.php template should also be
 * available in this template. In addition to those, the following variables
 * defined by the print module(s) are available:
 *
 * Arguments to the theme call:
 * - $node: The node object. For node content, this is a normal node object.
 *   For system-generated pages, this contains usually only the title, path
 *   and content elements.
 * - $format: The output format being used ('html' for the Web version, 'mail'
 *   for the send by email, 'pdf' for the PDF version, etc.).
 * - $expand_css: TRUE if the CSS used in the file should be provided as text
 *   instead of a list of @include directives.
 * - $message: The message included in the send by email version with the
 *   text provided by the sender of the email.
 *
 * Variables created in the preprocess stage:
 * - $print_logo: the image tag with the configured logo image.
 * - $content: the rendered HTML of the node content.
 * - $scripts: the HTML used to include the JavaScript files in the page head.
 * - $footer_scripts: the HTML  to include the JavaScript files in the page
 *   footer.
 * - $sourceurl_enabled: TRUE if the source URL infromation should be
 *   displayed.
 * - $url: absolute URL of the original source page.
 * - $source_url: absolute URL of the original source page, either as an alias
 *   or as a system path, as configured by the user.
 * - $cid: comment ID of the node being displayed.
 * - $print_title: the title of the page.
 * - $head: HTML contents of the head tag, provided by drupal_get_html_head().
 * - $robots_meta: meta tag with the configured robots directives.
 * - $css: the syle tags contaning the list of include directives or the full
 *   text of the files for inline CSS use.
 * - $sendtoprinter: depending on configuration, this is the script tag
 *   including the JavaScript to send the page to the printer and to close the
 *   window afterwards.
 *
 * print[--format][--node--content-type[--nodeid]].tpl.php
 *
 * The following suggestions can be used:
 * 1. print--format--node--content-type--nodeid.tpl.php
 * 2. print--format--node--content-type.tpl.php
 * 3. print--format.tpl.php
 * 4. print--node--content-type--nodeid.tpl.php
 * 5. print--node--content-type.tpl.php
 * 6. print.tpl.php
 *
 * Where format is the ouput format being used, content-type is the node's
 * content type and nodeid is the node's identifier (nid).
 *
 * @see print_preprocess_print()
 * @see theme_print_published
 * @see theme_print_breadcrumb
 * @see theme_print_footer
 * @see theme_print_sourceurl
 * @see theme_print_url_list
 * @see page.tpl.php
 * @ingroup print
 */
 
 	//$node = menu_get_object(); 
	$nid = $node->nid;
	// sources
	$printFundingSources = array(
		'adb'=> (!empty($node->field_project_cost_adb) ? $node->field_project_cost_adb['und'][0]['value'] : 0),
		//'adb_jsf_jfpr'=> (!empty($node->field_project_cost_adb_jsf_jfpr) ? $node->field_project_cost_adb_jsf_jfpr['und'][0]['value'] : 0),
		//'adb_tasf'=> (!empty($node->field_project_cost_adb_tasf) ? $node->field_project_cost_adb_tasf['und'][0]['value'] : 0),
		'gov'=> (!empty($node->field_project_cost_government) ? $node->field_project_cost_government['und'][0]['value'] : 0),
		'cof'=> (!empty($node->field_project_cost_cofinancing_) ? $node->field_project_cost_cofinancing_['und'][0]['value'] : 0),
	);
	$total = $node->field_project_cost_total['und'][0]['value'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>">
  <head>
    <?php print $head; ?>
    <base href='<?php print $url ?>' />
    <title><?php print $site_name ?> - <?php print $print_title; ?> (Printable Version)</title>
    <?php print $scripts; ?>
    <?php if (isset($sendtoprinter)) print $sendtoprinter; ?>
    <?php print $robots_meta; ?>
    <?php if (theme_get_setting('toggle_favicon')): ?>
      <link rel='shortcut icon' href='<?php print theme_get_setting('favicon') ?>' type='image/x-icon' />
    <?php endif; ?>
    <?php print $css; ?>
  </head>
  <body classes="print print-page print-node-project print-node-<?php print $nid ?> <?php print $classes ?> gms print-pdf" >
		<?php if (!empty($site_name)): ?>
		<div class="print-site_name"><h3 style="color: #ffffff; background-color: #3b444d; position: absolute; float: right"><?php if ($print_logo): ?><span class="print-logo"><?php print $print_logo; ?></span><?php endif; ?><?php print $site_name; ?></h3></div>
		<?php endif; ?>
		<div id="pdf-body-container" style="margin:2.5em">
		<table id="print-pdf-container" border="0">
			<tr>
				<td colspan="2">
					<p />
					<!--div class="print-breadcrumb"><?php //print theme('print_breadcrumb', array('node' => $node)); ?></div-->
					<h3 class="print-title"><?php print $node->title; ?></h3>
					<div class="print-content"></div>
  				</td>
			</tr>
			<tr class="print-pdf-table-row">
				<td colspan="2" id="print-locations" >
					<h4 class="print-title">Locations</h4>
					<hr class="print-hr" />
					<div id="print-map-container"  style="border: 0px solid green; display: block; width: 1500px">
					<?php 	$view_map_pdf = views_get_view('leaflet_view_test');			// locations
						$view_map_pdf->set_display('block_3');
						$markerPath = "http://gmsprogram.org/dev/".$node->field_adb_sector[LANGUAGE_NONE][0]['taxonomy_term']->field_marker_path[LANGUAGE_NONE][0]['value'];
						if($view_map_pdf) : 
							$iconurl = urlencode($markerPath);
							$coords = trim(str_replace(" ","",strip_tags($view_map_pdf->preview('block_3',array($nid)))));
							if ($coords) :
						?>
					<img id="print-map" src="https://maps.googleapis.com/maps/api/staticmap?size=1000x220&scale=2&zoom=5&markers=icon:<?php print $iconurl ?><?php print $coords ?>">
					<?php endif; endif; ?>
					</div>
				</td>
			</tr>
			<tr class="print-pdf-table-row">
				<td class="pdf-col-1" id="print-pdf-details">
					<div id="print-details">
						<h4 class="print-title" style="">Details</h4>
						<hr class="print-hr" />
						<?php	print views_embed_view('project_details','block',$nid); ?>
					</div>
				</td>
				<td class="pdf-col-2" id="print-pdf-funding">
					<div id="print-funding">
						<h4 class="print-title">Funding</h4>
						<hr class="print-hr" />
						<p>in US$('000)</p>
						<div id="funding-table-container">
							<table id="funding-table" class="table">
								<tr><th>ADB</th><td ><?php print ($printFundingSources['adb']!=0 ? number_format($printFundingSources['adb']) : "-"); ?></td></tr>
								<tr><th>Government</th><td ><?php print ($printFundingSources['gov']!=0 ? number_format($printFundingSources['gov']) : "-"); ?></td></tr>
								<tr><th>Cofinancing</th><td><?php print ($printFundingSources['cof']!=0 ? number_format($printFundingSources['cof']) : "-"); ?></td></tr>
								<tr><th>Total</th><td><?php print number_format($node->field_project_cost_total['und'][0]['value']); ?></td></tr>
							</table>
								<?php if (array_sum($printFundingSources) != 0) : ?>
								<img id="print-chart" src="https://chart.googleapis.com/chart?cht=p&chs=250x130&chd=t:<?php print ($printFundingSources['adb']/$total) ?>,<?php (print $printFundingSources['gov']/$total) ?>,<?php print ($printFundingSources['cof']/$total) ?>&chdl=ADB|Government|Cofinancing&chco=64994B|3CB6A3|188AD2&chdls=000000&chdlp=&chp=-1.5707&chma=0,0,0,0|50,50" />
								<?php endif; ?>
						</div>
						<div id="project-funding-chart" class="chart">
						</div>
					</div>
				</td>
			</tr>
			<tr class="print-pdf-table-row">
				<td id="print-pdf-outputs">
					<div id="print-outputs">
						<h4 class="print-title">Outputs</h4>
						<hr class="print-hr" />
						<div id="print-pdf-outputs-list">
						<?php print views_embed_view('outputs','block',$nid); ?>
						</div>
					</div>
				</td>
				<td id="print-pdf-impact">
					<div id="print-impact-stories">
						<h4 class="print-title">Impact Stories</h4>
						<hr class="print-hr" />
						<div id="print-pdf-impact-list">
						<?php	print views_embed_view('impact_stories','block_1',$nid); ?>
						</div>
					</div>
				</td>
			</tr>
		</table>
			<?php $photos = @$node->field_photos['und']; if (isset($photos)): ?>
		<h4 class="print-title" style="margin-left: -14px">Photos</h4>
		<table border="0" id="print-pdf-photos-table">
				<?php
					//print "PHOTOS";
					foreach ($photos as $photo){
						print "<tr><td colspan='2'><hr class='print-hr'></td></tr>";
						print "<tr class=\"print-pdf-table-row print-image-row\">";
						print "<td class=\"col-sm-6 print-image\"><img class=\"img-responsive\" src=\"".file_create_url($photo['uri'])."\"/></td>";
						print "<td class=\"col-sm-6 print-image-caption\">".$photo['title']."</td>";
						print "</tr>";
					} ?>
		</table>
			<?php endif; ?>
    <?php if ($sourceurl_enabled): ?>
      <div class="print-source_url">
			<hr class="print-hr" />
        <?php print theme('print_sourceurl', array('url' => $source_url, 'node' => $node, 'cid' => $cid)); ?>
      </div>
    <?php endif; ?>
		</div>
  </body>
</html>
