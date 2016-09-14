<?php
	drupal_add_css('
		body{background: #3a454b;}
	', array('type' => 'inline'));

	//echo print_r($variables,1)."<br/>";
	
?>

<div id="form-gms-login">
	<h2 class="gms-login-headertext">Login</h2>
	<div id="form-gms-container"> 
		<p id="intro-text"><?php print render($intro_text); ?></p>
		<p>Not yet registered? <a href="user/register" style="text-decoration:underline;color:#fff;">Sign up here.</a></p><br/>
		<?php print drupal_render_children($form) ?>
	</div>
</div>