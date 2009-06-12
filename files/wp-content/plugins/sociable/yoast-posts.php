<?php 

if (!class_exists('YoastPosts')) {
	class YoastPosts {

		// Class initialization
		function YoastPosts() {
			if (isset($_GET['show_yoast_widget'])) {
				if ($_GET['show_yoast_widget'] == "true") {
					update_option( 'show_yoast_widget', 'noshow' );
				} else {
					update_option( 'show_yoast_widget', 'show' );
				}
			} 
		
			// Add the widget to the dashboard
			add_action( 'wp_dashboard_setup', array(&$this, 'register_widget') );
			add_filter( 'wp_dashboard_widgets', array(&$this, 'add_widget') );
		}

		// Register this widget -- we use a hook/function to make the widget a dashboard-only widget
		function register_widget() {
			wp_register_sidebar_widget( 'yoast_posts', __( 'Yoast - Tweaking Websites', 'yoast-posts' ), array(&$this, 'widget'), array( 'all_link' => 'http://yoast.com/', 'feed_link' => 'http://yoast.com/feed/', 'edit_link' => 'options.php' ) );
		}

		// Modifies the array of dashboard widgets and adds this plugin's
		function add_widget( $widgets ) {
			global $wp_registered_widgets;
			if ( !isset($wp_registered_widgets['yoast_posts']) ) return $widgets;
			array_splice( $widgets, 2, 0, 'yoast_posts' );
			return $widgets;
		}

		function widget($args = array()) {
			$show = get_option('show_yoast_widget');
			if ($show != 'noshow') {
				if (is_array($args))
					extract( $args, EXTR_SKIP );
				echo $before_widget.$before_title.$widget_name.$after_title;
				echo '<a href="http://yoast.com/"><img style="margin: 0 0 5px 5px;" src="http://yoast.com/images/yoast-logo-rss.png" align="right" alt="Yoast"/></a>';
				include_once(ABSPATH . WPINC . '/rss.php');
				$rss = fetch_rss('http://feeds2.feedburner.com/joostdevalk');
				if ($rss) {
					$items = array_slice($rss->items, 0, 2);
					if (empty($items)) 
						echo '<li>No items</li>';
					else {
						foreach ( $items as $item ) { ?>
						<a style="font-size: 14px; font-weight:bold;" href='<?php echo $item['link']; ?>' title='<?php echo $item['title']; ?>'><?php echo $item['title']; ?></a><br/> 
						<p style="font-size: 10px; color: #aaa;"><?php echo date('j F Y',strtotime($item['pubdate'])); ?></p>
						<p><?php echo substr($item['summary'],0,strpos($item['summary'], "This is a post from")); ?></p>
						<?php }
					}
				}
				echo $after_widget;
			}
		}
	}

	// Start this plugin once all other plugins are fully loaded
	add_action( 'plugins_loaded', create_function( '', 'global $YoastPosts; $YoastPosts = new YoastPosts();' ) );
}
?>