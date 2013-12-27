<?php
 
// get all user details
$user_time = wp_shabbat_user_time();
$user_localday = wp_shabbat_user_localday();
$user_timezone_offset = wp_shabbat_user_timezone_offset();
$record = wp_shabbat_user_record();



/****************

	candle light time

	*************/
	
function candleTime($user_time,$user_timezone_offset,$record) {
	
	$options = get_option('wp_shabbat_settings');
	
	$sunsetLocalTime = date_sunset($user_time, SUNFUNCS_RET_TIMESTAMP, $record->latitude, $record->longitude, ini_get("date.sunset_zenith"), $user_timezone_offset) ;
	
	$userCandleMin = $options['Candle'];
	
	$candleLightTime = $sunsetLocalTime - (60*$userCandleMin);// 60*20 reduce 20 minutes in timstamp
	
	return ($candleLightTime+$user_timezone_offset); 
}



/**************

 calculate havdala time

 ********************/
  
function havdala($user_time,$user_timezone_offset,$record) {
	
	$options = get_option('wp_shabbat_settings');
	
	$userHavdalaMin = $options['Havdala'];
	
	$sunrise = date_sunrise($user_time, SUNFUNCS_RET_TIMESTAMP, $record->latitude, $record->longitude, ini_get("date.sunrise_zenith"), ($user_timezone_offset/3600));

	$sunset = date_sunset($user_time, SUNFUNCS_RET_TIMESTAMP, $record->latitude, $record->longitude, ini_get("date.sunset_zenith"), ($user_timezone_offset/3600));

	$minutes = round(abs($sunset - $sunrise) / 60,2);

	$jewishHour = $minutes /12;


	//$havdala = $sunset + $jewishHour/4*60 ;
	if ($userHavdalaMin == 60) {
			$havdala = $sunset + $jewishHour*60  ;
			
	} else {	
		if ($userHavdalaMin > 60) {
			$havdala = $sunset + $jewishHour*60 + $jewishHour/(60/($userHavdalaMin-60))*60 ;
			
		} else {
	
			$havdala = $sunset + $jewishHour/(60/$userHavdalaMin)*60 ;
			
		
			}
	}


	return round(abs($havdala+$user_timezone_offset));   // in timestamp
}


/************************

	hebrew date

**********************/

function isHoliday(){



$hebrewDate = jdtojewish(gregoriantojd( date('m'), date('d'), date('Y')), true, CAL_JEWISH_ADD_GERESHAYIM); // for today

$hebrewDate = iconv ('WINDOWS-1255', 'UTF-8', $hebrewDate); // convert to utf-8

    switch($hebrewDate) {
	// בדיקת יום כניסת חג ערב חג
	case preg_match('/כ"ט אלול/', $hebrewDate) == 1:// ראש השנה א
	case preg_match("/ט' תשרי/", $hebrewDate) == 1:// יום כיפור
	case preg_match('/י"ג תשרי/', $hebrewDate) == 1:// סוכות
	case preg_match('/כ"א תשרי/', $hebrewDate) == 1:// שמיני עצרת
	case preg_match('/י"ג ניסן/', $hebrewDate) == 1:// פסח
	case preg_match('/י"ט ניסן/', $hebrewDate) == 1:// שביעי של פסח
	case preg_match("/ה' סיון/", $hebrewDate) == 1://שבועות
	
	  $eveHoliday = 'eveHoliday';
	  return $eveHoliday;
	  break;
	
	// בדיקת חג ראשון
    
    case preg_match("/א' תשרי/", $hebrewDate) == 1:// ראש השנה א
    case preg_match("/ב' תשרי/", $hebrewDate) == 1:// ראש השנה ב
    case preg_match("/י' תשרי/", $hebrewDate) == 1:// יום כיפור
    case preg_match('/י"ד תשרי/', $hebrewDate) == 1:// סוכות
    case preg_match('/ט"ו תשרי/', $hebrewDate) == 1:// סוכות
    case preg_match('/כ"ב תשרי/', $hebrewDate) == 1:// שמיני עצרת
    case preg_match('/י"ד ניסן/', $hebrewDate) == 1:// פסח
	case preg_match('/ט"ו ניסן/', $hebrewDate) == 1:// פסח
	case preg_match("/כ' ניסן/", $hebrewDate) == 1:// שביעי של פסח
	case preg_match('/כ"א ניסן/', $hebrewDate) == 1:// שביעי של פסח
	case preg_match("/ו' סיון/", $hebrewDate) == 1://שבועות
	
	//case preg_match('/י"ב חשון/', $hebrewDate) == 1:// היום
	
      $firstHoliday = 'first';
	  return $firstHoliday;
	  break;
	  
    // בדיקת חג שני
    
    case preg_match('/ט"ז תשרי/', $hebrewDate) == 1:// סוכות
	case preg_match('/כ"ג תשרי/', $hebrewDate) == 1:// שמיני עצרת
	case preg_match('/ט"ז ניסן/', $hebrewDate) == 1:// פסח
	case preg_match('/כ"ב ניסן/', $hebrewDate) == 1:// שביעי של פסח
	case preg_match("/ז' ניסן/", $hebrewDate) == 1://שבועות
	//case preg_match('/י"ג חשון/', $hebrewDate) == 1://בדיקה
	
     $secondHoliday = 'second';
     return $secondHoliday = 'second';
    
      break;
    
	  }
}

