<?php
add_action( 'wp_enqueue_scripts', 'wp_shabbat_popup_scripts' );
function wp_shabbat_popup_scripts() {
	wp_enqueue_script(
		'popup_express_script',
		plugin_dir_url( __FILE__ ) . 'js/wp-shabbat-popup.js',
		array( 'jquery' )
	);
	wp_enqueue_script('jquerytools', 'http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js');
	wp_enqueue_style('wp_shabbat_popup_style', plugin_dir_url( __FILE__ ) . 'css/wp-shabbat-popup-template.css');

}

add_action('wp_footer', 'wp_shabbat_popup_filter');

function wp_shabbat_popup_filter( ) {
	
   
global $exitTime;
global $dayDetails;
global $dayTimeComeBack;
?>

<div id="wp_shabbat_content">
	<?php echo '<center><h1>'.__('We Dont Work Today','WP-Shabbat').'</h1>
						<h2>'.__('Today is : ','WP-Shabbat'). $dayDetails .'</h2>
						<h3>'.__('Please come back ','WP-Shabbat'). date('H:i',$exitTime) .'</h3></center>'; 
	?>

					 
	<div class="wp_shabbat_button">
	<button type="button" name="close" class="close" id="wp_shabbat_button"><?php echo __('Close Window ','WP-Shabbat'); ?></button>
	</div>
</div>

 <?php
}

?>