<?php
/*
Plugin Name: BackType Connect
Plugin URI: http://www.backtype.com/plugins/connect/
Description: Show related conversations (from other blogs, Twitter, Digg, FriendFeed and more) inline with your own comments.
Version: 0.2.5
Author: BackType <support@backtype.com>
Author URI: http://www.backtype.com/
*/

/*  Copyright 2009  BackType Inc  (email : support@backtype.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('BTC_VERSION', '0.2.5');

define('BTC_API_REGISTER_URL', 'http://api.backtype.com/register-connect.xml');
define('BTC_API_CONNECT_URL', 'http://api.backtype.com/comments/connect.xml');
define('BTC_API_PING_URL', 'http://api.backtype.com/ping.xml');

define('BTC_VERSION_OPTION', 'btc_version_option');
define('BTC_ENABLED_OPTION', 'btc_enabled_option');
define('BTC_API_KEY_OPTION', 'btc_api_key_option');
define('BTC_COMMENT_SORT_OPTION', 'btc_comment_sort_option');
define('BTC_SUMMARY_OPTION', 'btc_summary_option');
define('BTC_MORE_COMMENTS_OPTION', 'btc_more_comments_option');
define('BTC_IGNORE_OWN_BLOG_OPTION', 'btc_ignore_own_blog_option');
define('BTC_IGNORE_RETWEETS_OPTION', 'btc_ignore_retweets_option');
define('BTC_MODERATION_OPTION', 'btc_moderation_option');
define('BTC_AKISMET_OPTION', 'btc_akismet_option');
define('BTC_DEBUG_OPTION', 'btc_debug_option');
define('BTC_SRC_BLOG_OPTION', 'btc_src_blog_option');
define('BTC_SRC_DIGG_OPTION', 'btc_src_digg_option');
define('BTC_SRC_REDDIT_OPTION', 'btc_src_reddit_option');
define('BTC_SRC_FRIENDFEED_OPTION', 'btc_src_friendfeed_option');
define('BTC_SRC_YC_OPTION', 'btc_src_yc_option');
define('BTC_SRC_TWITTER_OPTION', 'btc_src_twitter_option');

define('BTC_SRC_BLOG', 'blog');
define('BTC_SRC_DIGG', 'digg');
define('BTC_SRC_REDDIT', 'reddit');
define('BTC_SRC_FRIENDFEED', 'friendfeed');
define('BTC_SRC_YC', 'yc');
define('BTC_SRC_TWITTER', 'twitter');

define('BTC_OPTION_DISABLED', 0);
define('BTC_OPTION_ENABLED', 1);
define('BTC_OPTION_SUMMARY', 2);

if (!defined('BTC_DIR')) {
	define('BTC_DIR', dirname(__FILE__));
}
if (!defined('WP_CONTENT_DIR')) {
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
}

define('BTC_LOG_FILE', false);

if (BTC_LOG_FILE && get_option(BTC_DEBUG_OPTION)) {
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('error_log', BTC_LOG_FILE);
}

require_once ABSPATH . 'wp-includes/compat.php';

if (is_admin()) {
	register_deactivation_hook(__FILE__, 'btc_uninstall');
	require_once BTC_DIR . '/admin.php';
}

add_action('admin_notices', 'btc_admin_notices');
add_action('btc_connect', 'btc_connect');
add_action('comment_post', 'btc_ping');
add_action('wp_set_comment_status', 'btc_set_comment_status');
add_action('delete_comment', 'btc_delete_comment', 1);

add_filter('comments_array', 'btc_get_comments', 1);
add_filter('get_comments_number', 'btc_get_comments_number', 1);
add_filter('get_avatar', 'btc_get_avatar', 10, 5);
add_filter('comment_class', 'btc_comment_class', 10, 3);
add_filter('comment_feed_where', 'btc_comment_feed_where', 10, 1);

if (get_option(BTC_SUMMARY_OPTION)) add_filter('comments_template', 'btc_comments_template', 1);
if (get_option(BTC_MORE_COMMENTS_OPTION)) add_filter('comment_reply_link', 'btc_comment_reply_link', 1);

/**
 * Install BackType Connect
 *
 * @uses	btc_do_install()
 */
function _btc_install() {
	require_once BTC_DIR . '/install.php';
	btc_do_install();
}

/**
 * Uninstall BackType Connect
 *
 * @uses	btc_uninstall()
 */
function btc_uninstall() {
	require_once BTC_DIR . '/install.php';
	btc_do_uninstall();
}

function btc_admin_notices() {
	if (!_btc_is_registered()) {
		_btc_install();
	}
	if (!_btc_is_registered()) {
		_btc_display_message('There was a problem registering your plugin with BackType. Please contact <a href="mailto:support@backtype.com">support@backtype.com</a> for assistance.', 'notice');
	} elseif (!get_option(BTC_ENABLED_OPTION) && $_SERVER['REQUEST_URI'] != '/wp-admin/options-general.php?page=BackType%20Connect') {
		_btc_display_message('<a href="' . get_option('siteurl') . '/wp-admin/options-general.php?page=BackType%20Connect">Configure the plugin</a> to finish setting up BackType Connect.', 'success');
	}
}

/**
 * Main import loop
 *
 * @uses	btc_db_get_posts()
 * @uses	btc_db_update_posts()
 * @uses	_btc_check_do_import()
 * @uses	_btc_import()
 *
 * @param	mixed	$offset		Used to ensure only most recent schedule event runs
 */