/************************

	the whole script check user and redirect

**********************/
$redirect = site_url().'/?WP-Shabbat=Shabbat-Closed-Page&redirectReason='; //redirect uri
$enterTime = candleTime($user_time,$user_timezone_offset,$record);
$exitTime = havdala($user_time,$user_timezone_offset,$record);




    if ($record->country_code3 == 'ISR' )  // if the user from israel
	{	
		if ( !is_null(isHoliday()) ) {
			if (isHoliday() == 'eveHoliday') {
				if ( $user_time > $enterTime  ){ // check time
					header('Location: '. $redirect .__('Holiday','WP-Shabbat').'&opentime='.__('tommorow at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					//echo 'israel eveHoliday<br/>';
					 
					 
				}		
			}
			if (isHoliday() == 'first') {
				if ( $user_time < $exitTime  ){ // check time
					header('Location: '. $redirect .__('Holiday','WP-Shabbat').'&opentime='.__('at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					//echo 'israel first<br/>';
					
				}		
			}
			
		}
		else { // check shabas
			if ($user_localday == 'Fri') {
				if ( $user_time > $enterTime ){ // check time
					header('Location: '. $redirect .__('Shabbat','WP-Shabbat').'&opentime='.__('tommorow at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					
					
					
					
				}		
			}
			if ($user_localday == 'Sat') {
				if ( $user_time < $exitTime ){ // check time
					header('Location: '. $redirect .__('Shabbat','WP-Shabbat').'&opentime='.__('at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					
					
				}	
			}
		}
	}
	else //  not israel
	{
	if ( !is_null(isHoliday()) ) {
			if (isHoliday() == 'eveHoliday') {
				if ($user_time > $enterTime){ // check time
					header('Location: '. $redirect .__('Holiday','WP-Shabbat').'&opentime='.__('two day at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					//echo 'eveHoliday<br/>';
					
				}		
			}
			if (isHoliday() == 'first') {
				
					header('Location: '. $redirect .__('Holiday','WP-Shabbat').'&opentime='.__('tommorow at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					//echo 'first<br/>';
					
						
			}
			if (isHoliday() == 'second') {
				if ($user_time < $exitTime){ // check time
					header('Location: '. $redirect .__('Holiday','WP-Shabbat').'&opentime='.__('at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					//echo 'second<br/>';
					
				}		
			}
		}
		else { // check shabas
			if ($user_localday == 'Fri') {
				if ($user_time > $enterTime){ // check time
					header('Location: '. $redirect .__('Shabbat','WP-Shabbat').'&opentime='.__('tommorow at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					
					
				}		
			}
			if ($user_localday == 'Sat') {
				if ($user_time < $exitTime){ // check time
					header('Location: '. $redirect .__('Shabbat','WP-Shabbat').'&opentime='.__('at ','WP-Shabbat').date('H:i',$exitTime));    // redirect to url
					
					
				}	
			}
		}	
	}
	




?>