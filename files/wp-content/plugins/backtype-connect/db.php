<?php
/**
 * Toggle comments due to changed ignore setting
 *
 * @global	object	$wpdb	DB object
 *
 * @param	bool	$btc_ignore_own_blog	The value we are toggling to
 * @param	array	$comment_IDs		Collection of comment IDs
 * @return	bool				True when successful; false otherwise
 */
function btc_db_update_ignored($btc_ignore, $comment_IDs) {
	global $wpdb;
	foreach ($comment_IDs as $comment_ID) {
		if ($btc_ignore) {
			$query = 'UPDATE ' . $wpdb->prefix . 'comments ' .
					'SET comment_approved=IF(SUBSTR(comment_approved,1,2)=\'d_\',\'d_ignored\',\'ignored\') ' .
					'WHERE comment_ID=%s';
		} else {
			$comment_approved = (get_option(BTC_MODERATION_OPTION)) ? '0' : '1';
			$query = 'UPDATE ' . $wpdb->prefix . 'comments ' .
					'SET comment_approved=IF(SUBSTR(comment_approved,1,2)=\'d_\',\''.$wpdb->escape('d_'.$comment_approved).'\',\''.$wpdb->escape($comment_approved).'\') ' .
					'WHERE comment_ID=%s';
		}
		$wpdb->query($wpdb->prepare($query, $comment_ID));
	}
}

/**
 * Toggle comments due to changed source filters
 *
 * @global	object	$wpdb	DB object
 *
 * @param	string	$comment_type	The value we are toggling on
 * @param	bool	$toggle		The toggle
 */
function btc_db_update_filtered($comment_type, $toggle) {
	global $wpdb;
	$query = 'SELECT meta_value ' .
			'FROM ' . $wpdb->prefix . 'postmeta ' .
			'WHERE meta_key=%s';
	$comment_IDs = $wpdb->get_col($wpdb->prepare($query, $comment_type));
	foreach ($comment_IDs as $comment_ID) {
		if ($toggle) {
			$query = 'UPDATE ' . $wpdb->prefix . 'comments ' .
					'SET comment_approved=SUBSTR(comment_approved,3) ' .
					'WHERE comment_ID=%s AND comment_agent=%s AND SUBSTR(comment_approved,1,2)=\'d_\'';
			$wpdb->query($wpdb->prepare($query, $comment_ID, $comment_type));
		} else {
			$query = 'UPDATE ' . $wpdb->prefix . 'comments ' .
					'SET comment_approved=CONCAT(\'d_\',comment_approved) ' .
					'WHERE comment_ID=%s AND comment_agent=%s';
			$wpdb->query($wpdb->prepare($query, $comment_ID, $comment_type));
		}
	}
}

/**
 * Delete all BTC comments
 *
 * @global	object	$wpdb	DB object
 *
 * @return	bool		True when successful; false otherwise
 */
function btc_db_clear_comments() {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'comments ' .
			'WHERE SUBSTR(comment_agent,1,4)=\'btc_\'';
	return $wpdb->query($query);
}

/**
 * Retrieve BTC comment counts for a post
 *
 * @param	int	$ID		The post ID you want comment counts for
 * @param	array	$filters	The source filters to apply
 * @return	bool			True on success; false otherwise
 */
function btc_db_comment_counts($ID, $filters=null) {
	global $wpdb;
	$query = 'SELECT SUBSTR(comment_agent,5) AS comment_src,COUNT(*) AS cnt,IF(comment_approved=1,1,0) AS enabled ' .
			'FROM ' . $wpdb->prefix . 'comments ' .
			'WHERE comment_post_ID=' . $wpdb->escape($ID) . ' ';
	if (isset($filters['srcs']) && !empty($filters['srcs'])) {
		$filter_query = ($filters['exclude'] == true) ? 'AND comment_agent NOT IN (' : ' AND comment_agent IN (';
		foreach ($filters['srcs'] as $src) {
			$filter_query .= '\'btc_' . $wpdb->escape($src) . '\',';
		}
		$query .= substr($filter_query, 0, -1) . ') ';
	}
	$query .= 'AND comment_approved IN (\'1\',\'d_1\') AND SUBSTR(comment_agent,1,4)=\'btc_\' ';
	$query .= 'GROUP BY comment_agent';
	return $wpdb->get_results($wpdb->prepare($query));
}

/**
 * Retrieve BTC comment summary for a post
 *
 * @param	int	$ID		The post ID you want comment summary for
 * @param	array	$filters	The source filters to apply
 * @return	bool			True on success; false otherwise
 */
