<?php get_header();?>
         
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<div class="post-nav">
							<div class="left">
								<?php previous_post_link('&laquo; %link') ?>
							</div><!-- end "left" -->
							<div class="right">
								<?php next_post_link('%link &raquo;') ?>
							</div><!-- end "right" --> 
							<div class="clear"/></div>
						</div><!-- end "post-nav" -->
							<div class="post">
								<div class="post-tags"><?php the_tags('<p>', ', ', '</p>'); ?></div>
									<div class="post-title" id="post-<?php the_ID(); ?>">
										<h3><?php the_title(); ?></h3>
									</div> <!-- end "post-title" -->
								<div class="post-sup">
									<span class="post-date"><?php the_time('l, F jS, Y') ?> - <?php the_time() ?> - <?php the_category(', ') ?></span>
								</div><!-- end "post-sup" -->
								<div class="post-entry">
									<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?><br />
									<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
								<div class="post-footer">
									<span class="post-edit"><?php edit_post_link('Edit this entry.','',''); ?></span>
								</div><!-- end "post-footer" -->
				<?php endwhile; ?>
				<?php else : ?>
					<h3 class="center">Not Found</h3>
						<p class="center">Sorry, but you are looking for something that isn't here.</p>
						<?php include (TEMPLATEPATH . "/searchform.php"); ?>
				<?php endif; ?>

					</div><!-- end "post-entry" -->
			</div><!-- end "post" -->
		</div><!-- end "top-primary" --> 
	</div><!-- end "wrapper-top" --> 
			<div id="top-secondary"><?php get_sidebar(); ?></div>
				<div class="clear"></div>
			</div><!-- end "top-secondary" --> 


		<div id="page-bottom">
		<div id="wrapper-bottom">
		<div id="bottom-primary">
					<div id="post-comments">
						<?php comments_template(); ?>
					</div>
				<div id="prev-posts-footer">
					<span class="nav-prev">
						<?php previous_post_link('%link') ?>
					</span>		
					<span class="nav-next">
						<?php next_post_link('%link') ?>
					</span><br class="clear"/>
				</div><!-- end "prev-posts-footer" --> 
					<div id="archives">
						<ul class="archlist-left">
							<?php if (function_exists('compact_archive')) { /*if we have the compact archives plugin installed, we display it. If not we do the default <li> monthly archives */
							compact_archive($style='block'); } 
							else { wp_get_archives('type=monthly'); } /* we do that here. Comment out the lines between the <div> tags to show nothing at all, or replace it with your own template tag. */ ?>
						</ul>
					</div><!-- end "archives" --> 

		<?php get_footer(); ?>