<?php
/*
Plugin Name: wp-shabbat
Plugin URI: www.dossihost.net
Description: Site closing on Saturday and Holidays by identifying the address of the user IP and close to 40 km
Version: 0.04
Author: DrMosko
Author URI: www.dossihost.net

Text Domain: WP-Shabbat
Domain Path: /lang
*/
/* This script uses GeoLite Country from MaxMind (http://www.maxmind.com) which is available under terms of GPL/LGPL */


 // add the admin settings and such
add_action('admin_init', 'wp_shabbat_admin_init');
function wp_shabbat_admin_init(){
	register_setting( 'wp_shabbat_settings', 'wp_shabbat_settings', 'wp_shabbat_settings_validate');
	add_settings_section('wp_shabbat_main', 'WP Shabbat Plugin Settings', 'wp_shabbat_main_section', 'wp_shabbat');
	add_settings_field('wp_shabbat_candle', __('Candle light Minutes','WP-Shabbat'), 'wp_shabbat_settings_input_candle', 'wp_shabbat', 'wp_shabbat_main');
	add_settings_field('wp_shabbat_havdala', __('Havdala Minutes','WP-Shabbat'), 'wp_shabbat_settings_input_havdala', 'wp_shabbat', 'wp_shabbat_main');
}
function wp_shabbat_main_section() {
	
	echo __('the website will close to user based on his location, when it is shabbat in one country the site will be closed but for the other country where its not shabbat the site is open','WP-Shabbat').'<br/>';
	echo __('WP-Shabbat correctly using a free database that we update every month , the plugin will download it from our servers automatically every month','WP-Shabbat').'<br/>';
	echo __('We recommend that every site owner will consult with a rabbi for the time to close his site','WP-Shabbat').'<br/>';
	echo '<h3>'.__('WP-Shabbat is wordpress plugin to close a website in shabbat and holidays,','WP-Shabbat').'</h3><br/>';

}
function wp_shabbat_settings_input_candle() { 
	$options = get_option('wp_shabbat_settings');
	echo __('insert minutes for candle light time, number of minutes before sunset minimum - 20 min ,max - 600 min.','WP-Shabbat').'<br/>';
	echo "<input id='wp_shabbat_candle' name='wp_shabbat_settings[Candle]' size='40' type='text' value='{$options['Candle']}' />";

	

}
function wp_shabbat_settings_input_havdala() {
	$options = get_option('wp_shabbat_settings');
	echo __('insert minutes for havdala time, number of jewish minutes after sunset minimum - 18 min ,max - 72 min.','WP-Shabbat').'<br/>'. __('(jewish hour is calculated from sunrise to sunset divided by 12)','WP-Shabbat').'<br/> ';
	echo "<input id='wp_shabbat_havdala' name='wp_shabbat_settings[Havdala]' size='40' type='text' value='{$options['Havdala']}' />";
	
}
// validate  
function wp_shabbat_settings_validate($input) {
	$options = get_option('wp_shabbat_settings');
	$options['Havdala'] = intval(wp_filter_nohtml_kses( trim( $input['Havdala']) ));
	$options['Candle'] = intval(wp_filter_nohtml_kses( trim( $input['Candle']) ));
	$havdala = $options['Havdala'];
	$candle = $options['Candle'];

if(  ( $candle < 20) || !is_numeric( $candle ) || preg_match("/^0/", $candle) || ( $candle > 600  ) )  { 
	$options['Candle'] = 20;
	add_settings_error(
				'wp_shabbat_validate_fail',           // setting title
				'wp_shabbat_validate_fail',            // error ID
				__('Invalid Candle light time, please enter valid input, default was loaded.','WP-Shabbat'),   // error message
				'error'                        // type of message
			);		
}

if(  ( $havdala < 18 ) || !is_numeric( $havdala ) || preg_match("/^0/", $havdala) || ( $havdala > 72  ) )  { 
	$options['Havdala'] = 18;
	add_settings_error(
				'wp_shabbat_validate_fail',           // setting title
				'wp_shabbat_validate_fail',            // error ID
				__('Invalid Havdalah light time, please enter valid input, default was loaded.','WP-Shabbat'),   // error message
				'error'                        // type of message
			);		
}

return $options;
}
// admin page
function wp_shabbat_admin() {   
    add_options_page( 'WP-Shabbat', 'WP-Shabbat','manage_options','wp_shabbat_admin', 'wp_shabbat_admin_options' );
	
}  
add_action('admin_menu', 'wp_shabbat_admin');  

