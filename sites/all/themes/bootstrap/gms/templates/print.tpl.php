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
 
 //$view_chart_country = views_get_view('projects_per_country');
 //if (arg(0) == "dashboard") {
  //$view_chart_country->set_display('block_3');
	//print $view_chart_country->render();
 //}
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>">
  <head>
    <?php print $head; ?>
    <base href='<?php print $url ?>' />
    <title><?php (arg(0) == "maps") ? print "Main Map" : print $print_title; ?> (Printable Version) | <?php print $site_name ?></title>
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
    <p />
    <!--div class="print-breadcrumb"><?php //print theme('print_breadcrumb', array('node' => $node)); ?></div-->
    <?php if (!isset($node->type)): ?>
      <h2 class="print-title"><?php print $print_title; ?></h2>
    <?php endif; ?>
		<?php if (arg(0) == "maps"): ?>
			<h3 class="print-title">Main Map</h3>
			<div id="print-main-map">
				<?php 
					function haversineDistance($lat1, $lon1, $lat2, $lon2) {
						$latd = deg2rad($lat2 - $lat1);
						$lond = deg2rad($lon2 - $lon1);
						$a = sin($latd / 2) * sin($latd / 2) +
							 cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
							 sin($lond / 2) * sin($lond / 2);
							 $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
						return 6371.0 * $c;
					}

					define('OFFSET', 268435456);
					define('RADIUS', 85445659.4471); /* $offset / pi() */
								
					function lonToX($lon) {
						return round(OFFSET + RADIUS * $lon * pi() / 180);        
					}

					function latToY($lat) {
						return round(OFFSET - RADIUS * 
							log((1 + sin($lat * pi() / 180)) / 
							(1 - sin($lat * pi() / 180))) / 2);
					}

					function pixelDistance($lat1, $lon1, $lat2, $lon2, $zoom) {
						$x1 = lonToX($lon1);
						$y1 = latToY($lat1);

						$x2 = lonToX($lon2);
						$y2 = latToY($lat2);

						return sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) >> (21 - $zoom);
					}

					function cluster($markers, $distance, $zoom) {
						$clustered = array();
						/* Loop until all markers have been compared. */
						while (count($markers)) {
							$marker  = array_pop($markers);
							$cluster = array();
							/* Compare against all markers which are left. */
							foreach ($markers as $key => $target) {
								$pixels = pixelDistance($marker['lat'], $marker['lon'],
													$target['lat'], $target['lon'],
													$zoom);
								/* If two markers are closer than given distance remove */
								/* target marker from array and add it to cluster.      */
								if ($distance > $pixels) {
									//printf("Distance between %s,%s and %s,%s is %d pixels.\n",$marker['lat'], $marker['lon'],$target['lat'], $target['lon'],$pixels);
									unset($markers[$key]);
									$cluster[] = $target;
								}
							}

							/* If a marker has been added to cluster, add also the one  */
							/* we were comparing to and remove the original from array. */
							if (count($cluster) > 0) {
								$cluster[] = $marker;
								$clustered[] = $cluster;
							} else {
								$clustered[] = $marker;
							}
						}
						return $clustered;
					}

					$view_main_map = views_get_view_result('project_locations','maps_projects');
					if ($view_main_map) {
						//print "MERON!<br/>";
						$latlngs = array();
						$url = "https://maps.googleapis.com/maps/api/staticmap?size=1000x1000&zoom=5&";
						
						$x = 1;
						$markers = array();
						foreach ($view_main_map as $info){
							$coord_info = $info->field_field_google_coordinates;
							if (count($coord_info)<>0){
								$latlngpair = number_format($coord_info[0]['raw']['lat'], 2, '.', '').",".number_format($coord_info[0]['raw']['lng'], 2, '.', '');
								$latlngs[]=$latlngpair;
								$markers[]=array('id'=>"marker_".$x,'lat'=>number_format($coord_info[0]['raw']['lat']),'lon'=>number_format($coord_info[0]['raw']['lng']));
								//print $x.". ".$latlngpair."<br/>";
								//if ($x == 50) break 1;
							}
							$x++;
						}
						
						$somelatlngs = array_slice($latlngs,0,150);
						//$somemarkers = array_slice
						$clustered = cluster($markers,35,5);
						print "<br/>clusters:".count($clustered)."<br/>";
						$clusters = array();
						foreach($clustered as $cluster){
							//print print_r($cluster,1)."<br/>";
							//if ($locCount == 3) {
							if (!is_array($cluster[0])) {
								@$clusters[] = "markers=label:1|".$cluster['lat'].",".$cluster['lon'];
							} else { //if($locCount > 1) {
								$locCount = count($cluster);
								//if ($locCount <= 9){
									//$label = $locCount;
								//} else {
								//	
									$label = "M";
								//}
								@$clusters[] = "markers=label:".$label."|".$cluster[0]['lat'].",".$cluster[0]['lon'];
							}
						}
						//print "".print_r($clustered,1)."<br/>";
						
						$coords = implode("&",$clusters);
						$url .= $coords;
						print $url."<br/>";
						//print count($latlngs)."<br/>";
						//print_r($view_main_map);
					}
				?><!-- icon:<?php //print $iconurl ?>-->
				<img id="print-map" src="<?php //print $url; ?>">
			</div>
		<?php endif; ?>
		<?php if (arg(0) != "dashboard"): ?>
    <div class="print-content"><?php //print_r($content); ?>
		<?php 					$view_pp_type = views_get_view_result('projects_per_type','block_3');
					$view_pp_sector = views_get_view_result('projects_per_sector','block_3');
					
					//print_r($view_pp_type);
					//print_r($view_pp_sector);

					?>
		</div><?php endif; if(arg(0) == "search"): ?>
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
					print "<li class\"print-pdf-search-result\"><h4 class=\"print-pdf-search-result-link\"><a href=\"".$srchlink."\">".$srchtitle."</a></h4></li>";
					//$x
				}
				print "</ol>";
			?>
		<?php endif; ?>

    <?php if ($sourceurl_enabled): ?>
			<hr class="print-hr" />
      <div class="print-source_url">
        <?php print theme('print_sourceurl', array('url' => $source_url, 'node' => $node, 'cid' => $cid)); ?>
      </div>
    <?php endif; ?>
  </body>
</html>