function btc_db_comment_summary($ID) {
	global $wpdb;
	$query = 'SELECT SUBSTR(comment_agent,5) AS comment_src,COUNT(*) AS cnt,IF(comment_approved=1,1,0) AS enabled ' .
			'FROM ' . $wpdb->prefix . 'comments ' .
			'WHERE comment_post_ID=' . $wpdb->escape($ID) . ' ' .
			'AND SUBSTR(comment_agent,1,4)=\'btc_\' ' .
			'GROUP BY comment_agent';
	return $wpdb->get_results($wpdb->prepare($query));
}

/**
 * Get the latest post IDs
 *
 * @global	object	$wpdb	DB object
 *
 * @return	bool		True when successful; false otherwise
 */
function btc_db_get_latest_post_IDs() {
	global $wpdb;
	$query = 'SELECT ID ' .
			'FROM ' . $wpdb->prefix . 'posts ' .
			'WHERE post_type=\'post\' AND post_status=\'publish\' ' .
			'AND post_date>=\'2009-01-01 00:00:00\' ' .
			'ORDER BY ID DESC ' .
			'LIMIT 75';
	return $wpdb->get_col($query);
}

/**
 * Check for duplicate comment
 *
 * @global	object	$wpdb	DB object
 *
 * @param	array	$commentdata	Comment data
 * @return	bool			IDs when successful; false otherwise
 */
function btc_db_dupe_comment($commentdata) {
	global $wpdb;
	$query = 'SELECT comment_ID FROM ' . $wpdb->prefix . 'comments WHERE comment_post_ID=%s AND comment_author_url=%s AND comment_agent=%s AND comment_content=%s';
	return $wpdb->get_col($wpdb->prepare($query, $commentdata['comment_post_ID'], $commentdata['comment_author_url'], $commentdata['comment_agent'], $commentdata['comment_content']));
}

/**
 * Check for duplicate comment -- strict check avoid charset issues (only for use after insert)
 *
 * @global      object  $wpdb   DB object
 *
 * @param       array   $comment_ID	Comment ID
 * @return      mixed                   IDs when successful; false otherwise
 */
function btc_db_dupe_clean($comment_ID) {
    global $wpdb;
    $query = 'SELECT c1.comment_ID ' .
            'FROM ' . $wpdb->prefix . 'comments c1 ' .
            'WHERE comment_content=' .
                '(SELECT c2.comment_content ' .
                'FROM ' . $wpdb->prefix . 'comments c2 ' .
                'WHERE c2.comment_ID=%s AND c2.comment_post_ID=c1.comment_post_ID ' .
                'AND c2.comment_author_url=c1.comment_author_url ' .
                'AND c2.comment_agent=c1.comment_agent) ' .
            'AND comment_ID<%s';
    return $wpdb->get_col($wpdb->prepare($query, $comment_ID, $comment_ID));
}

/**
 * Remove duplicate comment
 *
 * @global	object	$wpdb	DB object
 *
 * @param	int	$comment_ID	Comment ID
 * @return	bool			True when successful; false otherwise
 */
function btc_db_delete_dupe_comment($comment_ID) {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'comments WHERE comment_ID=%s';
	return $wpdb->query($wpdb->prepare($query, $comment_ID));
}

/**
 * Cache comment counts in the post meta data
 *
 * @param	int		$ID			Post ID
 * @param	array	$counts		Comment counts
 */
function btc_db_set_comment_counts($ID, $counts) {
	$btc_comment_counts = array();
	foreach ($counts as $count) {
		$btc_comment_counts[] = array('comment_src' => $count->comment_src, 'cnt' => $count->cnt, 'enabled' => $count->enabled);
	}
	if (!update_post_meta($ID, 'btc_comment_counts', $btc_comment_counts)) {
		add_post_meta($ID, 'btc_comment_counts', $btc_comment_counts, true);
	}
}

/**
 * Retrieve cached comment counts from the post meta data
 *
 * @param	int		$ID		Post ID
 * @return	array			Comment counts
 */
function btc_db_get_comment_counts($ID) {
	$btc_comment_counts = get_post_meta($ID, 'btc_comment_counts', true);
	if (!is_array($btc_comment_counts)) {
		return false;
	}
	$counts = array();
	foreach ($btc_comment_counts as $count) {
		$counts[] = (object) array('comment_src' => $count['comment_src'], 'cnt' => $count['cnt'], 'enabled' => $count['enabled']);
	}
	return $counts;
}

/**
 * Clear cached comment counts
 *
 * @uses	btc_db_get_post_IDs()
 *
 * @param	int		$ID		Post ID
 */
