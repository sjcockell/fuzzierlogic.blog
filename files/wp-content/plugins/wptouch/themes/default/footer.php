<!-- Here the switch code is very important, as well as the php code which deals with admin links and WordPress -->
	<div id="footer">
		<center><h3><?php if (current_user_can('edit_posts')) : // If it's not an admin don't show these! ?>      
				<a href="<?php bloginfo('wpurl'); ?>/wp-admin/"><?php _e("Admin", "wptouch"); ?></a> | 
					<?php //Another WordPress version check to figure out the correct logout method
						$version = (float)get_bloginfo('version'); 
						if ($version >= 2.7) { ?>
							<a href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>">
						<?php } else { ?>
							<a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=logout&redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>">
						<?php } ?><?php _e( "Logout", "wptouch" ); ?></a>
				<?php elseif (current_user_can('read_posts')) : ?>
					<a href="<?php bloginfo('wpurl'); ?>/wp-admin/profile.php"><?php _e( "Account Profile", "wptouch" ); ?></a><?php if (!bnc_is_login_button_enabled()) { ?> | <a href="<?php echo wp_logout_url(); ?>"><?php _e( "Logout", "wptouch" ); ?></a><?php } ?>
				<?php else : ?>
					<?php if (!bnc_is_login_button_enabled() && get_option('comment_registration') && !$user_ID) { ?>
						<a href="<?php bloginfo('wpurl'); ?>/wp-login.php"><?php _e( "Login to", "wptouch" ); ?> <?php bloginfo('name'); ?></a> | 
					<?php } ?>
					
					<?php if (get_option('comment_registration')) { ?>
						<a href="<?php bloginfo('wpurl'); ?>/wp-register.php"><?php _e( "Register for this site", "wptouch" ); ?></a>
					<?php } ?>
				<?php  endif; ?></h3></center>

			<div id="wptouch-switch-link"><?php _e( "Mobile Theme", "wptouch" ); ?><a onclick="javascript:document.getElementById('switch-on').style.display='none';javascript:document.getElementById('switch-off').style.display='block';" href="<?php echo bloginfo('home') . '/?bnc_view=normal'; ?>"><img id="switch-on" src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/on.jpg" alt="on switch image" class="wptouch-switch-image" /><img id="switch-off" style="display:none" src="<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/images/off.jpg" alt="off switch image" class="wptouch-switch-image" /></a></div>	

			<?php _e( "All content Copyright &copy;", "wptouch" ); ?> <?php bloginfo('name'); ?><br />
			<?php _e( 'Powered by <a href="http://wordpress.org/">WordPress</a> with', 'wptouch' ); ?> <a href="http://bravenewcode.com/wptouch/"><?php WPtouch(); ?></a>
	<?php if ( !bnc_wptouch_is_exclusive() ) { ?>
		<?php wp_footer(); ?>
	<?php } ?>
	</div>
<br />
<?php wptouch_get_stats(); 
//WPtouch theme designed and developed by Dale Mugford and Duane Storey for BraveNewCode.com
//Licensed under GPL
//If you modify it, please keep the link credit *visible* in the footer (and keep the WordPress credit, too!), that's all we ask, folks.
?>
</body>
</html>
