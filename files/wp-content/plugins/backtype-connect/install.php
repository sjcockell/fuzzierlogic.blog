<?php

define('BTC_OFFSET_OPTION', 'btc_offset_option');
define('BTC_OFFSET_ARG', 0);

require_once ABSPATH . 'wp-includes/compat.php';

/**
 * Install BackType Connect
 *
 * @uses	btc_register_plugin()
 * @uses	btc_default_options()
 *
 * @return	bool		True if successful
 */
function btc_do_install() {
	if (btc_register_plugin()) {
		btc_default_options(true);
		return true;
	}
	return false;
}

/**
 * Uninstall BackType Connect
 *
 * @uses	btc_disable_plugin()
 * @uses	btc_db_clear_own_blog_comments()
 * @uses	btc_db_clear_retweets()
 * @uses	btc_db_clear_all_comment_type()
 * @uses	btc_db_clear_comment_counts()
 * @uses	btc_db_clear_comment_summary()
 * @uses	btc_db_clear_comments()
 */
function btc_do_uninstall() {
	require_once BTC_DIR . '/db.php';
	btc_disable_plugin();
	delete_option(BTC_API_KEY_OPTION);
	btc_db_clear_comments();
	btc_db_clear_comment_counts();
	btc_db_clear_comment_summary();
	btc_db_clear_own_blog_comments();
	btc_db_clear_retweets();
	btc_db_clear_all_comment_type('btc_' . BTC_SRC_BLOG);
	btc_db_clear_all_comment_type('btc_' . BTC_SRC_DIGG);
	btc_db_clear_all_comment_type('btc_' . BTC_SRC_REDDIT);
	btc_db_clear_all_comment_type('btc_' . BTC_SRC_FRIENDFEED);
	btc_db_clear_all_comment_type('btc_' . BTC_SRC_YC);
	btc_db_clear_all_comment_type('btc_' . BTC_SRC_TWITTER);
	btc_db_clear_posts();
}

/**
 * Register plugin
 *
 * @uses	_btc_url_open()
 * @uses	_btc_xml_unserialize()
 *
 * @return	bool	True if successful; false otherwise
 */
function btc_register_plugin() {
	$processed = false;
	$params = array('identifier' => 'btc', 'url' => get_option('siteurl'));
	btc_log('Registering plugin: ' . BTC_API_REGISTER_URL . '?' . http_build_query($params, null, '&'), 'debug');
	if (!$contents = _btc_url_open(BTC_API_REGISTER_URL . '?' . http_build_query($params, null, '&'))) {
		return false;
	}

	$xml = _btc_xml_unserialize($contents['body']);
	unset($contents);
	
	if (class_exists('SimpleXMLElement')) {
		if (!empty($xml->key)) {
			add_option(BTC_API_KEY_OPTION, trim($xml->key));
			$processed = true;
		}
	} else {
		if (!empty($xml['feed']['key'])) {
			add_option(BTC_API_KEY_OPTION, trim($xml['feed']['key']));
			$processed = true;
		}
	}
	return $processed;
}

/**
 * Schedule BackType API Calls
 */
function btc_enable_plugin() {
	$hour = date('H');
	$minute = date('i') % 5;
	$current_time = mktime($hour, $minute, 0);
	wp_schedule_event($current_time, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 0));
	wp_schedule_event($current_time + 60 * 5, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 5));
	wp_schedule_event($current_time + 60 * 10, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 10));
	wp_schedule_event($current_time + 60 * 15, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 15));
	wp_schedule_event($current_time + 60 * 20, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 20));
	wp_schedule_event($current_time + 60 * 25, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 25));
	wp_schedule_event($current_time + 60 * 30, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 30));
	wp_schedule_event($current_time + 60 * 35, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 35));
	wp_schedule_event($current_time + 60 * 40, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 40));
	wp_schedule_event($current_time + 60 * 45, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 45));
	wp_schedule_event($current_time + 60 * 50, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 50));
	wp_schedule_event($current_time + 60 * 55, 'hourly', 'btc_connect', array(BTC_OFFSET_ARG => $minute + 55));
	add_option(BTC_OFFSET_OPTION, $minute);
	update_option(BTC_ENABLED_OPTION, true);
}

/**
 * Remove Scheduled BackType API Calls
 */
