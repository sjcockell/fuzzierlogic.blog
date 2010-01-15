<?php

require_once ABSPATH . 'wp-includes/compat.php';

add_action('admin_menu', 'btc_admin_menu');

/**
 * Add BackType Connect menu pages
 */
function btc_admin_menu() {
	add_submenu_page('options-general.php', 'BackType Connect', 'BackType Connect', 8, 'BackType Connect', 'btc_settings_submenu');
}

/**
 * BackType Connect settings page
 *
 * @uses	btc_db_clear_comment_counts()
 * @uses	btc_db_get_own_blog_comments()
 * @uses	btc_db_get_retweets()
 * @uses	btc_db_update_filtered()
 * @uses	btc_db_update_ignored()
 * @uses	btc_disable_plugin()
 * @uses	btc_enable_plugin()
 * @uses	_btc_display_message()
 * @uses	_btc_url_open()
 */
function btc_settings_submenu() {
	if (!current_user_can('manage_options')) {
		die();
	}

	if (isset($_POST[BTC_API_KEY_OPTION])) {	
		$error = false;
		$authenticated = false;
		$tries = 0;
		$processed = true;

		do {
			$btc_api_key = $_POST[BTC_API_KEY_OPTION];
			if ($btc_api_key != get_option(BTC_API_KEY_OPTION)) {
				// authenticate
				$url = get_option('siteurl');
				$params	= array('identifier' => 'btc', 'key' => $btc_api_key, 'url' => $url);
				if (!$contents = _btc_url_open(BTC_API_PING_URL . '?' . http_build_query($params, null, '&'))) {
					$tries++;
				} else {
					$xml = _btc_xml_unserialize($contents['body']);
					unset($contents);
					if (class_exists('SimpleXMLElement')) {
						if (!empty($xml->errorCode)) {
							$error = 'An error occured authenticating with BackType Connect. Please contact <a href="mailto:support@backtype.com">support@backtype.com</a>';
							break;
						}
					} else {
						if (!empty($xml['error']['errorCode'])) {
							$error = 'An error occured authenticating with BackType Connect. Please contact <a href="mailto:support@backtype.com">support@backtype.com</a>';
							break;
						}
					}
					update_option(BTC_API_KEY_OPTION, $btc_api_key);
					$authenticated = true;
					$processed = true;
					break;
				}
			} else {
				$authenticated = true;
				$processed = true;
				break;
			}
			if ($tries == 3) {
				$error = 'Could not contact BackType, are you sure your webserver is online? If so contact <a href="mailto:support@backtype.com">support@backtype.com</a>';
			}
		} while (!$processed && !$error);
		
		if ($authenticated) {
			if ($_POST['control'] == 1) {
				require_once BTC_DIR . '/install.php';
				if (get_option(BTC_ENABLED_OPTION)) {
					btc_disable_plugin();
					_btc_display_message(__('BackType Connect is now disabled.'), 'success');
				} else {
					btc_enable_plugin();
					_btc_display_message(__('BackType Connect is now enabled.'), 'success');
				}
			} else {
				require_once BTC_DIR . '/db.php';
				$btc_comment_sort = (isset($_POST[BTC_COMMENT_SORT_OPTION]) && in_array($_POST[BTC_COMMENT_SORT_OPTION], array('mixed', 'end'))) ? $_POST[BTC_COMMENT_SORT_OPTION] : 'mixed';
				$btc_summary = (isset($_POST[BTC_SUMMARY_OPTION])) ? true : false;
				$btc_more_comments = (isset($_POST[BTC_MORE_COMMENTS_OPTION])) ? true : false;
				$btc_ignore_own_blog = (isset($_POST[BTC_IGNORE_OWN_BLOG_OPTION])) ? true : false;
				$btc_ignore_retweets = (isset($_POST[BTC_IGNORE_RETWEETS_OPTION])) ? true : false;
				$btc_moderation = (isset($_POST[BTC_MODERATION_OPTION])) ? true : false;
				$btc_akismet = (isset($_POST[BTC_AKISMET_OPTION])) ? true : false;

				update_option(BTC_COMMENT_SORT_OPTION, $btc_comment_sort);
				update_option(BTC_SUMMARY_OPTION, $btc_summary);
				update_option(BTC_MORE_COMMENTS_OPTION, $btc_more_comments);
				$clear_btc_counts = false;
				if ($btc_ignore_own_blog != get_option(BTC_IGNORE_OWN_BLOG_OPTION)) {
					$clear_btc_counts = true;
					$btc_own_blog_comments = btc_db_get_own_blog_comments();
					btc_db_update_ignored($btc_ignore_own_blog, $btc_own_blog_comments);
					update_option(BTC_IGNORE_OWN_BLOG_OPTION, $btc_ignore_own_blog);
				}
				if ($btc_ignore_retweets != get_option(BTC_IGNORE_RETWEETS_OPTION)) {
					$clear_btc_counts = true;
					$btc_retweets = btc_db_get_retweets();
					btc_db_update_ignored($btc_ignore_retweets, $btc_retweets);
					update_option(BTC_IGNORE_RETWEETS_OPTION, $btc_ignore_retweets);
				}
				update_option(BTC_MODERATION_OPTION, $btc_moderation);
				update_option(BTC_AKISMET_OPTION, $btc_akismet);

				$options = array(BTC_OPTION_DISABLED, BTC_OPTION_ENABLED);
				$btc_src_blog = (isset($_POST[BTC_SRC_BLOG_OPTION]) && in_array((int) $_POST[BTC_SRC_BLOG_OPTION], $options)) ?
										(int) $_POST[BTC_SRC_BLOG_OPTION] : BTC_OPTION_DISABLED;
				$btc_src_digg = (isset($_POST[BTC_SRC_DIGG_OPTION]) && in_array((int) $_POST[BTC_SRC_DIGG_OPTION], $options)) ?
										(int) $_POST[BTC_SRC_DIGG_OPTION] : BTC_OPTION_DISABLED;
				$btc_src_reddit = (isset($_POST[BTC_SRC_REDDIT_OPTION]) && in_array((int) $_POST[BTC_SRC_REDDIT_OPTION], $options)) ?
										(int) $_POST[BTC_SRC_REDDIT_OPTION] : BTC_OPTION_DISABLED;
				$btc_src_friendfeed = (isset($_POST[BTC_SRC_FRIENDFEED_OPTION]) && in_array((int) $_POST[BTC_SRC_FRIENDFEED_OPTION], $options)) ?
										(int) $_POST[BTC_SRC_FRIENDFEED_OPTION] : BTC_OPTION_DISABLED;
				$btc_src_yc = (isset($_POST[BTC_SRC_YC_OPTION]) && in_array((int) $_POST[BTC_SRC_YC_OPTION], $options)) ?
										(int) $_POST[BTC_SRC_YC_OPTION] : BTC_OPTION_DISABLED;
				$btc_src_twitter = (isset($_POST[BTC_SRC_TWITTER_OPTION]) && in_array((int) $_POST[BTC_SRC_TWITTER_OPTION], $options)) ?
										(int) $_POST[BTC_SRC_TWITTER_OPTION] : BTC_OPTION_DISABLED;

				// update filters
				if ($btc_src_blog != get_option(BTC_SRC_BLOG_OPTION)) {
					$clear_btc_counts = true;
					btc_db_update_filtered('btc_blog', $btc_src_blog);
					update_option(BTC_SRC_BLOG_OPTION, $btc_src_blog);
				}
				if ($btc_src_digg != get_option(BTC_SRC_DIGG_OPTION)) {
					$clear_btc_counts = true;
					btc_db_update_filtered('btc_digg', $btc_src_digg);
					update_option(BTC_SRC_DIGG_OPTION, $btc_src_digg);
				}
				if ($btc_src_reddit != get_option(BTC_SRC_REDDIT_OPTION)) {
					$clear_btc_counts = true;
					btc_db_update_filtered('btc_reddit', $btc_src_reddit);
					update_option(BTC_SRC_REDDIT_OPTION, $btc_src_reddit);
				}
				if ($btc_src_friendfeed != get_option(BTC_SRC_FRIENDFEED_OPTION)) {
					$clear_btc_counts = true;
					btc_db_update_filtered('btc_friendfeed', $btc_src_friendfeed);
					update_option(BTC_SRC_FRIENDFEED_OPTION, $btc_src_friendfeed);
				}
				if ($btc_src_yc != get_option(BTC_SRC_YC_OPTION)) {
					$clear_btc_counts = true;
					btc_db_update_filtered('btc_yc', $btc_src_yc);
					update_option(BTC_SRC_YC_OPTION, $btc_src_yc);
				}
				if ($btc_src_twitter != get_option(BTC_SRC_TWITTER_OPTION)) {
					$clear_btc_counts = true;
					btc_db_update_filtered('btc_twitter', $btc_src_twitter);
					update_option(BTC_SRC_TWITTER_OPTION, $btc_src_twitter);
				}
				
				if ($clear_btc_counts) {
					btc_db_clear_comment_counts();
				}

				_btc_display_message(__('Settings updated.'), 'success');
			}
		}
		if ($error) {
			_btc_display_message(__($error), 'error');
		}
	}

	include(BTC_DIR . '/admin-settings.php');
}
?>