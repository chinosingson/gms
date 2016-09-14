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
 
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>">
  <head>
    <?php print $head; ?>
    <base href='<?php print $url ?>' />
    <title><?php print $print_title; ?> (Printable Version) | <?php print $site_name ?></title>
    <?php print $scripts; ?>
    <?php if (isset($sendtoprinter)) print $sendtoprinter; ?>
    <?php print $robots_meta; ?>
    <?php if (theme_get_setting('toggle_favicon')): ?>
      <link rel='shortcut icon' href='<?php print theme_get_setting('favicon') ?>' type='image/x-icon' />
    <?php endif; ?>
    <?php print $css; ?>
  </head>
  <body class="print print-page print-<?php print arg(0) ?> gms">
		<?php if (!empty($site_name)): ?>
		<div class="print-site_name"><h3 style="color: #ffffff; background-color: #3b444d; position: absolute; float: right"><?php if ($print_logo): ?><span class="print-logo"><?php print $print_logo; ?></span><?php endif; ?><?php print $site_name; ?></h3></div>
		<?php endif; ?>
		<div id="pdf-body-container" style="margin:1.5em">
		<?php if (arg(0) == "dashboard"):
			$view_pp_country_full = views_get_view('projects_per_country');
			$view_pp_country_full->set_display('block_3');
			$view_pp_country = views_get_view_result('projects_per_country','block_3');?>
			<h3 class="print-title" style="width: 100%"><?php print $node->title; ?></h3>
			<div id="portfolio-indicators" class="col-pdf-dashboard col-md-6" style="float: left;">
				<h4 class="print-title">Portfolio At a Glance</h4>
				<hr class="print-hr"/>
				<div class="print-chart row"><h4 class="pane-title"><?php print $view_pp_country_full->get_title(); ?></h4>
				<?php 
					function create_static_chart($values,$labels){
						$sum = array_sum($values);
						foreach ($labels as $label){
							//$ue_labels[] = urlencode($label);
							$ue_labels[] = ($label);
						}
						foreach ($values as $count){
							$chart_final_values[] = number_format($count/$sum,2,".","");
						}
						
						//print "L: ".count($ue_labels)."<br/>";
						//print "V: ".count($chart_final_values)."<br/>";
						$chart_labels = implode("|",$labels);
						//print $chart_labels;
						$chart_values = implode(",",$chart_final_values);
						$img_url = "https://chart.googleapis.com/chart?cht=p&chs=600x350&chd=t:".$chart_values."&chdl=".$chart_labels."&chco=64994B|3CB6A3|188AD2|356FAB|9279BA|7C569A|A25462|E25279|E08255|E2BB3C|FBED90|AFB363&chdls=000000&chdlp=r|l&chp=-1.57&chma=0,0,0,0|150,150";
						//print $img_url;
						//print $chart_values;
						return "<img id=\"print-chart-ppc\" src=\"".$img_url."\" />";
					}
					
					
					$values_ctry = array();
					$values_tot = 0;
					$labels_ctry = array();
					foreach ($view_pp_country as $info) {
						$values_ctry[] = $info->field_country_taxonomy_term_data_nid;
					}

					$values_tot = array_sum($values_ctry);
					foreach ($view_pp_country as $info) {
						$term_data = $info->field_country_taxonomy_term_data_nid;
						$term_name = $info->taxonomy_term_data_name;
						$term_pct = number_format(($term_data/$values_tot)*100,1,".","");
						$labels_ctry[] = $term_name." - ".$term_data." (".$term_pct."%)";
					}
					
					print create_static_chart($values_ctry,$labels_ctry);
					
				?>
				</div>
				<?php 
					$view_pp_type_full = views_get_view('projects_per_type');
					$view_pp_type_full->set_display('block_3');
					$view_pp_type = views_get_view_result('projects_per_type','block_3');
				?>
				<div class="print-chart row"><h4 class="pane-title"><?php print $view_pp_type_full->get_title(); ?></h4><?php 
					$values_type = array();
					$values_tot = 0;
					$labels_type = array();
					foreach ($view_pp_type as $info) {
						$values_type[] = $info->field_project_type_taxonomy_term_data_nid;
					}
				
					$values_tot = array_sum($values_type);
					foreach ($view_pp_type as $info) {
						$term_data = $info->field_project_type_taxonomy_term_data_nid;
						$term_name = $info->taxonomy_term_data_name;
						$term_pct = number_format(($term_data/$values_tot)*100,1,".","");
						$labels_type[] = $term_name." - ".$term_data." (".$term_pct."%)";
					}

					print create_static_chart($values_type,$labels_type); ?></div>
				<?php 
					$view_pp_sector_full = views_get_view('projects_per_sector');
					$view_pp_sector_full->set_display('block_3');
					$view_pp_sector = views_get_view_result('projects_per_sector','block_3');
				?>
				<div class="print-chart row"><h4 class="pane-title"><?php print $view_pp_sector_full->get_title(); ?></h4><?php 
					$values_sector = array();
					$values_sector_tot = 0;
					$labels_sector = array();
					$pct_sector = array();
					foreach ($view_pp_sector as $info) {
						$values_sector[] = $info->field_adb_sector_taxonomy_term_data_nid;
					}
				
					//$values_sector_tot = array_sum($values_sector);
					foreach ($view_pp_sector as $info) {
						$term_sector_data = $info->field_adb_sector_taxonomy_term_data_nid;
						$term_sector_name = $info->taxonomy_term_data_name;
						if($term_sector_data != 0) { // && $values_sector_tot != 0) {
							//print "tot: ".$values_sector_tot."<br/>";
							//$term_sector_pct = ($term_sector_data/$values_sector_tot)*100;
							//print "sector pct: ".(string) number_format($term_sector_pct,1,'.','')."<br/>";
							$sector_label = $term_sector_name." - ".$term_sector_data; //."-";
							//$pct_sector[] = number_format($term_sector_pct,1,'.','');
							$labels_sector[] = $sector_label;
							//$values_sector[] = $term_sector_data;
						}
						//$labels_sector[] = urlencode($info->taxonomy_term_data_name." (".$info->field_adb_sector_taxonomy_term_data_nid.")");
					}
					//print_r($values_sector);
					//print_r($labels_sector);
					//print '<pre>'.print_r($pct_sector,1).'</pre>';
					//print implode("|",$labels_sector);
					print create_static_chart($values_sector,$labels_sector); ?></div>
			</div>
			<div class="col-pdf-dashboard col-md-6" style="float: right;">
				<h4 class="print-title">Regional Indicators</h4>
				<hr class="print-hr"/>
			<?php $block = module_invoke('block', 'block_view', '2');
					print render($block['content']);
				?>	
			</div>
		<?php elseif(arg(0) == "search"): ?>
			<h3 class="print-title"><?php print $title ?></h3>
			<?php //print $content 
				$keywords = arg(2);
				//print $keywords;
				$contentTypes = ' type:project';
				$info = search_get_default_module_info();
				$results = module_invoke('search', 'data', $keywords . $contentTypes, $info['module']);
				//$x = 1;
				print "<ol class=\"print-pdf-search-results\">";
				foreach ($results['#results'] as $sra){
					//print_r($sra);
					$srchlink = $sra['link'];
					$srchtitle = $sra['title'];
					print "<li class\"print-pdf-search-result\"><h4 class=\"print-pdf-search-result-item\"><a class=\"print-pdf-search-result-link\" href=\"".$srchlink."\">".$srchtitle."</a></h4></li>";
					//$x
				}
				print "</ol>";
			?>
		<?php else: ?>
			<?php if (!isset($node->type)): ?>
				<h3 class="print-title"><?php print $print_title; ?></h3>
			<?php endif; ?>
			<div class="print-content"><?php print_r($content); ?></div>
		<?php endif; ?>
    <?php if ($sourceurl_enabled): ?>
      <div class="print-source_url">
				<hr class="print-hr" />
        <?php print theme('print_sourceurl', array('url' => $source_url, 'node' => $node, 'cid' => $cid)); ?>
      </div>
    <?php endif; ?>
			<div class="print-disclaimer"><br/><p><strong>Disclaimer:</strong></p><?php $block = module_invoke('block', 'block_view', '5'); print render($block['content']); ?></div>
  </body>
</html>
