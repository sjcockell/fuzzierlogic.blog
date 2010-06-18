<?php get_header(); ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="post alt">
					<div class="post-title" id="post-<?php the_ID(); ?>">
                 		<h3><?php the_title(); ?></h3>
					</div><!-- end "post-title" -->
					<div class="post-entry">
						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					</div><!-- end "post-entry" -->
					<div class="post-footer">
						<span class="post-edit"><?php edit_post_link('Edit this entry.','',''); ?></span>
					</div><!-- end "post-footer" -->
             	</div><!-- end "post alt" -->
			</div><!-- end "top primary" -->	
		</div><!-- end "wrapper-top" --> 
		<?php endwhile; endif; ?>
        

			<div id="top-secondary"><?php get_sidebar(); ?></div>
				<div class="clear"></div>
			</div><!-- end "top-secondary" --> 
		</div><!-- end "page-top" --> 
		
		<div id="page-bottom">
		<div id="wrapper-bottom">
		<div id="bottom-primary"> 		
				
			<?php get_footer(); ?>