<?php 

// This checks to see if advanced JS is enabled in the WPtouch admin and then loads jQuery without collisions if possible (2.5 or higher)
function wptouch_enqueue() {
	$version = get_bloginfo('version'); 
		if ($version >= 2.5 && bnc_is_js_enabled() && !bnc_wptouch_is_exclusive() ) { 
			echo	 '' . wp_enqueue_script('jquery') . '';
		} elseif (bnc_is_js_enabled()) { 
			echo '<script src="http://www.google.com/jsapi"></script>';
			echo '<script type="text/javascript">google.load("jquery", "1");</script>';
		}
}
//Favicon fetch and convert script // This script will convert favicons for the links listed on your Links page (if you have one).
  function bnc_url_exists($url)
  {
// Version 4.x supported
      $handle = curl_init($url);
      if (false === $handle) {
          return false;
      }
      curl_setopt($handle, CURLOPT_HEADER, false);
      // this works
      curl_setopt($handle, CURLOPT_FAILONERROR, true);
      curl_setopt($handle, CURLOPT_NOBODY, true);
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
      curl_setopt($handle, CURLOPT_TIMEOUT, 1);
      $connectable = curl_exec($handle);
      $d = curl_getinfo($handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
      return($d > 0);
  }
  function bnc_get_ico_file($ico)
  {
      $d = file_get_contents($ico);
      if (!file_exists(bnc_get_local_dir() . '/cache')) {
          mkdir(bnc_get_local_dir() . '/cache', 0755);
      }
      file_put_contents(bnc_get_local_dir() . '/cache/' . md5($ico) . '.ico', $d);
      exec('sh convert ico:' . bnc_get_local_dir() . '/cache/' . md5($ico) . '.ico' . bnc_get_local_dir() . '/cache/' . md5($ico) . '.png');
  }
  function bnc_get_local_dir()
  {
      $dir = preg_split("#/plugins/wptouch/images/icon-pool/#", __FILE__, $test);
      return $dir[0] . '/plugins/wptouch/images/icon-pool';
  }
  function bnc_get_local_icon_url()
  {
      return get_bloginfo('wpurl') . '/wp-content/plugins/wptouch/images/';
  }
  function bnc_get_favicon_for_site($site)
  {
// Yes we know this goes remote to handle things, but we do this to ensure that it works for everyone. No data is collected, as you'll see if you look at the script.
      $i = 'http://www.bravenewcode.com/code/favicon.php?site=' . urlencode($site) . '&default=' . urlencode(bnc_get_local_icon_url() . '/icon-pool/default.png');
      return $i;
  }

$bnc_start_content = '[gallery]';
$bnc_end_content = '';

add_filter('the_content_rss', 'rename_content');

function rename_content($content) {
   global $bnc_start_content;
   global $bnc_end_content;

   $content = str_replace($bnc_start_content, $bnc_end_content, $content);

   return $content;
}
?>