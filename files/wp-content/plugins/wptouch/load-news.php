<?php	
	if ( defined('ABSPATH') )
		require_once( ABSPATH . '/wp-includes/class-snoopy.php');
	else
		require_once( '../../../wp-includes/class-snoopy.php');

   $snoopy = new Snoopy;

	if (isset($_GET['donations'])) {
   		$snoopy->fetch('http://www.bravenewcode.com/purchase/last_donations.php');
   		$response = $snoopy->results;
		echo $response;
	} else {
   		$snoopy->fetch('http://www.bravenewcode.com/custom/wptouch-news.php');
   		$response = $snoopy->results;
   		echo '<h3>Latest News</h3>' . $response;
	}
?>