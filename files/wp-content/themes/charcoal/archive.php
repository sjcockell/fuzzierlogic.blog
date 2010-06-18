<?php get_header(); ?>
		<div class="main-posts">
			<div class="post multi">
				<?php if (have_posts()) : ?>

	 			  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			 	  <?php /* If this is a category archive */ if (is_category()) { ?>
					<h3 class="pagetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h3>
		 		  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
					<h3 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h3>
		 		  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
					<h3 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h3>
			 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
					<h3 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h3>
		 		  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
					<h3 class="pagetitle">Archive for <?php the_time('Y'); ?></h3>
				  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
					<h3 class="pagetitle">Author Archive</h3>
		 		  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					<h3 class="pagetitle">Blog Archives</h3>
		 		  <?php } ?>


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
								<?php the_content('Read the rest of this entry &raquo;'); ?><br />
							</div><!-- end "post-entry" -->
					<?php endwhile; ?>
							
					<?php else : ?>

						<h3 class="center">Not Found</h3>
						<p class="center">Sorry, but you are looking for something that isn't here.</p>
					<?php include (TEMPLATEPATH . "/searchform.php"); ?>

					<?php endif; ?>

			</div><!-- end "post multi"-->	
		</div><!-- end "main-posts" --> 
	</div><!-- end "top-primary" --> 
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
					</span><br class="clear"/>				
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