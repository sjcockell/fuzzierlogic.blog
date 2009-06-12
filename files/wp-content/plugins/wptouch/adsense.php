<?php

/*
	Code taken from Google's website
	Modified to use Snoopy class for HTTP request
*/

if (!function_exists('google_append_url')) {

	if ( defined('ABSPATH') )
		require_once( ABSPATH . '/wp-includes/class-snoopy.php');
	else
		require_once( '../../../wp-includes/class-snoopy.php');

	$GLOBALS['google']['ad_type']='text_image';
	$GLOBALS['google']['channel']='';
	$GLOBALS['google']['color_border']='FFFFFF';
	$GLOBALS['google']['color_bg']='FFFFFF';
	$GLOBALS['google']['color_link']='0000CC';
	$GLOBALS['google']['color_text']='333333';
	$GLOBALS['google']['color_url']='008000';
	$GLOBALS['google']['format']='mobile_single';
	$GLOBALS['google']['https']=$_SERVER['HTTPS'];
	$GLOBALS['google']['host']=$_SERVER['HTTP_HOST'];
	$GLOBALS['google']['ip']=$_SERVER['REMOTE_ADDR'];
	$GLOBALS['google']['markup']='xhtml';
	$GLOBALS['google']['oe']='utf8';
	$GLOBALS['google']['output']='xhtml';
	$GLOBALS['google']['ref']=$_SERVER['HTTP_REFERER'];
	$GLOBALS['google']['url']=$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$GLOBALS['google']['useragent']=$_SERVER['HTTP_USER_AGENT'];
	
	function google_append_url(&$url, $param, $value) {
	  $url .= '&' . $param . '=' . urlencode($value);
	}
	
	function google_append_globals(&$url, $param) {
	  google_append_url($url, $param, $GLOBALS['google'][$param]);
	}
	
	function google_append_color(&$url, $param) {
	  global $google_dt;
	  $color_array = split(',', $GLOBALS['google'][$param]);
	  google_append_url($url, $param,
	                    $color_array[$google_dt % sizeof($color_array)]);
	}
	
	function google_set_screen_res() {
	  $screen_res = $_SERVER['HTTP_UA_PIXELS'];
	  $delimiter = 'x';
	  if ($screen_res == '') {
	    $screen_res = $_SERVER['HTTP_X_UP_DEVCAP_SCREENPIXELS'];
	    $delimiter = ',';
	  }
	  $res_array = explode($delimiter, $screen_res);
	  if (sizeof($res_array) == 2) {
	    $GLOBALS['google']['u_w'] = $res_array[0];
	    $GLOBALS['google']['u_h'] = $res_array[1];
	  }
	}
	
	function google_set_dcmguid() {
	  $dcmguid = $_SERVER['HTTP_X_DCMGUID'];
	  if ($dcmguid != '') {
	    $GLOBALS['google']['dcmguid'] = $dcmguid;
	  }
	}
	
	function google_get_ad_url() {
	  $google_ad_url = 'http://pagead2.googlesyndication.com/pagead/ads?';
	  $google_scheme = ($GLOBALS['google']['https'] == 'on')
	      ? 'https://' : 'http://';
	  foreach ($GLOBALS['google'] as $param => $value) {
	    if ($param == 'client') {
	      google_append_url($google_ad_url, $param,
	                        'ca-mb-' . $GLOBALS['google'][$param]);
	    } else if (strpos($param, 'color_') === 0) {
	      google_append_color($google_ad_url, $param);
	    } else if ((strpos($param, 'host') === 0)
	               || (strpos($param, 'url') === 0)) {
	      google_append_url($google_ad_url, $param,
	                        $google_scheme . $GLOBALS['google'][$param]);
	    } else {
	      google_append_globals($google_ad_url, $param);
	    }
	  }
	  google_append_url($google_ad_url, 'dt',
	   		    round(1000 * array_sum(explode(' ', microtime()))));
	  return $google_ad_url;
	}
	
	function google_show_ad( $id, $channel = '' ) {
		global $bnc_wptouch_version;
		
		$ad = '';
		$GLOBALS['google']['client']= $id;
		$GLOBALS['google']['channel']= $channel;
		
		$google_dt = time();
		google_set_screen_res();
		google_set_dcmguid();	
		
   	$snoopy = new Snoopy;
   	$snoopy->agent = 'WPtouch ' . $bnc_wptouch_version;

		$ad = '';
      $result = $snoopy->fetch( google_get_ad_url() );
      if ( $result ) {
         $ad = $snoopy->results;
      }
		
		return $ad;
	}
}
?>