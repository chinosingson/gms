<?php

	// something
	dpm($form);
  //print drupal_render_children($form);
	hide($form['body']);
  
  //print drupal_render_children($form['field_custom_image']);
  print drupal_render_children($form['title']);
  
  print drupal_render_children($form);
?>
<div>HELLO</div>