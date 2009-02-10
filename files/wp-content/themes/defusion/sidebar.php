<div id="content-right">
		
		<?php // if there are subpages of the current page,they will be listed here
		
			if($post->post_parent)
			$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0&sort_column=menu_order"); else
			$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0&sort_column=menu_order");
			if ($children) { ?>

			<div class="box-right">
				
				<h4>Sub-Pages</h4>
			
				<ul>
					<?php echo $children; ?>
				</ul>
			
       		</div>
			
		<?php } ?>

<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar() ) : ?>
		
		<div class="box-right">
		
			<h4>Categories</h4>
		
			<ul>
				<?php wp_list_categories('title_li=&hierarchical=0'); ?>
			</ul>
		
		</div>

		<div class="box-right">

			<h4>Archive</h4>
		
			<ul>
				<?php wp_get_archives('type=monthly'); ?>
			</ul>
		
		</div>

		<div class="box-right">
			
			<h4>Links</h4>
			
			<ul>
				<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
			</ul>
		
		</div>

		<div class="box-right">

			<h4>Meta</h4>
		
			<ul>
            	<?php wp_register(); ?>
			    <li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		
		</div>

<?php endif; // endif widgets ?>
	  
</div><!-- end content-right -->