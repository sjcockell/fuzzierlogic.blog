<?php global $wptouch_settings; ?>

<div class="wptouch-itemrow newsblock">
	<div class="wptouch-item-desc">
		<h2><?php _e( "News &amp; Updates", "wptouch" ); ?></h2>
		<p><?php _e( "BraveNewCode blog entries tagged 'WPtouch'.", "wptouch" ); ?></p>
		<p><?php _e( "This list updates with the latest information about the plugin's development.", "wptouch" ); ?></p>
		<p class="callout"><strong><?php _e( "Interested in helping us internationalize WPtouch?" ); ?></strong><br /><br /><?php echo sprintf(__( "Send us an %se-mail%s telling us what language(s) you're fluent in.", "wptouch" ), '<a href="mailto:duane@bravenewcode.com">','</a>'); ?></p>
	</div>
		
	<div class="wptouch-item-content-box1">
		<div id="wptouch-news-frame" style="display: none;"></div>


		<script type="text/javascript">
			jQuery.ajax({
				url: "<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/load-news.php",
				success: function(data) {
					jQuery("#wptouch-news-frame").html(data).fadeIn();
				}});

			jQuery.ajax({
				url: "<?php bloginfo('wpurl'); ?>/wp-content/<?php echo wptouch_get_plugin_dir_name(); ?>/wptouch/load-news.php?donations=1",
				success: function(data) {
					jQuery("#wptouch-donation-frame").html(data).fadeIn();
				}});
		</script>


		
	</div>
	
   <div id="wptouch-news-donate">
	  <h3><?php _e( "Donate To WPtouch", "wptouch" ); ?><br /><?php _e( "And Help Us Stay Carbon Neutral!", "wptouch" ); ?></h3> 
	  
	  <?php echo sprintf( __( "WPtouch represents hundreds of hours of work. If you like the project and want to see it continue, %sconsider donating to WPtouch%s.", "wptouch"), '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=paypal%40bravenewcode%2ecom&amp;item_name=WPtouch%20Beer%20Fund&amp;no_shipping=1&amp;tax=0&amp;currency_code=CAD&amp;lc=CA&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8">', '</a>' ); ?><br />
	  <?php echo sprintf( __( "Everyone who donates is added to the donor listing on %sour website%s.", "wptouch"), '<a href="http://www.bravenewcode.com/wptouch">', '</a>' ); ?><br /><br />
	  
	  	 <strong> <?php echo sprintf( __( "We use every donation to pay for carbon offsets. %sFind out more%s", "wptouch"), '<a href="http://www.bravenewcode.com/environment" target="_blank">', '</a>' ); ?> &raquo;</strong>

	<!-- <h3><?php _e( "Last Donations", "wptouch" ); ?></h3>		
	<ul id="wptouch-donation-frame" style="display: none;"></ul> -->
   </div>
	
	<div class="wptouch-clearer"></div>
	<div class="donate-spacer"></div>
</div>
