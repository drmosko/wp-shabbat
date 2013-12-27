<?php

function wp_shabbat_status_header() {
	
	

	wp_load_translations_early();

	$protocol = $_SERVER["SERVER_PROTOCOL"];
	if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
		$protocol = 'HTTP/1.0';
	header( "$protocol 503 Service Unavailable", true, 503 );
	header( 'Content-Type: text/html; charset=utf-8' );
	
	 get_header();
?>
	
		
		<?php echo '<center><h1>'.__('We Dont Work Today','WP-Shabbat').'</h1></center>
					 <center><h3>'.__('Today is : ','WP-Shabbat'). $_GET['redirectReason'] .'</h3></center><br/>
					 <center><h3>'.__('Please come back ','WP-Shabbat'). $_GET['opentime'] .'</h3><center>'; ?>
	</body>
	</html>
<?php
get_footer();
	die();
	
		
	
}
function wp_shabbat_closed_template() {
	
     
	if   ('' != (locate_template( 'page.php' )))  {
		include(TEMPLATEPATH.'/page.php');
	}else {
		if  ('' != (locate_template( 'index.php' )))  {
			include(TEMPLATEPATH.'/index.php');
		}else {
			if  ('' != (locate_template( 'single.php' )))  {
				include(TEMPLATEPATH.'/single.php');
			}else {
				if  ('' != (locate_template( 'home.php' )))  {
					include(TEMPLATEPATH.'/home.php');
					}
				}
			}	
		}
  exit;
}
if (! empty ( $_GET['WP-Shabbat'] ) && "Shabbat-Closed-Page" === $_GET['WP-Shabbat']) {
  add_filter('send_headers','wp_shabbat_status_header');
}
?>