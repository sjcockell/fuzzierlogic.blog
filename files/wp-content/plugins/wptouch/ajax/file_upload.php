<?php
	$cur_dir = dirname(__FILE__);
	$loc = explode( 'wp-content', $cur_dir );
	$max_size = 128*1024; // 128k
	global $wptouch_on_mu;
	global $blog_id;
	
	require_once( $loc[0] . '/wp-config.php' );
	
	$directory_list = array();
	
	if ( current_user_can( 'upload_files' ) ) {
		if ( !$wptouch_on_mu ) {
			$directory_list[] = '/wp-content/uploads/wptouch/';
			$directory_list[] = '/wp-content/uploads/wptouch/custom-icons/'; 
			
			$wptouch_dir = $loc[0] . '/wp-content/uploads/wptouch/';
			$upload_dir = $loc[0] . '/wp-content/uploads/wptouch/custom-icons/'; 
		} else { 
			$directory_list[] = '/wp-content/blogs.dir/'; 
			$directory_list[] = '/wp-content/blogs.dir/' . $blog_id . '/';
			$directory_list[] = '/wp-content/blogs.dir/' . $blog_id . '/uploads/';
			$directory_list[] = '/wp-content/blogs.dir/' . $blog_id . '/uploads/wptouch/';
			$directory_list[] = '/wp-content/blogs.dir/' . $blog_id . '/uploads/wptouch/custom-icons/';
			
			$wptouch_dir = $loc[0] . '/wp-content/blogs.dir/' . $blog_id . '/uploads/wptouch/';
			$upload_dir = $loc[0] . '/wp-content/blogs.dir/' . $blog_id . '/uploads/wptouch/custom-icons/'; 
		}
		
		// create directories
		foreach ( $directory_list as $dir ) {
			if ( !file_exists( $dir ) ) {
				@mkdir( $dir, 0755, true ); 
			}
		}

		/*
		if ( !file_exists( $wptouch_dir )) {
			@mkdir( $wptouch_dir, 0755, true ); 
		}
		
		if ( !file_exists( $upload_dir )) {
			@mkdir( $upload_dir, 0755, true ); 
		}		
		*/
		
		if ( isset( $_FILES['submitted_file'] ) ) {
			$f = $_FILES['submitted_file'];
			if ( $f['size'] <= $max_size) {
				if ( $f['type'] == 'image/png' || $f['type'] == 'image/jpeg' || $f['type'] == 'image/gif' || $f['type'] == 'image/x-png' || $f['type'] == 'image/pjpeg' ) {	
					@move_uploaded_file( $f['tmp_name'], $upload_dir . $f['name'] );
					
					if ( !file_exists( $upload_dir . $f['name'] ) ) {
						echo 'There seems to have been an error.  Please try your upload again.';
					} else {
						echo 'File has been saved! <br />Click <a href="#" style="color:red" onclick="location.reload(true); return false;">here to refresh the page</a>.<br /><br />';						
					}					
				} else {
					echo __( 'Sorry, only PNG, GIF and JPEG images are supported.', 'wptouch' );
				}
			} else echo __( 'Image too large', 'wptouch' );
		}

	} else echo __( 'Insufficient priviledges', 'wptouch' );
?>
