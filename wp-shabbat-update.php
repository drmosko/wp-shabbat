<?php

/***********************
 function to auto update the dat file
 *******************/
$options = get_option('wp_shabbat_update_settings');

$nextUpdate = strtotime('+1 month',$options['lastUpdate']) ;
//$nextUpdate = strtotime('+5 min',$options['lastUpdate']) ;


$lastfiletime = filectime(plugin_dir_path( __FILE__ ).'/GeoLiteCity.dat');

// check if month past from last update
if ( $nextUpdate <  current_time(timestamp) ){
		
		$upload_dir = wp_upload_dir();
		$remotefilesize = strlen(file_get_contents('http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz'));
		
		$file = download_url('http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz');
		$localfilesize = strlen(file_get_contents($file));
		
		if ( $localfilesize == $remotefilesize) {
			// get contents of a gz-file into a string
			$sfp = gzopen($file, "rb");
			$fp = fopen(plugin_dir_path( __FILE__ ).'/GeoLiteCity.dat', "w");

			while ($string = gzread($sfp, 4096)) {
				fwrite($fp, $string, strlen($string));
			}
			gzclose($sfp);
			fclose($fp);
			
			
			
			if ( filemtime(plugin_dir_path( __FILE__ ).'/GeoLiteCity.dat') > $lastfiletime ) {
				$updatestatus = 'file extracted succefully';
			} else {
				$updatestatus = 'problem with extraction';
			}
		}
		else 
		{
		 $updatestatus = 'problem with gzip file download';
		}
	
	// delete the gz file
	unlink($file);
	
	// check if first time update
	if ( ($options['lastUpdate'] == 0 ) ){ 
			$today = getdate(current_time(timestamp)); 
			$first_day = getdate(mktime(0,0,0,$today['mon'],1,$today['year'])); 
			$nextUpdate = $first_day[0];
			
		} 	
		
	echo $nextUpdate;
	/*echo 'DEBUG: <pre>';
	print_r($options);
	echo '</pre>' ;*/
	$new_settings = array(
		'updatestatus' => $updatestatus,
		'lastUpdate' =>  $nextUpdate,
		
		
	);
	/*echo 'DEBUG: <pre>';
	print_r($new_settings);
	echo '</pre>' ;*/
	
		
	update_option('wp_shabbat_update_settings', $new_settings);
		
}
?>