function btc_connect($offset=false) {
	if (!isset($_GET['activate']) && !isset($_GET['deactivate'])) {
		$now = date('i');
		if ($offset === false || ($now - $offset < 5 && $now - $offset >= 0)) {
			require_once BTC_DIR . '/db.php';
			$btc_posts = btc_db_get_posts();
			if ($btc_posts) {
				$idx = 0;
				foreach ($btc_posts as $btc_post) {
					if (_btc_check_do_import($btc_post, $idx)) {
						_btc_import($btc_post);
						if ($btc_post->initial_import_date_gmt == 0) {
							btc_log('importing post id: ' . $btc_post->ID, 'debug');
							$btc_post->initial_import_date_gmt = gmdate('Y-m-d H:i:s');
							btc_db_update_post($btc_post);
						}
					}
					$idx++;
				}
			} 
		}
	}
}

/**
 * Ping BackType
 *
 * @uses	_btc_url_open_non_blocking()
 *
 * @param	int		$comment_ID		Comment ID
 */
function btc_ping($comment_ID) {
	$comment = get_comment($comment_ID);
	$params = array('identifier' => 'btc', 'key' => get_option(BTC_API_KEY_OPTION), 'url' => get_permalink($comment->comment_post_ID));
	btc_log('Pinging BackType: ' . BTC_API_PING_URL . '?' . http_build_query($params, null, '&'), 'debug');
	if (_btc_url_open_non_blocking(BTC_API_PING_URL . '?' . http_build_query($params, null, '&'))) {
		btc_log('Successfully pinged BackType.', 'success');
	} else {
		btc_log('Error pinging BackType.', 'error');
	}
}

/**
 * Reset WP counts when we change change status of BTC comment
 *
 * @uses	btc_db_clear_comment_counts()
 * @uses	_btc_is_btc_comment()
 *
 * @param	int		$comment_ID		Comment ID
 * @param	string	$comment_status	Status
 */
function btc_set_comment_status($comment_id, $comment_status=null) {
	$comment = get_comment($comment_id);
	if (_btc_is_btc_comment($comment)) {
		require_once BTC_DIR . '/db.php';
		btc_db_clear_comment_counts($comment->comment_post_ID);
	}
}

/**
 * Hack to insert BTC comment after deleted
 *
 * @uses	btc_db_clear_comment_counts()
 * @uses	_btc_is_btc_comment()
 *
 * @param	int		$comment_ID		Comment ID
 */
function btc_delete_comment($comment_id) {
	$comment = get_comment($comment_id);
	if (_btc_is_btc_comment($comment)) {
		require_once BTC_DIR . '/db.php';
		$comment->comment_approved = 'deleted';
		$commentdata = (array) $comment;
		wp_insert_comment($commentdata);
		btc_db_clear_comment_type($comment->comment_post_ID, $comment->comment_agent, $comment_id);
		btc_db_clear_comment_counts($comment->comment_post_ID);
		if (function_exists('wp_update_comment_count')) {
			wp_update_comment_count($comment->comment_post_ID);
		}
	}
}

/**
 * Filters and organizes comments to display
 *
 * @uses	_btc_get_filters()
 * @uses	_btc_is_btc_comment()
 *
 * @param	mixed	$comments	All comments for the post
 * @return	mixed				Filtered comments
 */
function btc_get_comments($comments) {
	$filters = _btc_get_filters();

	if (empty($filters['srcs']) && get_option(BTC_COMMENT_SORT_OPTION) == 'mixed') {
		return $comments;
	}

	$result = array();
	$btc_comments = array();

	foreach ($comments as $entry) {
		if (_btc_is_btc_comment($entry)) {
			if (!in_array(substr($entry->comment_agent, 4), $filters['srcs'])) {
				if (get_option(BTC_COMMENT_SORT_OPTION) == 'mixed') {
					$result[] = $entry;
				} else {
					$btc_comments[] = $entry;
				}
			}
		} else {
			$result[] = $entry;
		}
	}

	if (!empty($btc_comments)) {
		return array_merge($result, $btc_comments);
	}

	return $result;
}

/**
 * Adjust total comments number
 *
 * @global	object	$post	The current post
 * @uses	btc_db_get_comment_counts()
 * @uses	btc_db_set_comment_counts()
 * @uses	btc_db_comment_counts()
 * @uses	_btc_get_filters()
 *
 * @param	int		$count	Total comments number
 * @return	int 	$count	Total displayed WP and BTC comments number
 */
function btc_get_comments_number($count) {
	global $post;
	require_once BTC_DIR . '/db.php';
	$btc_count = 0;
	if (($btc_counts = btc_db_get_comment_counts($post->ID)) === false) {
		$btc_counts = btc_db_comment_counts($post->ID);
		btc_db_set_comment_counts($post->ID, $btc_counts);
	}
	if ($btc_counts) {
		$filters = _btc_get_filters();
		foreach ($btc_counts as $src) {
			if (in_array($src->comment_src, $filters['srcs']) && $src->enabled) {
				$btc_count -= (int) $src->cnt;
			}
		}
	}
	return $count + $btc_count;
}

/**
 * Filter avatars for BTC comments
 *
 * @uses	_btc_is_btc_comment()
 *
 * @param	string	$avatar			Current avatar
 * @param	object	$id_or_email	Current comment author
 * @param	int		$size			Size of avatar
 * @param	string	$default		Default avatar
 * @param	string	$alt			Alt tag for avatar
 * @return	string					New avatar
 */
