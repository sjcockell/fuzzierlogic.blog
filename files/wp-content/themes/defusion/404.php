<?php get_header(); ?>

	<div id="content">
	
		<div id="content-left">
	
			<div class="box-left" id="searchform">
			
				<h3>Not found!</h3>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
				<?php include (TEMPLATEPATH . "/searchform.php"); ?>
			
			<div class="clear"></div></div>
	
	  </div><!-- end content-left -->
	  
	  <?php get_sidebar(); ?>
	  
	  <div class="clear"></div>
	
</div><!-- end content -->
	
<?php get_footer(); ?>