<?php require_once( ABSPATH . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/include/icons.php' ); ?>
<?php global $wptouch_settings; ?>

<div class="wptouch-itemrow" id="available_icons">
	<div class="wptouch-item-desc">		
		<h2><?php _e( "Available Icons", "wptouch" ); ?></h2>
			
		<p><?php _e( "You can select which icons will be displayed beside pages enabled below", "wptouch" ); ?></p>
		<p><?php _e( "To add icons to the pool, simply upload a 60x60 .png, .jpeg or .gif from your computer.", "wptouch" ); ?></p>
		<p><?php echo sprintf( __( "These files will be stored in %swp-content/uploads/wptouch/custom-icons/%s.", "wptouch"), "<strong>", "</strong>" ); ?></p>
				
		<div id="upload_response"></div>
		<div id="upload_progress" style="display: none;">
			<p><img src="<?php echo get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/progress.gif'; ?>" alt="" /></p>
		</div>
		<script type="text/javascript">
			$j = jQuery.noConflict();
			$j(document).ready(function(){
				new Ajax_upload('#upload_button', {
					action: '<?php echo get_bloginfo('wpurl') . "/wp-content/" . wptouch_get_plugin_dir_name() . "/wptouch/ajax/file_upload.php"; ?>',
					autoSubmit: true,
					name: 'submitted_file',
					onSubmit: function(file, extension) { $j = jQuery.noConflict(); $j("#upload_progress").show(); },
					onComplete: function(file, response) { $j = jQuery.noConflict(); $j("#upload_progress").hide();
					$j('#upload_response').hide().html(response).fadeIn(); }
				});
			$j("a.wptouch-fancy").fancybox({
				'padding':						8,
				'imageScale':					true,
				'zoomSpeedIn':				300, 
				'zoomSpeedOut':			300,
				'zoomOpacity':				true, 
				'overlayShow':				false,
				'frameHeight':				250,
				'hideOnContentClick': 	false
			});
		});
		</script>
			
		<div id="upload_button"></div> 
			
		<p><?php _e( "Need help getting started?", "wptouch" ); ?></p>
		<p><?php echo sprintf(__( 'Download our %s Photoshop template%s which you can use to build custom icons WPtouch style.', 'wptouch'), '<strong><a href="' . get_bloginfo('wpurl') . '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/icon-pool/template.psd">', '</a></strong>' ); ?></p>
	</div><!-- .wptouch-item-desc -->
		
	<div class="wptouch-item-content-box1">	
		<?php bnc_show_icons(); ?>
	</div>
	
	<div class="wptouch-clearer"></div>
</div><!-- #available_icons -->

<div class="wptouch-itemrow">
	<div class="wptouch-item-desc">
		<h2><?php _e( "Logo/Bookmark", "wptouch" ); ?><br /><?php _e( "Page &amp; Menu Icons", "wptouch" ); ?></h2>
		
		<p><?php _e( "Choose the logo displayed in the header (also your bookmark icon), and the pages you want included in the WPtouch drop-down menu.", "wptouch" ); ?> <strong><?php _e( "Remember, only those checked will be shown.", "wptouch" ); ?></strong></p>
		
		<p><?php _e( "Next, select the icons from the drop lists that you want to pair with each page/menu item.", "wptouch" ); ?></p>
		
		<p><?php _e( "Lastly, you can decide if pages are listed by the page order in WordPress, or by name (default).", "wptouch" ); ?></p>
	</div><!-- .wptouch-item-desc-->
		
	<div class="wptouch-item-content-box1">
		
		<div class="wptouch-select-row" id="pages-sort-order">
			<div class="wptouch-select-left">	
				<?php _e( "Menu List Sort Order", "wptouch" ); ?>
			</div>
			<div class="wptouch-select-right sort-order">
				<select name="sort-order">
					<option value="name"<?php if ( $wptouch_settings['sort-order'] == 'name') echo " selected"; ?>><?php _e( "By Name", "wptouch" ); ?></option>
					<option value="page"<?php if ( $wptouch_settings['sort-order'] == 'page') echo " selected"; ?>><?php _e( "By Page ID", "wptouch" ); ?></option>
				</select>
			</div>
		</div>

		<div class="wptouch-select-row">
			<div class="wptouch-select-left">
				<?php _e( "Logo &amp; Home Screen Bookmark Icon", "wptouch" ); ?>
			</div>
			<div class="wptouch-select-right">
				<select name="enable_main_title">
					<?php bnc_get_icon_drop_down_list( $wptouch_settings['main_title']); ?>
				</select>
			</div>
		</div>
		
		<?php $pages = bnc_get_pages_for_icons(); ?>
		<?php foreach ( $pages as $page ) { ?>
		<div class="wptouch-select-row">
			<div class="wptouch-select-left">
				<input type="checkbox" name="enable_<?php echo $page->ID; ?>"<?php if ( isset( $wptouch_settings[$page->ID] ) ) echo " checked"; ?> />
				<label for="enable_<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></label>
			</div>
			<div class="wptouch-select-right">
				<select name="icon_<?php echo $page->ID; ?>">
					<?php bnc_get_icon_drop_down_list( $wptouch_settings[ $page->ID ]); ?>
				</select>
			</div>
		</div>
		<?php } ?>		
	<div class="wptouch-clearer"></div>
	
	</div><!-- .wptouch-item-content-box1 -->
</div><!-- .wptouch-itemrow -->