function btc_get_avatar($avatar, $id_or_email, $size='96', $default='', $alt='') {
	if (!is_object($id_or_email) && $comment_ID = get_comment_ID()) {
		$id_or_email = get_comment($comment_ID);
	}
	if (is_object($id_or_email)) {
		if (_btc_is_btc_comment($id_or_email)) {
			$alt = '';
			switch ($id_or_email->comment_agent) {
				case 'btc_'.BTC_SRC_TWITTER:
					$default = 'http://img.tweetimag.es/i/' . $id_or_email->comment_author . '_n';
					break;
				case 'btc_'.BTC_SRC_FRIENDFEED:
					$default = $id_or_email->comment_author_url . '/picture?size=medium';
					break;
				case 'btc_'.BTC_SRC_DIGG:
					$default = $id_or_email->comment_author_url . '/l.png';
					break;
				case 'btc_'.BTC_SRC_REDDIT:
					$default = WP_PLUGIN_URL . '/backtype-connect/images/reddit-icon-50.gif';
					break;
				case 'btc_'.BTC_SRC_YC:
					$default = WP_PLUGIN_URL . '/backtype-connect/images/yc-icon-50.gif';
					break;
			}
			return "<img alt='{$alt}' src='{$default}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		}
	}
	return $avatar;
}

/**
 * Add btc classes to BTC comment
 *
 * @uses	_btc_is_btc_comment()
 *
 * @param	string	$classes		Class attribute for comment
 * @param	string	$class			Class attribute for comment
 * @param	string	$comment_id		ID of the comment
 * @return							Adjusted class attribute for comment
 */
function btc_comment_class($classes, $class=null, $comment_id=null) {
	if ($comment_id == null) {
		$comment_id = get_comment_ID();
	}
	if ($comment = get_comment($comment_id)) {
		if (_btc_is_btc_comment($comment)) {
			$classes[] = 'btc-comment';
			switch ($comment->comment_agent) {
				case 'btc_'.BTC_SRC_DIGG:
					$classes[] = 'btc-digg';
					break;
				case 'btc_'.BTC_SRC_REDDIT:
					$classes[] = 'btc-reddit';
					break;
				case 'btc_'.BTC_SRC_FRIENDFEED:
					$classes[] = 'btc-friendfeed';
					break;
				case 'btc_'.BTC_SRC_YC:
					$classes[] = 'btc-yc';
					break;
				case 'btc_'.BTC_SRC_TWITTER:
					$classes[] = 'btc-twitter';
					break;
			}
		}
	}
	return $classes;
}

/**
 * Adjusts the WHERE clause for the comments feeds
 *
 * @param	string	$cwhere		WHERE clause in SQL
 * @return	string				Adjusted WHERE clause
 */
function btc_comment_feed_where($cwhere) {
	return $cwhere . ' AND SUBSTR(comment_agent,1,3)!=\'btc\'';
}

/**
 * Prepend BTC summary to comments template
 *
 * @param	string	$tpl	Template
 */
function btc_comments_template($tpl) {
	return BTC_DIR . '/comment-template.php';
}

/**
 * Add link to more comments by author
 *
 * @uses	_btc_is_btc_comment()
 *
 * @param	string	$link	Reply link
 * @return	string			Adjusted link
 */
function btc_comment_reply_link($link) {
	$author_url = get_comment_author_url();
	if (!$author_url || strlen($author_url) <= 7) return $link;
	if ($comment_ID = get_comment_ID()) {
		if ($comment = get_comment($comment_ID)) {
			switch ($comment->comment_agent) {
				case 'btc_'.BTC_SRC_DIGG:
					$profile_url = $comment->comment_author_url . '/history/comments';
					break;
				case 'btc_'.BTC_SRC_REDDIT:
					$profile_url = $comment->comment_author_url . 'comments';
					break;
				case 'btc_'.BTC_SRC_FRIENDFEED:
					$profile_url = $comment->comment_author_url . '/comments';
					break;
				case 'btc_'.BTC_SRC_YC:
					$profile_url = 'http://news.ycombinator.com/threads?id=' . $comment->comment_author;
					break;
				case 'btc_'.BTC_SRC_TWITTER:
					$profile_url = $comment->comment_author_url;
					break;
				default:
					$profile_url = ((substr($author_url, 0, 7) == 'http://') ? 'http://www.backtype.com/url/' . _btc_url_encode($author_url) : '');
					break;
			}
		}
	}
	if ($profile_url) {
		return (($link == '') ? '' : $link . ' &nbsp; ') . '<a href="' . $profile_url . '">More from author</a>';
	} else {
		return $link;
	}
}

/**
 * BTC logging for debug
 *
 * @param	string	$message	Message to log
 * @param	string	$level		Type of message
 * @param	string	$file		Destination to log to
 */
function btc_log($message, $level='notice', $file=BTC_LOG_FILE) {
	if (BTC_LOG_FILE && get_option(BTC_DEBUG_OPTION)) {
		error_log(sprintf("[%s] [%s] %s\n", date("D M j G:i:s Y"), $level, $message), 3, $file);
	}
}

/**
 * Display messages
 *
 * @global	string	$wp_version	The version of Wordpress installed
 *
 * @param	string	$message	The message to display
 * @param	string	$level		Type of message
 */
function _btc_display_message($message, $level='notice') {
	global $wp_version;
	$display = '<div class="updated fade';
	switch ($level) {
		case 'error':
			$display .= ($wp_version < 2.5) ? '-ff000' : '';
		break;
	}
	$display .= '"><p><strong>' . __($message) . '</strong></p></div>';
	echo $display;
}

/**
 * Check if plugin is registered
 *
 * @return	bool	True if registered; false otherwise
 */
function _btc_is_registered() {
	return (get_option(BTC_API_KEY_OPTION)) ? true : false;
}

/**
 * Test comment to see if it is a BTC comment
 *
 * @param	int		$comment		Comment to test
 * @param	bool	$ret_ID			Whether or not to return the comment_ID
 * @return	bool					True if BTC comment; false otherwise
 */
function _btc_is_btc_comment($comment, $ret_ID=false) {
	if (substr($comment->comment_agent, 0, 4) == 'btc_') {
		if ($ret_ID) {
			return $comment->comment_ID;
		}
		return true;
	}
	return false;
}

/**
 * Get BTC comment filters
 *
 * @return	array	Array of filters
 */
function _btc_get_filters() {
	$filters = array('srcs' => array(), 'exclude' => true);
	if (get_option(BTC_SRC_BLOG_OPTION) == BTC_OPTION_DISABLED)
		$filters['srcs'][] = BTC_SRC_BLOG;
	if (get_option(BTC_SRC_DIGG_OPTION) == BTC_OPTION_DISABLED)
		$filters['srcs'][] = BTC_SRC_DIGG;
	if (get_option(BTC_SRC_REDDIT_OPTION) == BTC_OPTION_DISABLED)
		$filters['srcs'][] = BTC_SRC_REDDIT;
	if (get_option(BTC_SRC_FRIENDFEED_OPTION) == BTC_OPTION_DISABLED)
		$filters['srcs'][] = BTC_SRC_FRIENDFEED;
	if (get_option(BTC_SRC_YC_OPTION) == BTC_OPTION_DISABLED)
		$filters['srcs'][] = BTC_SRC_YC;
	if (get_option(BTC_SRC_TWITTER_OPTION) == BTC_OPTION_DISABLED)
		$filters['srcs'][] = BTC_SRC_TWITTER;
	return $filters;
}

/**
 * Encode URL
 *
 * @param	string	$url	URL to encode
 * @return	string			Encoded URL
 */
function _btc_url_encode($url) {
	return urlencode(str_replace(array('/', '&amp;', '&'), array('%2f', '%26', '%26'), substr($url, 7)));
}

/**
 * Determines if a new import should be done
 *
 * @param	object	$post	Post to check
 * @param	int     $idx	Index in main loop
 * @return	bool            True if import should be done; false otherwise
 */
function _btc_check_do_import($btc_post, $idx) {
	// TODO: clean this up
	btc_log('Scheduling', 'debug');
	btc_log(sprintf('Post ID: %s', $btc_post->ID), 'debug');
	btc_log(sprintf('Initial import date: %s', $btc_post->initial_import_date_gmt), 'debug');
	btc_log(sprintf('Last import date: %s', $btc_post->last_import_date_gmt), 'debug');
	btc_log(sprintf('Post date: %s', $btc_post->post_date_gmt), 'debug');
	if ($btc_post->initial_import_date_gmt == 0) {
		return true;
	} elseif ($btc_post->post_date_gmt > gmdate('Y-m-d H:i:s', time()-3600)) { // first hour
		if ($idx < 10) {
			return true;
		} else {	
			return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-1800)) ? true : false; // every 30 minutes
		}
	} elseif ($btc_post->post_date_gmt > gmdate('Y-m-d H:i:s', time()-86400)) { // first 24 hours
		if ($btc_post->hits > $btc_post->misses) {
			return true;
		} else {
			if ($idx < 20) {
				return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-900)) ? true : false; // every 15 minutes
			} else {
				return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-7200)) ? true : false; // every 2 hours
			}
		}
	} elseif ($btc_post->post_date_gmt > gmdate('Y-m-d H:i:s', time()-129600)) { // first 1.5 days
		if ($btc_post->hits > $btc_post->misses) {
			return true;
		} else {
			if ($idx < 30) {
				return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-1800)) ? true : false; // every 30 minutes
			} else {
				return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-14400)) ? true : false; // every 4 hours
			}
		}
	} elseif ($btc_post->post_date_gmt > gmdate('Y-m-d H:i:s', time()-172800)) { // first 2 days
		if ($btc_post->hits > $btc_post->misses) {
			return true;
		} else {
			if ($idx < 40) {
				return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-3600)) ? true : false; // every hour
			} else {
				return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-28800)) ? true : false; // every 8 hours
			}
		}
	} elseif ($btc_post->post_date_gmt > gmdate('Y-m-d H:i:s', time()-604800)) { // first week
		if ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-7200)) { // 2 hour
			return true;
		} elseif ($btc_post->hits > $btc_post->misses) {
			return true;
		}
	} elseif ($btc_post->post_date_gmt > gmdate('Y-m-d H:i:s', time()-1728000)) { // twenty days
		if ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-14400)) { // 4 hours
			return true;
		} elseif ($btc_post->hits > $btc_post->misses) {
			return true;
		}
	} elseif ($btc_post->hits > $btc_post->misses && $btc_post->post_date_gmt > gmdate('Y-m-d H:i:s', time()-5184000)) { // safety
		return ($btc_post->last_import_date_gmt < gmdate('Y-m-d H:i:s', time()-1800)) ? true : false; // every 30 minutes
	}
	return false;
}

