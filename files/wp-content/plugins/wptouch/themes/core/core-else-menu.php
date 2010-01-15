	<?php if (bnc_is_login_button_enabled()) { ?>
		<?php if (!is_user_logged_in()) { ?>
			    <a id="loginopen" class="top" href="#" onclick="bnc_jquery_login_toggle(); return false;"><?php _e( 'Login', 'wptouch' ); ?></a>	
		<?php } else { ?>
			    <a id="accountopen" class="top" href="#" onclick="$wptouch('#wptouch-acct').fadeToggle(250);return false;"><?php _e( 'My Account', 'wptouch' ); ?></a>	
	<?php } } ?>

	<?php if (wptouch_prowl_direct_message_enabled()) { ?>			    
    	<a id="prowlopen" class="top" href="#" onclick="bnc_jquery_prowl_open(); return false;"><?php _e( 'Direct Message', 'wptouch' ); ?></a>
	<?php } ?>

	<?php if (bnc_is_cats_button_enabled()) { ?>			    
	    <a id="catsopen" class="top" href="#" onclick="$wptouch('#wptouch-cats').fadeToggle(250);return false;"><?php _e( 'Categories', 'wptouch' ); ?></a>
	<?php } ?>
	
	<?php if (bnc_is_tags_button_enabled()) { ?>	
	    <a id="tagsopen" class="top" href="#" onclick="$wptouch('#wptouch-tags').fadeToggle(250);return false;"><?php _e( 'Tags', 'wptouch' ); ?></a>
	<?php } ?>

 <!-- #start the Prowl Message Area -->
 <div id="prowl-message" style="display:none">
 	 <div id="prowl-style-bar"></div><!-- filler to get the styling just right -->
	 <img src="<?php echo compat_get_plugin_url( 'wptouch' ); ?>/themes/core/core-images/push-icon.png" alt="push icon" />
	 <h4><?php _e( 'Send a Push Notification', 'wptouch' ); ?></h4>
	 <p><?php _e( 'This message will be pushed to the admin\'s iPhone immediately.', 'wptouch' ); ?></p>
	 
	 <form id="prowl-direct-message" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	 	<p>
	 		<input name="prowl-msg-name"  id="prowl-msg-name" type="text" />
	 		<label for="prowl-msg-name"><?php _e( 'Name', 'wptouch' ); ?></label>
	 	</p>

		<p>
			<input name="prowl-msg-email" id="prowl-msg-email" type="text" />
			<label for="prowl-msg-email"><?php _e( 'E-Mail', 'wptouch' ); ?></label>
		</p>

		<textarea name="prowl-msg-message"></textarea>
		<input type="hidden" name="wptouch-prowl-message" value="1" /> 	
		<input type="submit" name="prowl-submit" value="<?php _e('Send Now', 'wptouch' ); ?>" id="prowl-submit" />
	 </form>
	<div class="clearer"></div>
 </div>

<!-- #start The Categories Drop Down List -->

	<div id="wptouch-cats" class="dropper" style="display:none">
            <ul>
	  	 	<?php bnc_get_ordered_cat_list(); ?>
            </ul>
	</div>

<!-- #start The Tags Drop Down List -->

	<div id="wptouch-tags" class="dropper" style="display:none">
			<?php wp_tag_cloud('smallest=13&largest=13&unit=px&number=30&order=asc&format=list'); ?>
	</div>

<!-- #start The Account Drop Down List -->
	<div id="wptouch-acct" class="dropper" style="display:none">
		<ul>
			<?php if (current_user_can('edit_posts')) : ?>
			<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/"><?php _e("Admin", "wptouch"); ?></a></li>
			<?php endif; ?>
			
			<?php if (get_option('comment_registration')) { ?>
			<li><a href="<?php bloginfo('wpurl'); ?>/wp-register.php"><?php _e( "Register for this site", "wptouch" ); ?></a></li>
			<?php } ?>
			<?php if (is_user_logged_in()) { ?>
			<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/profile.php"><?php _e( "Account Profile", "wptouch" ); ?></a></li>
			<li><a href="<?php $version = (float)get_bloginfo('version'); if ($version >= 2.7) { ?><?php echo wp_logout_url($_SERVER['REQUEST_URI']); } else { bloginfo('wpurl'); ?>/wp-login.php?action=logout&redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?><?php } ?>"><?php _e( "Logout", "wptouch" ); ?></a></li>
			<?php }  // End android stuff ?>
		</ul>
</div>