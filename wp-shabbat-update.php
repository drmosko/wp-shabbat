<?php

/***********************
 function to auto update the dat file
 *******************/
$options = get_option('wp_shabbat_settings');


$nextUpdate = strtotime('+1 month',$options['lastUpdate']) ;



$lastfiletime = filectime(plugin_dir_path( __FILE__ ).'/GeoLiteCity.dat');

// check if month past from last update
if ( $nextUpdate <  current_time(timestamp) ){

		$upload_dir = wp_upload_dir();
		$remotefilesize = strlen(file_get_contents('http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz'));
		
		// Use wp_remote_get to fetch the data
		$response = wp_remote_get('http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz')  ;

		// Save the body part to a variable
		$fileContent = $response['body'];


		// Create the name of the file and the declare the directory and path
		$file = $upload_dir['path'].'/GeoLiteCity.dat.gz';

		// Now use the standard PHP file functions
		$fp = fopen($file, "w");
		fwrite($fp, $fileContent);
		fclose($fp);
		
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
		
		
		if ( ($options['lastUpdate'] == 0 ) ){ // check if first time update
			$today = getdate(current_time(timestamp)); 
			$first_day = getdate(mktime(0,0,0,$today['mon'],1,$today['year'])); 
			$nextUpdate = $first_day[0];
			
		} 
		
		$new_settings = array(
		'Candle' => $options['CandleDefault'],
		'Havdala' => $options['HavdalaDefault'],
		'updatestatus' => $updatestatus,
		'lastUpdate' => $nextUpdate,
		);
		
		update_option('wp_shabbat_settings', $new_settings);
		
}
?>