<?php get_header(); ?>
				<div class="post multi">
						<?php if (have_posts()) : ?>
							<h3 class="pagetitle">Search Results</h3>
								<?php while (have_posts()) : the_post(); ?>
							<div class="post-title" id="post-<?php the_ID(); ?>">
								<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							</div> <!-- end "post-title" -->
						
							<div class="post-sup"><span class="post-date">
								<?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> -->
								</span><!--<span class="post-tags"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?></span> -->
								<span class="post-comments-link"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span>
								<span class="clear"></span>
							</div> <!-- end "post-sup" -->
						
							<div class="post-entry">
								<?php the_content('Read the rest of this entry &raquo;'); ?>
							</div><!-- end "post-entry" -->
		
						<?php endwhile; ?>
					
						<?php else : ?>

							<h3 class="center">Not Found</h3>
							<p class="center">Sorry, but you are looking for something that isn't here.</p>
						<?php include (TEMPLATEPATH . "/searchform.php"); ?>

						<?php endif; ?>
				</div><!-- end "post multi"-->	
			</div><!-- end "top primary" -->		
		</div><!-- end "wrapper-top" --> 

			<div id="top-secondary"><?php get_sidebar(); ?></div>
				<div class="clear"></div>
			</div><!-- end "top-secondary" --> 
		</div><!-- end "page-top" --> 

		<div id="page-bottom">
		<div id="wrapper-bottom">
		<div id="bottom-primary">
				<div id="prev-posts-footer">
				<span class="nav-prev">
				<?php next_posts_link('&laquo; Older Entries') ?>
				</span>		
				<span class="nav-next">
				<?php previous_posts_link('Newer Entries &raquo;') ?>
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
