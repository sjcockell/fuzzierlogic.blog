<?php	
	require_once( WP_CONTENT_DIR . '/../wp-includes/class-snoopy.php');
	
	$snoopy = new Snoopy();
	$snoopy->offsiteok = true; /* allow a redirect to different domain */

	global $bnc_wptouch_version;
	$result = $snoopy->fetch( 'http://www.bravenewcode.com/custom/wptouch-beta.php?version=' . urlencode( $bnc_wptouch_version ) );
if($result) {
	echo $snoopy->results;
} else {
	echo '<p>We were not able to load the Downloads panel on your server.</p>';
}
?>