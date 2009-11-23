<?php 
include( dirname(__FILE__) . '/../core/core-header.php' ); 
// End WPtouch Core Header
?>
<body class="<?php wptouch_core_body_background(); ?>">
<!-- New noscript check, we need js on now folks -->
<noscript>
<div id="noscript-wrap">
	<div id="noscript">
		<h2><?php _e("Notice", "wptouch"); ?></h2>
		<p><?php _e("JavaScript for Mobile Safari is currently turned off.", "wptouch"); ?></p>
		<p><?php _e("Turn it on in ", "wptouch"); ?><em><?php _e("Settings &rsaquo; Safari", "wptouch"); ?></em><br /><?php _e(" to view this website.", "wptouch"); ?></p>
	</div>
</div>
</noscript>
<!-- Prowl: if DM is sent, let's tell the user what happened -->
<?php if (bnc_prowl_did_try_message()) { if (bnc_prowl_message_success()) { ?>
<div id="prowl-success"><p><?php _e("Your Push Notification was sent.", "wptouch"); ?></p></div>
	<?php } else { ?>
<div id="prowl-fail"><p><?php _e("Your Push Notification cannot be delivered at this time.", "wptouch"); ?></p></div>
<?php } } ?>

<!--#start The Login Overlay -->
	<div id="wptouch-login">
		<div id="wptouch-login-inner">
			<form name="loginform" id="loginform" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
				<label><input type="text" name="log" id="log" onfocus="if (this.value == 'username') {this.value = ''}" value="username" /></label>
				<label><input type="password" name="pwd"  onfocus="if (this.value == 'password') {this.value = ''}" id="pwd" value="password" /></label>
				<input type="hidden" name="rememberme" value="forever" />
				<input type="hidden" id="logsub" name="submit" value="<?php _e('Login'); ?>" tabindex="9" />
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
			</form>
		</div>
	</div>
	
<div id="headerbar">
	<div id="headerbar-title">
		<!-- This fetches the admin selection logo icon for the header, which is also the bookmark icon -->
		<img src="<?php echo bnc_get_title_image(); ?>" alt="<?php $str = bnc_get_header_title(); echo stripslashes($str); ?>" />
		<a href="<?php bloginfo('home'); ?>"><?php wptouch_core_body_sitetitle(); ?></a>
	</div>
	<div id="headerbar-menu">
		    <a href="#" onclick="bnc_jquery_menu_drop(); return false;"></a>
	</div>
</div>

<!-- #start The Search / Menu Drop-Down -->
	<div id="wptouch-menu" class="dropper"> 
 		<div id="wptouch-search-inner">
			<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
				<input type="text" value="Search..." onfocus="if (this.value == 'Search...') {this.value = ''}" name="s" id="s" /> 
				<input name="submit" type="hidden" tabindex="5" value="Search"  />
			</form>
		</div>
        <div id="wptouch-menu-inner">
			<ul>
				<?php wptouch_core_header_home(); ?>            
				<?php wptouch_core_header_pages(); ?>
				<?php wptouch_core_header_rss(); ?>
				<?php wptouch_core_header_email(); ?>           
			</ul>
        </div>
	</div>

<div id="drop-fade" <?php if (bnc_is_login_button_enabled() || bnc_is_tags_button_enabled() || bnc_is_cats_button_enabled()) { echo 'class="sub-on"'; }?>>
	<!-- Detect if we're Apple-based and if not, don't do the fancy select thing -->
	<?php wptouch_core_subheader(); ?>
</div>
 		
<!-- #start the wptouch plugin use check -->
<?php wptouch_core_header_check_use(); ?>