<?php global $wptouch_settings; ?>

<div class="metabox-holder">
	<div class="postbox">
		<h3><?php _e( "Adsense, Stats &amp; Custom Code", "wptouch" ); ?></h3>

			<div class="wptouch-left-content">
				<h4><?php _e( "Adsense", "wptouch" ); ?></h4>
					<p><?php _e( "Enter your Google AdSense ID if you'd like to support mobile advertising in WPtouch posts.", "wptouch" ); ?></p>
					<p><?php _e( "Make sure to include the 'pub-' part of your ID string.", "wptouch" ); ?></p>
				<br />
			    <h4><?php _e( "Stats &amp; Custom Code", "wptouch" ); ?></h4>
			 		<p><?php _e( "If you'd like to capture traffic statistics ", "wptouch" ); ?><br /><?php _e( "(Google Analytics, MINT, etc.)", "wptouch" ); ?></p>
			 		<p><?php _e( "Enter the code snippet(s) for your statistics tracking here.", "wptouch" ); ?></p>
			 		<p><?php _e( "You can also enter custom CSS &amp; other code here.", "wptouch" ); ?></p>
			</div><!-- left content -->

			<div class="wptouch-right-content">
				<ul class="wptouch-make-li-italic">
					<li><input name="adsense-id" type="text" value="<?php echo $wptouch_settings['adsense-id']; ?>" /><?php _e( "Google AdSense ID", "wptouch" ); ?></li>
					<li><input name="adsense-channel" type="text" value="<?php echo $wptouch_settings['adsense-channel']; ?>" /><?php _e( "Google AdSense Channel", "wptouch" ); ?></li>
				</ul>
			
				<textarea id="wptouch-stats" name="statistics"><?php echo stripslashes($wptouch_settings['statistics']); ?></textarea>
			</div><!-- right content -->
		<div class="wptouch-clearer"></div>
	</div><!-- postbox -->
</div><!-- metabox -->