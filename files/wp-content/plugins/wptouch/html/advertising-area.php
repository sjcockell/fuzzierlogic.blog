<?php global $wptouch_settings; ?>

<div class="wptouch-itemrow">		
	<div class="double-box-one">
		<div class="wptouch-item-desc">
			<h2><?php _e( "Advertising Options", "wptouch" ); ?></h2>
			
			<p><?php _e( "Enter your Google AdSense ID if you'd like to support mobile advertising in WPtouch posts.", "wptouch" ); ?></p>
			<p><?php _e( "Make sure to include the 'pub-' part of your ID string.", "wptouch" ); ?></p>
		</div>	
			
		<div class="wptouch-item-content-box1 wptouchstyle">
			<div class="header-item-desc"><?php _e( "Google AdSense ID", "wptouch" ); ?></div>
			<div class="header-input">#<input type="text" name="adsense-id" type="text" value="<?php echo $wptouch_settings['adsense-id']; ?>" /></div>
			
			<div class="header-item-desc"><?php _e( "Google AdSense Channel", "wptouch" ); ?></div>
			<div class="header-input">#<input type="text" name="adsense-channel" type="text" value="<?php echo $wptouch_settings['adsense-channel']; ?>" /></div>
		</div>
	</div>
	
	<div class="double-box-two">
		<div class="wptouch-item-desc">
			<h2><?php _e( "Stats Tracking", "wptouch" ); ?></h2>
	 		<p><?php _e( "If you'd like to capture traffic statistics (Google Analytics, MINT, Etc):", "wptouch" ); ?></p>
	 		<p><?php _e( "Enter the code snippet(s) for your statistics tracking here.", "wptouch" ); ?></p>
		</div>	
		<textarea name="statistics"><?php echo stripslashes($wptouch_settings['statistics']); ?></textarea>
	</div>
		
	<div class="wptouch-clearer"></div>
</div>