<?php global $wptouch_settings; ?>

<div class="wptouch-itemrow">
	<div class="wptouch-item-desc">
		<h2><?php _e( "Default Menu Items", "wptouch" ); ?></h2>
		
		<p><?php _e( "Enable/Disable these default items in the WPtouch dropdown menu.", "wptouch"); ?></p>
	</div>		
	
	<div class="wptouch-item-content-box1">
		<div class="wptouch-checkbox-row">
			<input type="checkbox" name="enable-main-home" <?php if (isset($wptouch_settings['enable-main-home']) && $wptouch_settings['enable-main-home'] == 1) echo('checked'); ?> />
			<label for="enable-main-home"><?php _e( "Enable Home Icon", "wptouch" ); ?></label>
		</div>
	
		<div class="wptouch-checkbox-row">
			<input type="checkbox" name="enable-main-rss" <?php if (isset($wptouch_settings['enable-main-rss']) && $wptouch_settings['enable-main-rss'] == 1) echo('checked'); ?> />
			<label for="enable-main-rss"><?php _e( "Enable RSS Icon", "wptouch" ); ?></label>
		</div>
	
		<div class="wptouch-checkbox-row">
			<input type="checkbox" name="enable-main-email" <?php if (isset($wptouch_settings['enable-main-email']) && $wptouch_settings['enable-main-email'] == 1) echo('checked'); ?> />
			<label for="enable-main-email"><?php _e( "Enable Email Icon", "wptouch" ); ?></label>
		</div>
	</div>

	<div class="wptouch-clearer"></div>
</div>
