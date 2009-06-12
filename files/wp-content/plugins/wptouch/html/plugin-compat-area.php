<?php require_once( ABSPATH . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/include/plugin.php' ); ?>
<?php global $wptouch_settings; ?>

<?php $version = bnc_get_wp_version(); ?>

<div class="wptouch-itemrow">
	<div class="wptouch-item-desc">
	
	<h2><?php _e( "Plugin Support &amp; Compatibility", "wptouch" ); ?></h2>
	<p>
		<strong>
		<?php
			if ($version > 2.71) {
				echo __( 'WordPress installed: ', 'wptouch' ) . get_bloginfo('version') . '<br />(' . __( 'Untested', 'wptouch' ) . ')';
			} elseif ($version >= 2.5) {
				echo __('WordPress installed: ', 'wptouch' ) . get_bloginfo('version') . '<br />(' . __( 'Fully Supported', 'wptouch' ) . ')';
			} elseif ($version >= 2.3) {
				echo __( 'WordPress installed: ', 'wptouch' ) . get_bloginfo('version') . '<br />(' . __( 'Supported, Upgrade Recommended', 'wptouch' ) . ')';
			} else {
				echo __( 'WordPress installed: ', 'wptouch' ) . get_bloginfo('version') . '<br />(' . __( 'NOT Supported! Upgrade Required', 'wptouch' ) . ' <u>' . __( 'Required', 'wptouch' ) . '</u>)';
			} 
		?>	
		</strong>
	</p>
	<p><?php _e( "Here you'll find info on additional WPtouch features and their requirements, including those activated with companion plugins.", "wptouch" ); ?></p>
	<p><?php _e( "For further documentation visit" ); ?> <a href="http://www.bravenewcode.com/wptouch/"><?php _e( "BraveNewCode", "wptouch" ); ?></a>.</p>
	<p><?php echo sprintf( __( "To report an incompatible plugin, let us know in our %sSupport Forums%s.", "wptouch"), '<a href="http://support.bravenewcode.com/">', '</a>' ); ?></p>
</div>
		
<div class="wptouch-item-content-box1 wptouch-admin-plugins">
	<h4><?php _e( "WordPress Built-in Functions Support", "wptouch" ); ?></h4>

	<!-- wp tag cloud -->
	<?php if (function_exists('wp_tag_cloud')) { ?>
	<div class="all-good">
		<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/good.png" alt="" /> <?php _e( "The tag cloud for WordPress will automatically show on a page called 'Archives' if you have one.", "wptouch" ); ?>
	</div>
	<?php } else { ?>
	<div class="too-bad">
		<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/bad.png" alt="" /> <?php _e( "Since you're using a pre-tag version of WordPress, your categories will be listed on a page called 'Archives', if you have it.", "wptouch" ); ?>
	</div>
	<?php } ?>
			   
	<br /><br />
						   
	<h4><?php _e( "WordPress Pages &amp; Feature Support", "wptouch" ); ?></h4>
 
	<?php
	  //Start Pages support checks here
	  
	  //WordPress Links Page Support
	  $links_page_check = new WP_Query('pagename=links');
	  if ($links_page_check->post->ID) {
		  echo '<div class="all-good"><img src="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/good.png" alt="" /> ' . __( "All of your WP links will automatically show on your page called 'Links'.", "wptouch" ) . '</div>';
	  } else {
		  
		  echo '<div class="too-bad"><img src="' . get_bloginfo('wpurl') . '/wp-content/' .  wptouch_get_plugin_dir_name() . '/wptouch/images/bad.png" alt="" /> ' . __( "If you create a page called 'Links', all your WP links would display in <em>WPtouch</em> style.", "wptouch" ) . '</div>';
	  }
?>
						
		  <?php
				  //WordPress Photos Page with and without FlickRSS Support	 
				  $links_page_check = new WP_Query('pagename=photos');
				  if ($links_page_check->post->ID && function_exists('get_flickrRSS')) {
					  echo '<div class="all-good"><img src="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/good.png" alt="" /> ' . __( 'All your <a href="http://eightface.com/wordpress/flickrrss/" target="_blank">FlickrRSS</a> images will automatically show on your page called \'Photos\'.', 'wptouch' ) . '</div>';
				  } elseif ($links_page_check->post->ID && !function_exists('get_flickrRSS')) {
					  echo '<div class="sort-of"><img src="' . get_bloginfo('wpurl') . '/wp-content/' .  wptouch_get_plugin_dir_name() . '/wptouch/images/sortof.png" alt="" /> ' . __( 'You have a page called \'Photos\', but don\'t have <a href="http://eightface.com/wordpress/flickrrss/" target="_blank">FlickrRSS</a> installed.', 'wptouch' ) . '</div>';
				  } elseif (!$links_page_check->post->ID && function_exists('get_flickrRSS')) {
					  echo '<div class="sort-of"><img src="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/sortof.png" alt="" /> ' . __( 'If you create a page called \'Photos\', all your <a href="http://eightface.com/wordpress/flickrrss/" target="_blank">FlickrRSS</a> photos would display in <em>WPtouch</em> style.', 'wptouch' ) . '</div>';
				  } else {
					  
					  echo '<div class="too-bad"><img src="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/bad.png" alt="" /> ' . __( 'If you create a page called \'Photos\', and install the <a href="http://eightface.com/wordpress/flickrrss/" target="_blank">FlickrRSS</a> plugin, your photos would display in <em>WPtouch</em> style.', 'wptouch' ) . '</div>';
				  }
?>

			<?php
				  //WordPress Archives Page Support with checks for Tags Support or Not
				  $links_page_check = new WP_Query('pagename=archives');
				  if ($links_page_check->post->ID && function_exists('wp_tag_cloud')) {
					  echo '<div class="all-good"><img src="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/good.png" alt="" /> ' . __( 'Your tags and your monthly listings will automatically show on your page called \'Archives\'.', 'wptouch' ) . '</div>';
				  } elseif ($links_page_check->post->ID && !function_exists('wp_tag_cloud')) {
					  echo '<div class="sort-of"><img src="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/good.png" alt="" /> ' . __( 'You don\'t have WordPress 2.3 or above, so no Tags will show, but your categories and monthly listings will automatically show on your page called \'Archives\'.', 'wptouch' ) . '</div>';
				  } else {		   
					  echo '<div class="too-bad"><img src="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/bad.png" alt="" /> ' . __( 'If you create a page called \'Archives\', your tags/categories and monthly listings would display in <em>WPtouch</em> style.', 'wptouch' ) . '</div>';
				  }
?>
			  <br /><br />

	<h4><?php _e( 'Other Plugin Support &amp; Compatibility', 'wptouch' ); ?></h4>
	
	<!-- custom anti spam -->
	<?php if (!function_exists('cas_register_post')) { ?>
		<div class="all-good">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/good.png" alt="" />  <?php _e( 'Cool! <a href="http://wordpress.org/extend/plugins/peters-custom-anti-spam-image/" target="_blank">Peter\'s Custom Anti-Spam</a>: Your comment form supports it.', 'wptouch'); ?></div>
	<?php } else { ?>
		<div class="sort-of">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/sortof.png" alt="" /> <?php _e( 'You don\'t have <a href="http://wordpress.org/extend/plugins/peters-custom-anti-spam-image/" target="_blank">Peter\'s Custom Anti-Spam</a> installed (Your commentform supports it).', 'wptouch' ); ?></div>
	<?php } ?>


	<!-- flickr rss -->	  
	<?php if (function_exists('get_flickrRSS')) { ?>
		<div class="all-good">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/good.png" alt="" /> <?php _e( 'Cool! <a href="http://eightface.com/wordpress/flickrrss/" target="_blank">FlickrRSS</a>: Your photos will automatically show on a page called \'Photos\'.', 'wptouch' ); ?>
		</div>
	<?php } else { ?>
		<div class="sort-of">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/sortof.png" alt="" /> <?php _e( 'You don\'t have <a href="http://eightface.com/wordpress/flickrrss/" target="_blank">FlickrRSS</a> installed (No automatic photos page support)', 'wptouch' ); ?>
		</div>
	<?php } ?>
			  
			  
	<!-- blipit -->
	<?php if (function_exists('bnc_blipit_head')) { ?>
		<div class="all-good">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/good.png" alt="" /> <?php _e( 'Cool! <a href="http://www.bravenewcode.com/blipit/" target="_blank">Blip.it</a>: Your videos will automatically show on your posts in iPhone version.', 'wptouch' ); ?>
		</div>
	<?php } else { ?>
		<div class="sort-of">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/sortof.png" alt="" /> <?php _e( 'You don\'t have <a href="http://www.bravenewcode.com/blipit/" target="_blank">Blip.it</a> installed: (No automatic iPhone compatible video support)', 'wptouch' ); ?>
		</div>
	<?php } ?>
	
			  
	<!-- wp cache -->		  
	<?php if (function_exists('wp_cache_is_enabled')) { ?>
		<div class="sort-of">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/sortof.png" alt="" /> <?php _e( '<a href="http://mnm.uib.es/gallir/wp-cache-2/" target="_blank">WP-Cache</a> active. It <strong><a href="http://www.bravenewcode.com/wptouch/">requires special configuration</a></strong> to work with WPtouch.', 'wptouch' ); ?>
		</div>
	<?php } else { ?>
		<div class="all-good">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/good.png" alt="" /> <?php _e( 'No <a href="http://mnm.uib.es/gallir/wp-cache-2/" target="_blank">WP-Cache</a> active. If activated, <strong>it requires configuration.</strong> Visit the <a href="http://www.bravenewcode.com/wptouch/">WPtouch page</a> for help.', 'wptouch' ); ?>
		</div>
	<?php } ?>
			
			
	<!-- wp super cache -->
	<?php if (function_exists('wp_super_cache_footer')) { ?>
		<div class="sort-of">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/sortof.png" alt="" /> <?php _e( '<a href="http://ocaoimh.ie/wp-super-cache/" target="_blank">WP Super Cache</a> support is currently experimental. Please visit <a href="http://www.bravenewcode.com/2009/01/05/wptouch-and-wp-super-cache/">this page</a> for more information.', 'wptouch' ); ?>
		</div>
	<?php } else { ?>
		<div class="all-good">
			<img src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/good.png" alt="" /> <?php _e( '<a href="http://ocaoimh.ie/wp-super-cache/" target="_blank">WP Super Cache</a> support is currently experimental. Please visit <a href="http://www.bravenewcode.com/2009/01/05/wptouch-and-wp-super-cache/">this page</a> for more information.', 'wptouch' ); ?>
		</div>
	<?php } ?>
	
	</div>
</div>
