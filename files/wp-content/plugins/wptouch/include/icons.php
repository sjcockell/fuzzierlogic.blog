<?php

	function bnc_get_icon_locations() {
		global $wptouch_on_mu;
		global $blog_id;
        	$locations = array( 
        		'default' => '/wp-content/' . wptouch_get_plugin_dir_name() . '/wptouch/images/icon-pool/',        
			'custom' => '/wp-content/uploads/wptouch/custom-icons/'

        	);

	if ( $wptouch_on_mu ) {
		$locations['custom'] =  '/wp-content/blogs.dir/' . $blog_id . '/uploads/wptouch/custom-icons/';
	}
        
        return $locations;
	}

    function bnc_get_icon_list() {
        $a = preg_match('#(.*)wptouch.php#', __FILE__, $matches);
        $locations = bnc_get_icon_locations();
                
        $files = array();
        $root_locations = explode( 'wp-content', __FILE__);
        $wordpress_root = $root_locations[0];
        
        foreach ( $locations as $name => $location ) {
        	$current_path = $wordpress_root . $location;
	   	    $dir = @opendir( $current_path );
	   	    $files[ $name ] = array();

			if ( $dir ) {
				while ( false !== ( $file = readdir( $dir ) ) ) { 
					if ($file == '.' || $file == '..' || $file == '.svn' || $file == 'template.psd' || $file == '.DS_Store' || $file == 'more') {
						continue;
					}
					
					$icon = array();
					$names = explode('.', $file);
					$icon['friendly'] = ucfirst($names[0]);
					$icon['name'] = $file;
					$icon['url'] = get_bloginfo('wpurl') . $location . $file;
					$files[ $name ][ $icon['name'] ] = $icon;
				}
			}
        }
     
     	ksort($files);
     	return $files;
	}
	
	function bnc_show_icons() {
		$icons = bnc_get_icon_list();
		$locations = bnc_get_icon_locations();
		
		foreach ( $locations as $name => $location ) {
			echo '<div class="new-icon-block ' . $name . '">';
			foreach ( $icons[ $name ] as $icon ) {
				echo '<ul class="wptouch-iconblock">';
				echo '<li><img src="' . $icon['url'] . '" title="' . $icon['name'] . '" /><br />' . $icon['friendly'];
				if ( $name == 'custom' ) {
					echo ' <a href="' . $_SERVER['REQUEST_URI'] . '&delete_icon=' . urlencode($icon['url']) . '">(x)</a>';	
				}
				echo '</li>';
				echo '</ul>';
			}	
			echo '</div>';
		}
	}	
	
	function bnc_get_icon_drop_down_list( $selected_item ) {
		$icons = bnc_get_icon_list();
		$locations = bnc_get_icon_locations();
		$files = array();
		
		foreach ( $locations as $name => $location ) {
			foreach ( $icons[$name] as $icon) {
				$files[$icon['name']] = $icon;
			}	
		}
		
		ksort( $files );
		
		foreach ( $files as $key => $file ) {
			$is_selected = '';
			if ( $selected_item == $file['name'] ) {
				$is_selected = ' selected';
			}
			echo '<option' . $is_selected . ' value="' . $file['name'] . '">'. $file['friendly'] . '</option>';
		}
	}
	
	function bnc_get_pages_for_icons() {
		global $table_prefix;
		global $wpdb;
		
		$query = "select * from {$table_prefix}posts where post_type = 'page' and post_status = 'publish' order by post_title asc";
		$results = $wpdb->get_results( $query );
		if ( $results ) {
			return $results;
		}
	}
	
	function bnc_get_master_icon_list() {
	}
	
?>