/**
 * Calls BackType API and parses response
 *
 * @uses	btc_db_comment_counts()
 * @uses	btc_db_dupe_comment()
 * @uses	btc_db_add_comment_type()
 * @uses	btc_db_add_own_blog_comment()
 * @uses	btc_db_add_retweet()
 * @uses	btc_db_set_comment_counts()
 * @uses	btc_db_update_post()
 * @uses	_btc_akismet_check_comment()
 * @uses	_btc_comment_desc()
 * @uses	_btc_filter_content()
 * @uses	_btc_get_filters()
 * @uses	_btc_url_open()
 * @uses	_btc_xml_unserialize()
 *
 * @param	object	$btc_post	Post to call API for
 * @return	array				Status
 */
function _btc_import(&$btc_post) {
	$filters		= _btc_get_filters();
	$page		 	= 1;
	$processed		= false;
	$error			= 0;
	$url		  	= get_permalink($btc_post->ID);
	$itemsperpage 	= '25';
	$params		  	= array('identifier' => 'btc', 'url' => $url, 'key' => get_option(BTC_API_KEY_OPTION), 'page' => '1', 'sort' => '1', 'itemsperpage' => $itemsperpage);

	do {
		btc_log('Fetching: ' . BTC_API_CONNECT_URL . '?' . http_build_query($params, null, '&'), 'debug');
		if (!$contents = _btc_url_open(BTC_API_CONNECT_URL . '?' . http_build_query($params, null, '&'))) {
			btc_log('Failed opening url: ' . BTC_API_CONNECT_URL . '?' . http_build_query($params, null, '&'), 'error');
			$error = 1;
			break;
		}

		$xml = _btc_xml_unserialize($contents['body']);
		unset($contents);

		if (class_exists('SimpleXMLElement')) {
			if (!$xml || !empty($xml->errorCode) || empty($xml->comments)) {
				if (!empty($xml->errorCode) && $xml->errorCode == 'related-conversations-not-found' || (int) $xml->totalresults == 0) {
					$btc_post->last_import_date_gmt = gmdate('Y-m-d H:i:s');
					$btc_post->misses = (int) $btc_post->misses + 1;
					btc_db_update_post($btc_post);
					btc_log('No related conversations.', 'error');
					$error = 2;
				} else if ($xml && empty($xml->comments->entry) && (int) $xml->startindex > (int) $xml->totalresults) {
					btc_log('Paginated too far.', 'error');
					//..
				} else {
					btc_log('Unknown XML error.', 'error');
					$error = 3;
				}
				break;
			}
		
			if (empty($xml->comments->entry) || !count($xml->comments->entry)) {
				btc_log('No comments.', 'debug');
				break;
			}

			require_once BTC_DIR . '/db.php';
		
			$new = 0;

			btc_log('Comments received: ' . count($xml->comments->entry), 'debug');
			$comments = array();
			$ignore_own_blog = get_option(BTC_IGNORE_OWN_BLOG_OPTION);
			$ignore_retweets = get_option(BTC_IGNORE_RETWEETS_OPTION);
			$siteurl = get_option('siteurl');
			$site_pieces = parse_url($siteurl);
			$site_domain = (isset($site_pieces['host'])) ? trim($site_pieces['host'], '/') : '';
			$site_path = (isset($site_pieces['path'])) ? trim($site_pieces['path'], '/') : '';
			foreach ($xml->comments->entry as $entry) {
				if ($entry['type'] == 'comment') {
					$entry_pieces = parse_url($entry->blog->url);
					$entry_domain = (isset($entry_pieces['host'])) ? trim($entry_pieces['host'], '/') : '';
					$entry_path = (isset($entry_pieces['path'])) ? trim($entry_pieces['path'], '/') : '';
					$is_own_blog = ($site_domain == $entry_domain && $site_path == $entry_path) ? 1 : 0;
					$is_retweet = 0;
					$commentdata = array(
						'comment_post_ID' => $btc_post->ID,
						'comment_author' => ($entry->author->name) ? trim($entry->author->name) : 'Anonymous',
						'comment_author_email' => '',
						'comment_author_url' => trim($entry->author->url),
						'comment_author_IP' => '',
						'comment_date' => date('Y-m-d H:i:s', strtotime(trim($entry->comment->date)) + get_option('gmt_offset')*3600),
						'comment_date_gmt' => date('Y-m-d H:i:s', strtotime(trim($entry->comment->date))),
						'comment_content' => _btc_filter_content($entry->comment->content),
						'comment_approved' => ($ignore_own_blog && $is_own_blog) ? 'ignored' : ((get_option(BTC_MODERATION_OPTION)) ? '0' : '1'),
						'comment_agent' => 'btc_' . $entry['src'],
						'comment_type' => '',
						'comment_parent' => ''
					);
					$desc = array(
						'comment_src' => $entry['src'],
						'site_url' => trim($entry->blog->url),
						'site_title' => trim($entry->blog->title),
						'post_url' => trim($entry->post->url),
						'post_title' => ($entry->post->title) ? trim($entry->post->title) : trim($entry->blog->title),
						'comment_url' => trim($entry->comment->url)
					);
				} elseif ($entry['type'] == 'tweet') {
					$is_own_blog = 0;
					$is_retweet = (substr(strtolower($entry->tweet_text), 0, 2) == 'rt') ? 1 : 0;
					$commentdata = array(
						'comment_post_ID' => $btc_post->ID,
						'comment_author' => trim($entry->tweet_from_user),
						'comment_author_email' => '',
						'comment_author_url' => 'http://twitter.com/' . trim($entry->tweet_from_user),
						'comment_author_IP' => '',
						'comment_date' => date('Y-m-d H:i:s', strtotime(trim($entry->tweet_created_at)) + get_option('gmt_offset')*3600),
						'comment_date_gmt' => date('Y-m-d H:i:s', strtotime(trim($entry->tweet_created_at))),
						'comment_content' => _btc_filter_content($entry->tweet_text),
						'comment_approved' => ($ignore_retweets && $is_retweet) ? 'ignored' : ((get_option(BTC_MODERATION_OPTION)) ? '0' : '1'),
						'comment_agent' => 'btc_twitter',
						'comment_type' => '',
						'comment_parent' => ''
					);
					$desc = array(
						'comment_src' => 'twitter',
						'site_url' => 'http://twitter.com/',
						'site_title' => 'Twitter',
						'post_url' => 'http://twitter.com/' . trim($entry->tweet_from_user) . '/statuses/' . $entry->tweet_id,
						'post_title' => trim($entry->tweet_text),
						'comment_url' => 'http://twitter.com/' . trim($entry->tweet_from_user) . '/statuses/' . $entry->tweet_id
					);
				}
				$commentdata['comment_content'] .= _btc_comment_desc($desc);
				if (!btc_db_dupe_comment($commentdata)) {
					if (get_option(BTC_AKISMET_OPTION) && function_exists('akismet_auto_check_comment')) {
						$commentdata = _btc_akismet_check_comment($commentdata);
					}
					if (in_array($desc['comment_src'], $filters['srcs'])) {
						$commentdata['comment_approved'] = 'd_' . $commentdata['comment_approved'];
					}
			   		if ($comment_ID = wp_insert_comment($commentdata)) {
			   			$dupes = btc_db_dupe_clean($comment_ID);
			   			if (count($dupes) > 0) {
			   				btc_db_delete_dupe_comment($comment_ID);
			   				continue;
			   			}
			   			if ($is_own_blog) {
			   				btc_db_add_own_blog_comment($btc_post->ID, $comment_ID);
			   			} elseif ($is_retweet) {
				   			btc_db_add_retweet($btc_post->ID, $comment_ID);
			   			}
			   			btc_db_add_comment_type($btc_post->ID, $commentdata['comment_agent'], $comment_ID);
						$new++;
					}
				}
			}	

			btc_log('Inserted ' . $new . ' new comments.', 'debug');	

			// paginate
			if ($new) {
				$btc_post->last_import_date_gmt = gmdate('Y-m-d H:i:s');
				$btc_post->hits = (int) $btc_post->hits + $new;
				btc_db_update_post($btc_post);
				if ($new == (int) $itemsperpage && isset($xml->next_page)) {
					$params['page'] = (int) $xml->next_page;
				} else {
					$btc_counts = btc_db_comment_counts($btc_post->ID);
					btc_db_set_comment_counts($btc_post->ID, $btc_counts);
					if (function_exists('wp_update_comment_count')) {
						wp_update_comment_count($btc_post->ID);
					}
					$btc_summary = btc_db_comment_summary($btc_post->ID);
					btc_db_set_comment_summary($btc_post->ID, $btc_summary);
					$processed = true;
				}
			} else {
				$btc_post->last_import_date_gmt = gmdate('Y-m-d H:i:s');
				$btc_post->misses = (int) $btc_post->misses + 1;
				btc_db_update_post($btc_post);
				$processed = true;
			}
		} else {
			if (!$xml || !empty($xml['error']['errorCode']) || empty($xml['feed']['comments'])) {
				if (!empty($xml['error']['errorCode']) && $xml['error']['errorCode'] == 'related-conversations-not-found') {
					$btc_post->last_import_date_gmt = gmdate('Y-m-d H:i:s');
					$btc_post->misses = (int) $btc_post->misses + 1;
					btc_db_update_post($btc_post);
					btc_log('No related conversations.', 'error');
					$error = 2;
				} else if ($xml && empty($xml['feed']['comments']['entry']) && (int) $xml['feed']['startindex'] > (int) $xml['feed']['totalresults']) {
					btc_log('Paginated too far.', 'error');
					//..
				} else {
					btc_log('Unknown XML error.', 'error');
					$error = 3;
				}
				break;
			}

			if (empty($xml['feed']['comments']['entry']) || !count($xml['feed']['comments']['entry'])) {
				btc_log('No comments.', 'debug');
				break;
			}

			require_once BTC_DIR . '/db.php';

			$new = 0;

			btc_log('Comments received: ' . count($xml['feed']['comments']['entry']), 'debug');
			$comments = array();
			$ignore_own_blog = get_option(BTC_IGNORE_OWN_BLOG_OPTION);
			$ignore_retweets = get_option(BTC_IGNORE_RETWEETS_OPTION);
			$siteurl = get_option('siteurl');
			$site_pieces = parse_url($siteurl);
			$site_domain = (isset($site_pieces['host'])) ? trim($site_pieces['host'], '/') : '';
			$site_path = (isset($site_pieces['path'])) ? trim($site_pieces['path'], '/') : '';
			foreach ($xml['feed']['comments']['entry'] as $key => $value) {
				if (is_int($key)) {
					$entry = $value;
					$entry['type'] = $type;
					$entry['src'] = $src;
				} else {
					$type = $value['type'];
					$src = (isset($value['src'])) ? $value['src'] : '';
					continue;
				}
				if ($entry['type'] == 'comment') {
					$entry_pieces = parse_url($entry['blog']['url']);
					$entry_domain = (isset($entry_pieces['host'])) ? trim($entry_pieces['host'], '/') : '';
					$entry_path = (isset($entry_pieces['path'])) ? trim($entry_pieces['path'], '/') : '';
					$is_own_blog = ($site_domain == $entry_domain && $site_path == $entry_path) ? 1 : 0;
					$is_retweet = 0;
					$commentdata = array(
						'comment_post_ID' => $btc_post->ID,
						'comment_author' => ($entry['author']['name']) ? trim($entry['author']['name']) : 'Anonymous',
						'comment_author_email' => '',
						'comment_author_url' => trim($entry['author']['url']),
						'comment_author_IP' => '',
						'comment_date' => date('Y-m-d H:i:s', strtotime(trim($entry['comment']['date'])) + get_option('gmt_offset')*3600),
						'comment_date_gmt' => date('Y-m-d H:i:s', strtotime(trim($entry['comment']['date']))),
						'comment_content' => _btc_filter_content($entry['comment']['content']),
						'comment_approved' => ($ignore_own_blog && $is_own_blog) ? 'ignored' : ((get_option(BTC_MODERATION_OPTION)) ? '0' : '1'),
						'comment_agent' => 'btc_' . $entry['src'],
						'comment_type' => '',
						'comment_parent' => ''
					);
					$desc = array(
						'comment_src' => $entry['src'],
						'site_url' => trim($entry['blog']['url']),
						'site_title' => trim($entry['blog']['title']),
						'post_url' => trim($entry['post']['url']),
						'post_title' => ($entry['post']['title']) ? trim($entry['post']['title']) : trim($entry['blog']['title']),
						'comment_url' => trim($entry['comment']['url'])
					);
				} elseif ($entry['type'] == 'tweet') {
					$is_own_blog = 0;
					$is_retweet = (substr(strtolower($entry['tweet_text']), 0, 2) == 'rt') ? 1 : 0;
					$commentdata = array(
						'comment_post_ID' => $btc_post->ID,
						'comment_author' => trim($entry['tweet_from_user']),
						'comment_author_email' => '',
						'comment_author_url' => 'http://twitter.com/' . trim($entry['tweet_from_user']),
						'comment_author_IP' => '',
						'comment_date' => date('Y-m-d H:i:s', strtotime(trim($entry['tweet_created_at'])) + get_option('gmt_offset')*3600),
						'comment_date_gmt' => date('Y-m-d H:i:s', strtotime(trim($entry['tweet_created_at']))),
						'comment_content' => _btc_filter_content($entry['tweet_text']),
						'comment_approved' => ($ignore_retweets && $is_retweet) ? 'ignored' : ((get_option(BTC_MODERATION_OPTION)) ? '0' : '1'),
						'comment_agent' => 'btc_twitter',
						'comment_type' => '',
						'comment_parent' => ''
					);
					$desc = array(
						'comment_src' => 'twitter',
						'site_url' => 'http://twitter.com/',
						'site_title' => 'Twitter',
						'post_url' => 'http://twitter.com/' . trim($entry['tweet_from_user']) . '/statuses/' . $entry['tweet_id'],
						'post_title' => trim($entry['tweet_text']),
						'comment_url' => 'http://twitter.com/' . trim($entry['tweet_from_user']) . '/statuses/' . $entry['tweet_id']
					);
				}
				$commentdata['comment_content'] .= _btc_comment_desc($desc);
				if (!btc_db_dupe_comment($commentdata)) {
					if (get_option(BTC_AKISMET_OPTION) && function_exists('akismet_auto_check_comment')) {
						$commentdata = _btc_akismet_check_comment($commentdata);
					}
					if (in_array($desc['comment_src'], $filters['srcs'])) {
						$commentdata['comment_approved'] = 'd_' . $commentdata['comment_approved'];
					}
			   		if ($comment_ID = wp_insert_comment($commentdata)) {
			   			$dupes = btc_db_dupe_comment($commentdata);
			   			if (count($dupes) > 1 && $comment_ID > min($dupes)) {
			   				btc_db_delete_dupe_comment($comment_ID);
			   				continue;
			   			}
			   			if ($is_own_blog) {
			   				btc_db_add_own_blog_comment($btc_post->ID, $comment_ID);
			   			} elseif ($is_retweet) {
				   			btc_db_add_retweet($btc_post->ID, $comment_ID);
			   			}
			   			btc_db_add_comment_type($btc_post->ID, $commentdata['comment_agent'], $comment_ID);
						$new++;
					}
				}
			}	

			btc_log('Inserted ' . $new . ' new comments.', 'debug');	

			// paginate
			if ($new) {
				$btc_post->last_import_date_gmt = gmdate('Y-m-d H:i:s');
				$btc_post->hits = (int) $btc_post->hits + $new;
				btc_db_update_post($btc_post);
				if ($new == (int) $itemsperpage && isset($xml['feed']['next_page'])) {
					$params['page'] = (int) $xml['feed']['next_page'];
				} else {
					$btc_counts = btc_db_comment_counts($btc_post->ID);
					btc_db_set_comment_counts($btc_post->ID, $btc_counts);
					if (function_exists('wp_update_comment_count')) {
						wp_update_comment_count($btc_post->ID);
					}
					$btc_summary = btc_db_comment_summary($btc_post->ID);
					btc_db_set_comment_summary($btc_post->ID, $btc_summary);
					$processed = true;
				}
			} else {
				$btc_post->last_import_date_gmt = gmdate('Y-m-d H:i:s');
				$btc_post->misses = (int) $btc_post->misses + 1;
				btc_db_update_post($btc_post);
				$processed = true;
			}
		}

	} while (!$processed && !$error);

	if (!$error) {
		return array('success' => 1);
	} else if ($error == 1) {
		if ($page == 1) {
			return array('success' => 0, 'message' => 'Could not open the URL.');
		} else {
			return array('success' => 0, 'message' => 'Could not open some of the URLs.');
		}
	} else if ($error == 2) {
		return array('success' => 0, 'message' => 'No related conversations were found.');
	} else {
		return array('success' => 0, 'message' => 'An unknown error occurred.');
	}
}

