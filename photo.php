<pre><?php 

define('DRUPAL_ROOT', getcwd());


require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);




$i=1;

$file = "photos_import_3.csv";

print $file . "<br/>";

$file_handle2 = fopen($file, "r");

  while (($data = fgetcsv($file_handle2, 8000, ",")) !== FALSE) {

	//echo "<pre>".print_r($data,1)."</pre>";
	
	
  $nid = $data[0];
	$index = $data[1];
	$filepath = $data[2];
	$title = $data[3];
  //$lat = $data[1];
  //$long = $data[2];

  
  $node = node_load($nid, $nid, FALSE);
	if ($node){
		/* Drupal Node fields */
		// Create managed File object and associate with Image field.
		$file = (object) array(
			'uid' => 1,
			'uri' => $filepath,
			'filemime' => file_get_mimetype($filepath),
			'status' => 1,
			'alt' => $title,
			'title' => $title,
			'image_field_caption' => array(
				'value' => $title,
				'format' => 'plain_text'
			)
		);
		
		//echo print_r($file,1);

		// We save the file to the root of the files directory.
		$file = file_copy($file, 'public://photos/projects');

		$node->field_photos['und'][$index] = (array)$file;

		print $i . " - " . $nid . " - " . $index . " - " . $filepath . " - ".$title."<br/>";

		node_save($node); // save the data through drupal save node
		//echo print_r($node->field_photos,1);
	} else {
		print "NO SUCH NODE<br/>";
	}

$i++; 

      }

?></pre>