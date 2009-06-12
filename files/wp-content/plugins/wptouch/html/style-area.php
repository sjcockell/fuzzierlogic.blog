<?php global $wptouch_settings; ?>

<div class="wptouch-itemrow wptouchbump">
	<div class="wptouch-item-desc">
		<h2><?php _e( "Style &amp; Color Options", "wptouch" ); ?></h2>
		<p><?php _e( "Customize the appearance of your website title, colors and text WPtouch will use.", "wptouch" ); ?></p>
		<p><a href="http://www.colorpicker.com/" target="_blank"><?php _e( "Click here", "wptouch" ); ?></a> <?php _e( "to view a color picker to help you select hex colors.", "wptouch" ); ?></p>
	</div>
		
	<div class="wptouch-item-content-box1 wptouchstyle">
		<div class="header-item-desc"><?php _e( "Header Title Text", "wptouch" ); ?> <small>(<?php _e( "here you can override your site title to fit the WPtouch header", "wptouch" ); ?>)</small></div>
		<div class="header-input">&nbsp; <input type="text" name="header-title" value="<?php $str = $wptouch_settings['header-title']; echo stripslashes($str); ?>" /></div>
	
		<div class="header-item-desc"><?php _e( "Logo & site title header background color", "wptouch" ); ?></div>
		<div class="header-input">#<input type="text" name="header-background-color" value="<?php echo $wptouch_settings['header-background-color']; ?>" /></div>
	
		<div class="header-item-desc"><?php _e( "Header 'Search, Login &amp; Menu' background color", "wptouch" ); ?> <small>(<?php _e( "dark colors work best", "wptouch" ); ?>)</small></div>
		<div class="header-input">#<input type="text" name="header-border-color" value="<?php echo $wptouch_settings['header-border-color']; ?>" /></div>
	
		<div class="header-item-desc"><?php _e( "Header Text Color", "wptouch" ); ?></div>
		<div class="header-input">#<input type="text" name="header-text-color" value="<?php echo $wptouch_settings['header-text-color']; ?>" /></div>
	
		<div class="header-item-desc"><?php _e( "Site-wide Link Color", "wptouch" ); ?> <small>(<?php _e( "the color for most of the links in WPtouch", "wptouch" ); ?>)</small></div>
		<div class="header-input">#<input type="text" name="link-color" value="<?php echo $wptouch_settings['link-color']; ?>" /></div>		
		<div class="header-item-desc"><?php _e( "Font Size", "wptouch" ); ?></div>
		<div class="header-input">
			<select name="style-text-size">
				<option <?php if ($wptouch_settings['style-text-size'] == "small-text") echo " selected"; ?> value="small-text"><?php _e( "Regular", "wptouch" ); ?></option>
				<option <?php if ($wptouch_settings['style-text-size'] == "medium-text") echo " selected"; ?> value="medium-text"><?php _e( "Medium", "wptouch" ); ?></option>
				<option <?php if ($wptouch_settings['style-text-size'] == "large-text") echo " selected"; ?> value="large-text"><?php _e( "Large", "wptouch" ); ?></option>
			</select>
		</div>		

		<div class="header-item-desc"><?php _e( "Font Justification", "wptouch" ); ?></div>
		<div class="header-input">
			<select name="style-text-justify">
				<option <?php if ($wptouch_settings['style-text-justify'] == "left-justified") echo " selected"; ?> value="left-justified"><?php _e( "Left", "wptouch" ); ?></option>
				<option <?php if ($wptouch_settings['style-text-justify'] == "full-justified") echo " selected"; ?> value="full-justified"><?php _e( "Full", "wptouch" ); ?></option>
			</select>
		</div>

		<div class="header-item-desc"><?php _e( "Font Zoom (Accessibilty)", "wptouch" ); ?></div>
		<div class="header-input">
			<select name="bnc-zoom-state">
				<option <?php if ($wptouch_settings['bnc-zoom-state'] == "auto") echo " selected"; ?> value="auto"><?php _e( "On Rotate", "wptouch" ); ?></option>
				<option <?php if ($wptouch_settings['bnc-zoom-state'] == "none") echo " selected"; ?> value="none"><?php _e( "None", "wptouch" ); ?></option>
			</select>
		</div>


		<div class="header-item-desc"><?php _e( "Background Image", "wptouch" ); ?></div>
		<div class="header-input">
			<select name="style-background">
				<option <?php if ($wptouch_settings['style-background'] == "classic-wptouch-bg") echo " selected"; ?> value="classic-wptouch-bg"><?php _e( "Classic", "wptouch" ); ?></option>
				<option <?php if ($wptouch_settings['style-background'] == "horizontal-wptouch-bg") echo " selected"; ?> value="horizontal-wptouch-bg"><?php _e( "Horizontal Grey", "wptouch" ); ?></option>
				<option <?php if ($wptouch_settings['style-background'] == "diagonal-wptouch-bg") echo " selected"; ?> value="diagonal-wptouch-bg"><?php _e( "Diagonal Grey", "wptouch" ); ?></option>
			</select>
		</div>
	</div>
	
	<div class="wptouch-clearer"></div>
</div>