function btc_db_clear_comment_counts($ID=null, $wp_update=true) {
	if ($ID == null) {
		$posts = btc_db_get_post_IDs();
		foreach ($posts as $post_ID) {
			delete_post_meta($post_ID, 'btc_comment_counts');
			if (function_exists('wp_update_comment_count') && $wp_update) {
				wp_update_comment_count($post_ID);
			}
		}
	} else {
		delete_post_meta($ID, 'btc_comment_counts');
		if (function_exists('wp_update_comment_count') && $wp_update) {
			wp_update_comment_count($ID);
		}
	}
}

/**
 * Cache comment summary in the post meta data
 *
 * @param	int		$ID			Post ID
 * @param	array	$counts		Comment summary
 */
function btc_db_set_comment_summary($ID, $summary) {
	$btc_comment_summary = array();
	foreach ($summary as $s) {
		$btc_comment_summary[] = array('comment_src' => $s->comment_src, 'cnt' => $s->cnt, 'enabled' => $s->enabled);
	}
	if (!update_post_meta($ID, 'btc_comment_summary', $btc_comment_summary)) {
		add_post_meta($ID, 'btc_comment_summary', $btc_comment_summary, true);
	}
}

/**
 * Retrieve cached comment summary from the post meta data
 *
 * @param	int		$ID		Post ID
 * @return	array			Comment summary
 */
function btc_db_get_comment_summary($ID) {
	$btc_comment_summary = get_post_meta($ID, 'btc_comment_summary', true);
	if (!is_array($btc_comment_summary)) {
		return false;
	}
	$summary = array();
	foreach ($btc_comment_summary as $s) {
		$summary[] = (object) array('comment_src' => $s['comment_src'], 'cnt' => $s['cnt'], 'enabled' => $s['enabled']);
	}
	return $summary;
}

/**
 * Clear cached comment counts
 *
 * @uses	btc_db_get_post_IDs()
 *
 * @param	int		$ID		Post ID
 */
function btc_db_clear_comment_summary($ID=null) {
	if ($ID == null) {
		$posts = btc_db_get_post_IDs();
		foreach ($posts as $post_ID) {
			delete_post_meta($post_ID, 'btc_comment_summary');
		}
	} else {
		delete_post_meta($ID, 'btc_comment_summary');
	}
}

/**
 * Activate plugin for a post
 *
 * @param	object	$post	Post
 * @return	bool			True on success; false otherwise
 */
function btc_db_enable_post($post)
{
	$btc_post = array('ID' => $post->ID,
						'post_date_gmt' => $post->post_date_gmt,
						'initial_import_date_gmt' => '0000-00-00 00:00:00',
						'last_import_date_gmt' => '0000-00-00 00:00:00',
						'hits' => 0, 'misses' => 0);
	return add_post_meta($post->ID, 'btc_post', $btc_post, true);
}

/**
 * Deactivate plugin for post(s)
 *
 * @param	int		$ID		Post to deactivate
 * @return	bool			True on success; false otherwise
 */
function btc_db_disable_post($ID)
{
	return delete_post_meta($ID, 'btc_post');
}

/**
 * Fetch post IDs
 *
 * @global	object	$wpdb	DB object
 *
 * @return	array			post IDs
 */
function btc_db_get_post_IDs() {
	global $wpdb;
	$query = 'SELECT post_id ' .
			'FROM ' . $wpdb->prefix . 'postmeta ' .
			'WHERE meta_key=\'btc_post\'';
	return $wpdb->get_col($query);
}

/**
 * Update a post
 *
 * @param	object	$post	Post to update
 * @return	bool			True when successful; false otherwise
 */
function btc_db_update_post($post) {
	$btc_post = array('ID' => $post->ID,
						'post_date_gmt' => $post->post_date_gmt,
						'initial_import_date_gmt' => $post->initial_import_date_gmt,
						'last_import_date_gmt' => $post->last_import_date_gmt,
						'hits' => (int) $post->hits, 'misses' => (int) $post->misses);
	return update_post_meta($post->ID, 'btc_post', $btc_post);
}

/**
 * Get posts
 *
 * @global	object	$wpdb	DB object
 * @uses	btc_db_enable_post()
 * @uses	btc_db_get_latest_post_IDs()
 *
 * @return	array	Collection of post objects
 */
