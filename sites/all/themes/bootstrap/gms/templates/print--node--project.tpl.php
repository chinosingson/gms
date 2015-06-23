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
 
	//$node = $print['node'];
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
  <body classes="print print-page print-node-project print-node-<?php print $nid ?> <?php print $classes ?> gms">
    <?php if ($print_logo): ?>
      <div class="print-logo"><?php print $print_logo; ?></div>
    <?php endif; ?>
		<?php if (!empty($site_name)): ?>
		<div class="print-site_name"><h3><?php print $site_name; ?></h3></div>
		<?php endif; ?>
    <p />
    <!--div class="print-breadcrumb"><?php //print theme('print_breadcrumb', array('node' => $node)); ?></div-->
    <hr class="print-hr" />
		<h2 class="print-title"><?php print $node->title; ?></h2>
    <div class="print-content">
		<!-- MAP -->
		<div id="print-locations">
			<h3 class="print-title">Locations</h3>
			<?php print views_embed_view('leaflet_view_test','block_1',$nid); ?>
			<?php 	$view_map_pdf = views_get_view('leaflet_view_test');			// locations
								$sector = $node->field_adb_sector;
								//print_r($GLOBALS['base_url']."/".$sector[LANGUAGE_NONE][0]['taxonomy_term']->field_marker_path[LANGUAGE_NONE][0]['value']);
								//$markerPath = $GLOBALS['base_url']."/".$sector[LANGUAGE_NONE][0]['taxonomy_term']->field_marker_path[LANGUAGE_NONE][0]['value'];
								$markerPath = "http://gmsprogram.org/dev/".$sector[LANGUAGE_NONE][0]['taxonomy_term']->field_marker_path[LANGUAGE_NONE][0]['value'];
								print $markerPath;
							if($view_map_pdf){ 
								$iconurl = urlencode($markerPath);
								$coords = trim(str_replace(" ","",strip_tags($view_map_pdf->preview('block_3',array($nid)))));
							} ?>
							<img src="https://maps.googleapis.com/maps/api/staticmap?size=1000x200&zoom=7&markers=
icon:<?php print $iconurl; ?><?php print $coords; ?>">
		</div>
<div id="projects-table" class="container-fluid">
	<div class="row" style="padding:20px 20px;">
	<div class="col-md-6">
		<div id="print-details">
			<h3 class="print-title">Details</h3>
			<hr>
			<?php	print views_embed_view('project_details','block',$nid); ?>
		</div>
		<div id="print-funding">
			<h3 class="print-title">Funding</h3>
			<hr>
			<p>in US$('000)</p>
			<div id="funding-table">
				<table class="table">
					<tbody>
						<tr><th class="col-sm-5">ADB</th><td class="col-sm-7"><?php print ($printFundingSources['adb']!=0 ? number_format($printFundingSources['adb']) : "-"); ?></td></tr>
						<!--tr><th class="col-sm-5">ADB JSF-JFPR</th><td class="col-sm-7"><?php //print number_format($printFundingSources['adb_jsf_jfpr']); ?></td></tr>
						<tr><th class="col-sm-5">ADB TASF</th><td class="col-sm-7"><?php //print number_format($printFundingSources['adb_tasf']); ?></td></tr-->
						<tr><th class="col-sm-5">Government</th><td class="col-sm-7"><?php print ($printFundingSources['gov']!=0 ? number_format($printFundingSources['gov']) : "-"); ?></td></tr>
						<tr><th class="col-sm-5">Cofinancing</th><td class="col-sm-7"><?php print ($printFundingSources['cof']!=0 ? number_format($printFundingSources['cof']) : "-"); ?></td></tr>
					</tbody>
					<tfoot>
						<tr><th class="col-sm-5">Total</th><td class="col-sm-7"><?php print number_format($node->field_project_cost_total['und'][0]['value']); ?></td></tr>
					</tfoot>
				</table>
			</div>
			<div id="project-funding-chart" class="chart">
			</div>
		</div>
	</div>
		
	<div class="col-md-6">
		<div id="print-outputs">
			<h3 class="print-title">Outputs</h3>
			<hr>
			<?php print views_embed_view('outputs','block',$nid); ?>
		</div>
		<div id="print-impact-stories">
			<h3 class="print-title">Impact Stories</h3>
			<hr>
			<?php	print views_embed_view('impact_stories','block_1',$nid); ?>
		</div>
	</div>
</div>
<div class="row" style="padding:20px 20px;">
	
	<div class="col-md-12">
		<div id="print-photos">
				<div id="print-photo-list" class="container-fluid">
			<?php //print print_r(,1) 
				$photos = $node->field_photos[LANGUAGE_NONE];
				if (isset($photos)){ ?>
			<h3 class="print-title">Photos</h3>
			<hr>
				<?php
					//print "PHOTOS";
					foreach ($photos as $photo){
						print "<div class=\"row print-image-row\">";
						print "<div class=\"col-sm-6 print-image\"><img class=\"img-responsive\" src=\"".file_create_url($photo['uri'])."\"/></div>";
						print "<div class=\"col-sm-6 print-image-caption\">".$photo['title']."</div>";
						print "</div>";
						print "<hr>";
					}
				} ?>
				</div>
		</div>
	</div>
</div>
		
</div>
		
		</div>
  </body>
</html>