/**
 * Run BTC comment against Akismet
 *
 * @global	string	$akismet_api_host
 * @global	string	$akismet_api_post
 *
 * @param	object	$comment	BTC Comment
 * @return	object				BTC Comment
 */
function _btc_akismet_check_comment($comment) {
	global $akismet_api_host, $akismet_api_port;

	$comment['user_ip']	= '127.0.0.1';
	$comment['blog'] 	= get_option('home');

	$ignore = array('HTTP_COOKIE');

	foreach ($_SERVER as $key => $value)
		if (!in_array($key, $ignore))
			$comment["$key"] = $value;

	$query_string = '';
	foreach ($comment as $key => $data)
		$query_string .= $key . '=' . urlencode(stripslashes($data)) . '&';

	$response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
	if ('true' == $response[1]) {
		$comment['comment_approved'] = 'spam';
	}

	return $comment;
}

/**
 * Generate comment description
 *
 * @param	object	$entry	Metadata from BackType API
 * @return	string			Comment description
 */
function _btc_comment_desc($entry) {
	$title = '';
	if ($entry['comment_src'] != '') {
		switch ($entry['comment_src']) {
			case BTC_SRC_BLOG:
				$source = $entry['site_title'];
				$title = ($entry['post_title'] != '') ? '&#8220;' . $entry['post_title'] . '&#8221;' : $entry['post_url'];
				break;
			case BTC_SRC_DIGG:
				$source = 'Digg';
				break;
			case BTC_SRC_REDDIT:
				$source = 'Reddit';
				break;
			case BTC_SRC_FRIENDFEED:
				$source = 'FriendFeed';
				break;
			case BTC_SRC_YC:
				$source = 'Hacker News';
				break;
			case BTC_SRC_TWITTER:
				$source = 'Twitter';
				break;
		}
		$desc = '<p><i>This comment was originally posted on <a href="' . $entry['comment_url'] . '" rel="nofollow"' . (($title != '') ? ' title="' . $title . '"' : '') . '>' . $source . '</a></i></p>';
	}
	return $desc;
}