function btc_db_get_posts() {
	global $wpdb;
	$IDs = btc_db_get_latest_post_IDs();
	
	$btc_posts = array();
	foreach ($IDs as $ID) {
		if (!$btc_post = get_post_meta($ID, 'btc_post', true)) {
			$post = $wpdb->get_row($wpdb->prepare('SELECT ID,post_date_gmt FROM ' . $wpdb->prefix . 'posts WHERE ID=%s', $ID));
			btc_db_enable_post($post);
			$btc_post = array('ID' => $ID,
								'post_date_gmt' => $post->post_date_gmt,
								'initial_import_date_gmt' => '0000-00-00 00:00:00',
								'last_import_date_gmt' => '0000-00-00 00:00:00',
								'hits' => 0, 'misses' => 0);
		}
		$btc_posts[] = (object) $btc_post;
	}
	return $btc_posts;
}

/**
 * Store comment_ID of BTC comment from own blog
 *
 * @param	int		$ID				Post ID
 * @param	int		$comment_ID		Comment ID
 * @return	bool					True when successful; false otherwise
 */
function btc_db_add_own_blog_comment($ID, $comment_ID) {
	return add_post_meta($ID, 'btc_own_blog_comment', $comment_ID);
}

/**
 * Store comment_ID of BTC comment that is a retweet
 *
 * @param	int		$ID				Post ID
 * @param	int		$comment_ID		Comment ID
 * @return	bool					True when successful; false otherwise
 */
function btc_db_add_retweet($ID, $comment_ID) {
	return add_post_meta($ID, 'btc_retweet', $comment_ID);
}

/**
 * Retrieve BTC comments from this blog
 *
 * @uses	btc_db_get_post_IDs()
 *
 * @return	bool	Collection of comment IDs
 */
function btc_db_get_own_blog_comments() {
	$IDs = btc_db_get_post_IDs();
	$comment_IDs = array();
	foreach ($IDs as $ID) {
		$comment_IDs = array_merge($comment_IDs, get_post_meta($ID, 'btc_own_blog_comment'));
	}
	return $comment_IDs;
}

/**
 * Retrieve BTC comments that are retweets
 *
 * @uses	btc_db_get_post_IDs
 *
 * @return	bool	Collection of comment IDs
 */
function btc_db_get_retweets() {
	$IDs = btc_db_get_post_IDs();
	$comment_IDs = array();
	foreach ($IDs as $ID) {
		$comment_IDs = array_merge($comment_IDs, get_post_meta($ID, 'btc_retweet'));
	}
	return $comment_IDs;
}

/**
 * Clear references to BTC comments from this blog
 *
 * @global	object	$wpdb	DB object
 *
 * @return	bool	DESCRIPTION
 */
function btc_db_clear_own_blog_comments() {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'postmeta ' .
			'WHERE meta_key=\'btc_own_blog_comment\'';
	return $wpdb->query($query);
}

/**
 * Clear references to BTC comments that are retweets
 *
 * @global	object	$wpdb	DB object
 *
 * @return	bool	DESCRIPTION
 */
function btc_db_clear_retweets() {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'postmeta ' .
			'WHERE meta_key=\'btc_retweet\'';
	return $wpdb->query($query);
}

/**
 * Store comment type of new BTC comment
 *
 * @param	int		$ID				Post ID
 * @param	string	$comment_type	Comment type to be added
 * @param	int		$comment_ID		Comment ID
 */
function btc_db_add_comment_type($ID, $comment_type, $comment_ID) {
	add_post_meta($ID, $comment_type, $comment_ID);
}

/**
 * Delete stored comment type data for BTC comment
 *
 * @param	int		$ID				Post ID
 * @param	string	$comment_type	Comment type
 * @param	int		$comment_ID		Comment ID
 */
function btc_db_clear_comment_type($ID, $comment_type, $comment_ID) {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'postmeta ' .
			'WHERE post_id=%s AND meta_key=%s AND meta_value=%s';
	return $wpdb->query($wpdb->prepare($query, $ID, $comment_type, $comment_ID));
}

/**
 * Delete stored comment type data for BTC comments
 *
 * @param	string	$comment_type	Comment type to be added
 * @return	bool					True when successful; false otherwise
 */
function btc_db_clear_all_comment_type($comment_type) {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'postmeta ' .
			'WHERE meta_key=%s';
	return $wpdb->query($wpdb->prepare($query, $comment_type));
}

/**
 * Delete all post meta for BTC
 *
 * @global	object	$wpdb	DB object
 *
 * @return	bool			True when successful; false otherwise
 */
function btc_db_clear_posts() {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'postmeta ' .
			'WHERE meta_key=\'btc_post\'';
	return $wpdb->query($query);
}
?>
