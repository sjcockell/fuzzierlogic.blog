<!-- Here we're establishing whether the page was loaded via Ajax or not, for dynamic purposes. 
If it's ajax, we're not bringing in header.php and footer.php -->

<?php $wptouch_settings = bnc_wptouch_get_settings(); ?>


<?php global $is_ajax; $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']); if (!$is_ajax) get_header(); ?>
	<div class="content" id="content<?php echo md5($_SERVER['REQUEST_URI']); ?>">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post">
			    <a class="sh2" href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( "Permanent Link to ", "wptouch" ); ?><?php if (function_exists('the_title_attribute')) the_title_attribute(); else the_title(); ?>"><?php the_title(); ?></a>
			        <div class="single-post-meta-top"><?php echo get_the_time('M jS @ h:i a') ?> &rsaquo; <?php the_author() ?><br />

<!-- Let's check for DISQUS... we need to skip to a different div if it's installed and active -->		
	
	<?php if (function_exists('dsq_comments_template')) { ?>
 		<a href="#dsq-add-new-comment">&darr; <?php _e( "Skip to comments", "wptouch" ); ?></a></div>
			<?php } else { ?>
   	    <a href="#comments">&darr; <?php _e( "Skip to comments", "wptouch" ); ?></a></div>
	<?php } ?>	
		<div class="clearer"></div>
	</div>

         <div class="post singlecut" id="post-<?php the_ID(); ?>">
         	<div id="singlentry" class="<?php echo $wptouch_settings['style-text-size']; ?> <?php echo $wptouch_settings['style-text-justify']; ?>">
            	<?php the_content(); ?>				
			</div>  
			
<!-- Categories and Tags post footer -->        

			<div class="single-post-meta-bottom">
				<?php link_pages('<div class="post-page-nav">' . __( "Article Pages", "wptouch" ) . ': ', '</div>', 'number', ' &raquo;', '&laquo; '); ?>          
			    <?php _e( "Categories", "wptouch" ); ?>: <?php if (the_category(', ')) the_category(); ?>
			    <?php if (function_exists('get_the_tags')) the_tags('<br />' . __( 'Tags', 'wptouch' ) . ': ', ', ', ''); ?>  
		    </div>
    
<!-- Mail and Bookmark code -->	

	<div class="single-links">
		<div class="single-bookmark-right"><?php if (bnc_is_js_enabled()) { ?><a href="javascript:$wptouch('#bookmark-box').slideToggle(300);"><?php } else { ?><a href="javascript:document.getElementById('bookmark-box').style.display = 'block';"><?php } ?><img src="<?php bloginfo('template_directory'); ?>/images/bookmarkit.png" class="small" alt="" /> <?php _e( "Bookmark It", "wptouch" ); ?></a></div>
			<div class="single-mail-left"><a href="mailto:?subject=<?php
bloginfo('name'); ?>- <?php the_title();?>&body=<?php _e( "Check out this post:", "wptouch" ); ?> <?php the_permalink() ?>"><img src="<?php bloginfo('template_directory'); ?>/images/mailit.png" class="small" alt="" /> <?php _e( "Mail It", "wptouch" ); ?></a></div>
				<div class="clearer"></div>
			</div>
		<div class="post-spacer"></div>
	</div>

<!-- Hidden bookmark box code (activated by the above link) -->

	<div id="bookmark-box" style="display:none">
		<ul>
			<li><a  href="http://del.icio.us/post?url=<?php echo get_permalink()
?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/bookmarks/delicious.jpg" alt="" /> <?php _e( "Del.icio.us", "wptouch" ); ?></a></li>
			<li><a href="http://digg.com/submit?phase=2&url=<?php echo get_permalink()
?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/bookmarks/digg.jpg" alt="" /> <?php _e( "Digg", "wptouch" ); ?></a></li>
			<li><a href="http://technorati.com/faves?add=<?php the_permalink() ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/bookmarks/technorati.jpg" alt="" /> <?php _e( "Technorati", "wptouch" ); ?></a></li>
			<li><a href="http://ma.gnolia.com/bookmarklet/add?url=<?php echo get_permalink() ?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/bookmarks/magnolia.jpg" alt="" /> <?php _e( "Magnolia", "wptouch" ); ?></a></li>
			<li><a href="http://www.newsvine.com/_wine/save?popoff=0&u=<?php echo get_permalink() ?>&h=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/bookmarks/newsvine.jpg" target="_blank"> <?php _e( "Newsvine", "wptouch" ); ?></a></li>
			<li class="noborder"><a href="http://reddit.com/submit?url=<?php echo get_permalink() ?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/bookmarks/reddit.jpg" alt="" /> <?php _e( "Reddit", "wptouch" ); ?></a></li>
		</ul>
	</div>

	<div class="navigation">

<!-- Single post navigation links -->

		<div class="alignleft"><?php next_post_link('<img src="' . get_bloginfo('template_directory') . '/images/blue_arrow_l.jpg" alt="" /> %link', 'Prev Post') ?></div>
		<div class="alignright"><?php previous_post_link('%link <img src="' . get_bloginfo('template_directory') . '/images/blue_arrow_r.jpg" alt="" />', 'Next Post') ?></div>

<!-- Let's make sure there's no float strangeness happening. Sometimes plugins get funky here. -->
		<div class="clearer"></div>
	</div>

<!-- Let's rock the comments -->

	<?php comments_template(); ?>

	<?php endwhile; else : ?>

<!-- Dynamic test for what page this is. A little redundant, but so what? -->

	<?php global $is_ajax; if (($is_ajax) && !is_search()) { ?>
		<div class="result-text"><?php _e( "No more entries to display.", "wptouch" ); ?></div>
			<?php } elseif (is_search() && ($is_ajax)) { ?>
		<div class="result-text"><?php _e( "No more search results to display.", "wptouch" ); ?></div>
			<?php } elseif (is_search()) { ?>
		<div class="result-text"><?php _e( "No search results results found. Try another query.", "wptouch" ); ?></div>
			<?php } else { ?>
		<div class="post"><img src="<?php bloginfo('template_directory'); ?>/images/404.jpg" alt="404 Not Found" /></div>
			<?php } ?>
	<?php endif; ?>
		</div>
	
	<!-- Do the footer things -->
	
<?php global $is_ajax; if (!$is_ajax) get_footer(); ?>