/**
 * Unserialize xml response
 *
 * @param	string	$xml	XML response
 * @return	object			Returns unserialized XML
 */
function _btc_xml_unserialize($xml) {
	if (class_exists('SimpleXMLElement')) {
		return new SimpleXMLElement($xml);
	} else {
		require_once BTC_DIR . '/parser_php4.php';
		$xml_parser = &new btc_XML();
		$data = &$xml_parser->parse($xml);
		$xml_parser->destruct();
		return $data;
	}
}

/**
 * Filter string
 *
 * @param	string	$content	Unfiltered string
 * @return	string				Filtered string
 */
function _btc_filter_content($content) {
	return wpautop(make_clickable(convert_chars(wptexturize(strip_tags(trim($content))))));
}

/**
 * Do URL open
 *
 * @uses	_btc_url_open()
 *
 * @param	string	$url	The URL to open
 * @param	array	$args	Arguments
 * @param	int		$tries	Number of tries
 * @return	array			The HTTP response
 */
function _btc_url_open($url, $args=array('timeout' => 10), $tries=1) {
	if (function_exists('wp_remote_get')) {
		$result = wp_remote_get($url, $args);
		if (is_wp_error($result)) {
			if ($tries < 3 && $result->get_error_code() == 'http_request_failed') {
				return _btc_url_open($url, $args, ++$tries);
			} else {
				return false;
			}			
		} else {
			return $result;
		}
	} else {	
		if (!class_exists('Snoopy')) {
			require_once ABSPATH . 'wp-includes/class-snoopy.php';
		}
		$snoopy = new Snoopy();
		if (!$snoopy->fetch($url) || !$snoopy->results) {
			if ($tries < 3) {
				return _btc_url_open($url, $args, ++$tries);
			} else {
				return false;
			}
		}
		return array('body' => $snoopy->results, 'response' => array('code' => $snoopy->status));
	}
}

/**
 * Do non-blocking URL open
 *
 * @param	string	$url	URL to open
 * @return	bool			True is successful; false otherwise
 */
function _btc_url_open_non_blocking($url) {
	$pieces = parse_url($url);
	$fp = fsockopen($pieces['host'], 80, $errno, $errstr, 1);
	
	if (!$fp) {
		return false;
	} else {
		stream_set_blocking($fp, 0);
		$postdata = (isset($pieces['query'])) ? '?' . $pieces['query'] : '';
		$out = "GET " . $pieces['path'] . $postdata . " HTTP/1.1\r\n";
		$out.= "Host: " . $pieces['host'] . "\r\n";
		$out.= "Connection: Close\r\n\r\n";
		
		fwrite($fp, $out);
		fclose($fp);
		return true;
	 }
}
?>