function btc_disable_plugin() {
	$minute = get_option(BTC_OFFSET_OPTION);
	wp_clear_scheduled_hook('btc_connect', $minute + 0);
	wp_clear_scheduled_hook('btc_connect', $minute + 5);
	wp_clear_scheduled_hook('btc_connect', $minute + 10);
	wp_clear_scheduled_hook('btc_connect', $minute + 15);
	wp_clear_scheduled_hook('btc_connect', $minute + 20);
	wp_clear_scheduled_hook('btc_connect', $minute + 25);
	wp_clear_scheduled_hook('btc_connect', $minute + 30);
	wp_clear_scheduled_hook('btc_connect', $minute + 35);
	wp_clear_scheduled_hook('btc_connect', $minute + 40);
	wp_clear_scheduled_hook('btc_connect', $minute + 45);
	wp_clear_scheduled_hook('btc_connect', $minute + 50);
	wp_clear_scheduled_hook('btc_connect', $minute + 55);
	delete_option(BTC_OFFSET_OPTION);
	update_option(BTC_ENABLED_OPTION, false);
}

/**
 * BackType Connect default options
 *
 * @param	bool	$install	To specify use during installation
 * @return	mixed				If not on installation, array of options
 */
function btc_default_options($install=false) {
	if ($install) {
		if (get_option(BTC_VERSION_OPTION)) {
			update_option(BTC_VERSION_OPTION, BTC_VERSION);
		} else {
			add_option(BTC_VERSION_OPTION, BTC_VERSION);
		}
		if (!get_option(BTC_ENABLED_OPTION))
			add_option(BTC_ENABLED_OPTION, false);
		if (!get_option(BTC_COMMENT_SORT_OPTION))
			add_option(BTC_COMMENT_SORT_OPTION, 'mixed');
		if (!get_option(BTC_SUMMARY_OPTION))
			add_option(BTC_SUMMARY_OPTION, true);
		if (!get_option(BTC_MORE_COMMENTS_OPTION))
			add_option(BTC_MORE_COMMENTS_OPTION, true);
		if (!get_option(BTC_IGNORE_OWN_BLOG_OPTION))
			add_option(BTC_IGNORE_OWN_BLOG_OPTION, true);
		if (!get_option(BTC_IGNORE_RETWEETS_OPTION))
			add_option(BTC_IGNORE_RETWEETS_OPTION, true);
		if (!get_option(BTC_MODERATION_OPTION))
			add_option(BTC_MODERATION_OPTION, (get_option('comment_moderation') == 1) ? true : false);
		if (!get_option(BTC_AKISMET_OPTION))
			add_option(BTC_AKISMET_OPTION, false);
		if (!get_option(BTC_SRC_BLOG_OPTION))
			add_option(BTC_SRC_BLOG_OPTION, 1);
		if (!get_option(BTC_SRC_DIGG_OPTION))
			add_option(BTC_SRC_DIGG_OPTION, 1);
		if (!get_option(BTC_SRC_REDDIT_OPTION))
			add_option(BTC_SRC_REDDIT_OPTION, 1);
		if (!get_option(BTC_SRC_FRIENDFEED_OPTION))
			add_option(BTC_SRC_FRIENDFEED_OPTION, 1);
		if (!get_option(BTC_SRC_YC_OPTION))
			add_option(BTC_SRC_YC_OPTION, 1);
		if (!get_option(BTC_SRC_TWITTER_OPTION))
			add_option(BTC_SRC_TWITTER_OPTION, 1);
		if (!get_option(BTC_DEBUG_OPTION))
			add_option(BTC_DEBUG_OPTION, false);
	} else {
		return array(BTC_VERSION_OPTION => $btc_version,
			BTC_ENABLED_OPTION => false,
			BTC_COMMENT_SORT_OPTION => 'mixed',
			BTC_SUMMARY_OPTION => true,
			BTC_MORE_COMMENTS_OPTION => true,
			BTC_IGNORE_OWN_BLOG_OPTION => true,
			BTC_IGNORE_RETWEETS_OPTION => true,
			BTC_MODERATION_OPTION => (get_option('comment_moderation') == 1) ? true : false,
			BTC_AKISMET_OPTION => false,
			BTC_SRC_BLOG_OPTION => 1,
			BTC_SRC_DIGG_OPTION => 1,
			BTC_SRC_REDDIT_OPTION => 1,
			BTC_SRC_FRIENDFEED_OPTION => 1,
			BTC_SRC_YC_OPTION => 1,
			BTC_SRC_TWITTER_OPTION => 1,
			BTC_DEBUG_OPTION => false
		);
	}
}
?>