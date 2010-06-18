<?php get_header();?>
         <div class="main_posts">
	
				<div class="post">
			<div class="post-tags"><?php the_tags( '<p>Tags: ', ', ', '</p>'); ?></div>
			<div class="post-title">
			<h3>404 - Not Found</h3></div> <!-- end "post-title" -->
				<div class="post-sup"></div>
					<div class="post-entry">
						<p>It appears you are looking for something that isn't there...
					<div class="post-footer">
					</div>
				</div>

			</div>
		</div>

		</div><!-- end "top-primary" --> 
		</div><!-- end "wrapper-top" --> 
			<div id="top-secondary"><?php get_sidebar(); ?></div>
				<div class="clear"></div>
			</div><!-- end "top-secondary" --> 
		</div><!-- end "main-posts" --> 
		</div><!-- end "page-top" --> 

		<div id="page-bottom">
		<div id="wrapper-bottom">
		<div id="bottom-primary">
				<div id="prev-posts-footer">
					<div id="archives">
						<ul class="archlist-left">
							<?php if (function_exists('compact_archive')) { /*if we have the compact archives plugin installed, we display it. If not we do the default <li> monthly archives */
							compact_archive($style='block'); } 
							else { wp_get_archives('type=monthly'); } /* we do that here. Comment out the lines between the <div> tags to show nothing at all, or replace it with your own template tag. */ ?>
						</ul>
					</div><!-- end "archives" --> 
	<?php get_footer(); ?>