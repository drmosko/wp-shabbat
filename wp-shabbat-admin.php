
<div class="wrap">

	<?php screen_icon(); ?>

	<h2> WP-Shabbat Plugin is Working</h2>
	
	<form action="options.php" method="post">
	<?php settings_fields( 'wp_shabbat_settings' ); ?>
	<?php do_settings_sections( 'wp_shabbat'  ); ?>
	<?php submit_button(); ?>
	</form>
	
 
</div>