function wp_shabbat_admin_options() {  // display the admin options page
?>
	<div>
	<?php screen_icon();
	$options = get_option('wp_shabbat_settings');	?>

	<h2> WP-Shabbat Plugin is Working</h2>
	<form action="options.php" method="post">
	<?php settings_fields( 'wp_shabbat_settings' ); ?>
	<?php do_settings_sections( 'wp_shabbat'  ); ?>
	 
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
	</div>
	<div>
	<h3><?php echo __('How closed page look like?','WP-Shabbat') ?> </h3>
	<a  target="_blank" href="<?php echo site_url().'/?WP-Shabbat=Shabbat-Closed-Page&redirectReason=' .__('Holiday','WP-Shabbat').'&opentime='.__('tommorow at ','WP-Shabbat').date('H:i',$exitTime); ?>" ><?php echo __('Test Page','WP-Shabbat') ; ?></a> 
	
	</div>
	<div id="WPShabbat-Donation">
	<?php
		echo '<br/><br/>'.__('Ip DataBase last updated at : ','WP-Shabbat').'<code>'. date('d.m.y',$options['lastUpdate']) .'</code><br/>';
		echo '<br/><br/>'.__('Update Status : ','WP-Shabbat').'<code>'. $options['updatestatus'] .'</code><br/>';
		echo '<br/><h2>' . __( 'Want to keep this plugin free, please make a donation','WP-Shabbat' ) . '</h2><br/>';
		
	?>	
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="53KUKZ9KN2YBN">
	<input type="image" src="https://www.paypalobjects.com/en_US/IL/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	</div>
	<div>
	<br/><br/>
	 <?php echo __( 'This product includes GeoLite data created by MaxMind, available from','WP-Shabbat' )?>
	<a href="http://www.maxmind.com">http://www.maxmind.com</a>
	</div>
 
<?php  
}  

// create globals : $user_time , $user_localtime , $user_timezone_offset
include( plugin_dir_path( __FILE__ ) . 'wp-shabbat-user.php');

// add function page and auto update function
function wp_shabbat() {
	if (!$_GET['WP-Shabbat'] == "Shabbat-Closed-Page"){ // if not shabbat fly page presented 
		
		include( plugin_dir_path( __FILE__ ) . 'wp-shabbat-functions.php');
    }
}
add_action( 'get_header', 'wp_shabbat' );

// create default values when user activate plugin
function wp_shabbat_activate() {
	
		delete_option('wp_shabbat_settings');				
			$settings = array(
			'CandleDefault' => 20,
			'HavdalaDefault' => 18,
			'Candle' => 20,
			'Havdala' => 18,
			'updatestatus' => 'Plugin DataBase need to be updated',
			'lastUpdate' => 0,
			);
	
			add_option('wp_shabbat_settings', $settings);
	
	
	include( plugin_dir_path( __FILE__ ) . 'wp-shabbat-update.php');
	
			
	
	
}
register_activation_hook( __FILE__, 'wp_shabbat_activate' );

// delete default values when user activate plugin
function wp_shabbat_deactivate() {
		
	delete_option('wp_shabbat_settings');
	
	
}
register_deactivation_hook( __FILE__, 'wp_shabbat_deactivate' );

// add update check 
function wp_shabbat_update() {
		include( plugin_dir_path( __FILE__ ) . 'wp-shabbat-update.php');
}
add_action( 'admin_init', 'wp_shabbat_update' );

// load language translation

function wp_shabbat_lang()  
{  
    // Localization  
    load_plugin_textdomain('WP-Shabbat', false, dirname(plugin_basename(__FILE__)) . '/lang');  
}  
  
// Add actions  
add_action('init', 'wp_shabbat_lang');  

// add on fly wp-shabbat-closed-page
include( plugin_dir_path( __FILE__ ) . 'wp-shabbat-closed-page.php');
?>