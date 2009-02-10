<?php
/*
 Plugin Name: FriendFeed Comments
 Version: 1.6.4
 Plugin URI: http://blog.slaven.net.au/wordpress-plugins/friendfeed-comments-wordpress-plugin/
 Description: Places the comments & likes from FriendFeed on each post from your blog under the normal comments section on the post page.
 Author: Glenn Slaven
 Author URI: http://blog.slaven.net.au/

 Copyright 2008 Glenn Slaven  (email : gdalziel@gmail.com)
 */
if (!class_exists('FriendFeed')) {
	require_once("friendfeed.php");
}

class FriendFeedPostComments
{
	var $options_key = 'friendfeed_options';
	var $apikeys_key = 'friendfeed_apikeys';
	var $titles_key = 'friendfeed_id';
	var $table_name = 'friendfeedcomments';
	var $cache_key = 'friendfeed_discussions';
	var $cookie_key = 'wp_ffcomments';
	var $max_posts_to_load = 150;
	var $page_size = 30;
	var $plugin_name = "FriendFeed Comments";
	var $is_debug = false;
	var $plugin_path;

	/**
	 * Constructor.  Setup the actions & filters and set default values
	 *
	 * @return FriendFeedPostComments
	 */
	function FriendFeedPostComments() {
		global $wpdb;

		add_action('admin_menu', array(&$this, '_create_options_menu_item'));
		add_action('update_friendfeedcomments_hook', array(&$this, 'update_friendfeedcomments'));
		add_action('wp_head', array(&$this, '_add_page_header'));
		add_action('edit_form_advanced', array(&$this, '_add_edit_form_section'));
		add_filter('save_post', array(&$this, '_update_post_discussionid'), 10, 2);

		register_activation_hook(__FILE__, array(&$this, '_install'));
		register_deactivation_hook(__FILE__, array(&$this, '_uninstall'));

		$this->plugin_path = get_option('home') . '/' . PLUGINDIR . '/friendfeed-comments/';
		$this->table_name = $wpdb->prefix . $this->table_name;
	
		$options = get_option($this->options_key);
		if ($options['includeprototype']) {
			wp_enqueue_script('prototype');
		}
	}

	/**
	 * Callback to create the Settings menu option
	 *
	 */
	function _create_options_menu_item() {
		add_options_page($this->plugin_name, $this->plugin_name, 9, __FILE__, array(&$this, 'options_page'));
		add_action('admin_notices', array(&$this, '_admin_notices'));
	}

	/**
	 * Callback to display admin notices if needed (reminder to set the username)
	 *
	 */
	function _admin_notices() {
		$options = get_option($this->options_key);
		if ((!$options || !$options['nickname']) && !$_POST['ff_nickname']) {
			echo "<div class='updated' style='background-color:#f66;'><p><a href=\"options-general.php?page=friendfeed-comments/friendfeed-comments.php\">FriendFeed Comments</a> does not have a nickname set.  You need to enter your FriendFeed nickname for the comments to be loaded.</p></div>";
		}
	}

	/**
	 * Callback to setup the plugin
	 *
	 */
	function _install() {
		$this->create_table();
		wp_schedule_event( time(), 'hourly', 'update_friendfeedcomments_hook' );
		$options = get_option($this->options_key);
		if (!$options) {
			$options['nickname'] = '';
			$options['apikey'] = '';
			$options['servicename'] = 'blog';
			$options['showcomments'] = false;
			$options['headline'] = false;
			$options['showform'] = true;
			$options['usestyles'] = true;
			$options['includeprototype'] = true;
			update_option($this->options_key, $options);
		} else { 
			$options['includeprototype'] = true;
		}
		update_option($this->options_key, $options);
	}

	/**
	 * Callback to unstall the plugin.
	 *
	 */
	function _uninstall() {
		wp_clear_scheduled_hook('update_friendfeedcomments_hook');
	}

	/**
	 * Callback to add the needed items to the page header
	 *
	 */
	function _add_page_header() {
		$options = get_option($this->options_key);

		if ($options['usestyles']) {
			print "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->plugin_path ."defaultStyle.css\" />\n";
		}

		print "\t<script type=\"text/javascript\">var friendFeedServicePath = '$this->plugin_path';</script>\n";
		print "\t<script type=\"text/javascript\" src=\"{$this->plugin_path}friendfeed-comments.js\"></script>\n";

	}

	/**
	 * Add a section to the edit page to select the post
	 *
	 */
	function _add_edit_form_section() {
		global $post;

		if ($_REQUEST['reloadff']) {
			$this->update_friendfeedcomments();
		}

		$titles = get_option($this->titles_key);

		if ($post && $post->ID && is_array($titles) && count($titles) > 0) {
			$discussion = $this->get_discussion_for_post($post->ID);
			?>
<div id="friendfeeddiv" class="postbox">
	<h3><a class="togbox">+</a>FriendFeed Comments post</h3>
	<div class="inside">
		<p>Hopefully you won't need to change this. The FriendFeed Comments
		plugin <em>should</em> link your post to the item on FriendFeed, but
		sometimes it can't. This is often due to the title of the post changing,
		but sometimes it just doesn't work. So if the wrong post, or none, is
		selected here, pick the right one and click save.</p>
			<?php
			if ($discussion) {
				?>
		<div id="friendfeed_current_entry">
			<strong>Linked to <a href="http://friendfeed.com/e/<?php echo $discussion->entry_id; ?>" target="_BLANK"><?php echo $discussion->title; ?> on FriendFeed</a></strong>
			[<a href="" id="friendfeed_change_link">Change</a>] 
			<script type="text/javascript">Event.observe($('friendfeed_change_link'), 'click', function(e) { $('friendfeed_current_entry').toggle(); $('friendfeed_change_entry').toggle(); Event.stop(e);});</script>
		</div>
<?php
			}
?>
		<div id="friendfeed_change_entry" <?php echo ($discussion ? ' style="display:none;"' : ''); ?>>
			<select id="friendfeedcomments-post" name="friendfeedcomments-post">
				<option value="">Select...</option>
			<?php
			foreach (array_keys($titles) as $key) {
				echo "\t\t<option value=\"$key\">{$titles[$key]}</option>";
			}
			?>
			</select>
			<a href="post.php?action=edit&post=<?=$post->ID?>&reloadff=1#friendfeeddiv">Refresh list</a>
		</div>
	</div>
</div>
	<?php
		}
	}
	
	/**
	 * Filter callled on the save_post hook, updates which FriendFeed entry the post is linked to
	 *
	 * @param int $post_id
	 * @param object $post
	 */
	function _update_post_discussionid($post_id, $post) {
		if ($_POST['friendfeedcomments-post'] && is_numeric($_POST['post_ID'])) {
			$this->set_post_for_entry($_POST['friendfeedcomments-post'], $_POST['post_ID']);
			$this->update_friendfeedcomments();
		}
	}
	
	/**
	 * Check if the debug flag is set by either in-code or by the ffdebugflag querystring value
	 *
	 * @return bool
	 */
	function is_debug() {
		return $this->is_debug || $_REQUEST['ffdebugflag'];
	}

	/**
	 * Delete the stored username & password for the user & remove the cookie
	 *
	 */
	function remove_authentication() {
		if ($_COOKIE[$this->cookie_key]) {
			$hash = $_COOKIE[$this->cookie_key];

			$apikeys = get_option($this->apikeys_key);
			if (!is_array($apikey)) {
				$apikeys = unserialize($apikeys);
			}

			if (is_array($apikeys)) {
				unset($apikeys[$hash]);
			}

			update_option($this->apikeys_key, $apikeys);
			setcookie($this->cookie_key, '', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
		}
	}

	/**
	 * Update the apikey for the user
	 *
	 * @param string $username
	 * @param string $apikey
	 */
	function update_apikey($username, $apikey) {
		$apikeys = get_option($this->apikeys_key);
		if (!is_array($apikeys)) {
			$apikeys = array();
		}

		$hash = md5($username + time());

		$apikeys[$hash] = array($username, $apikey);

		update_option($this->apikeys_key, $apikeys);
		setcookie($this->cookie_key, $hash, time() + 31536000, COOKIEPATH, COOKIE_DOMAIN);
	}

	/**
	 * Get the username & apikey stored for a user given the cookie
	 *
	 * @return array
	 */
	function get_authentication() {
		$auth = false;

		if ($_COOKIE[$this->cookie_key]) {
			$hash = $_COOKIE[$this->cookie_key];
			$apikeys = get_option($this->apikeys_key);

			if (!is_array($apikeys)) {
				$apikeys = unserialize($apikeys);
			}

			if (is_array($apikeys)) {
				$auth = $apikeys[$hash];
			}
		}

		return $auth;
	}

	/**
	 * Format the title into a normalised string that can be used as a hash key
	 * The inputed title has most non-alphanumeric characters replaced, then it's urlencoded
	 * then md5 hashed
	 *
	 * @param string $title The input title
	 * @return string The encoded string hash
	 */
	function format_title($title) {
		$normalised = $title;
		$normalised = str_replace(array("‘", "’", "“", "”", "`", '"', "'", ',', ':', urldecode('%93'), urldecode('&#8217;'), urldecode('&#8216;'), urldecode('&#8220;'), urldecode('&#8221;')), '', $normalised);
		$normalised = urlencode($normalised);
		$normalised = preg_replace("/%E2%80%9./i", '', $normalised);
	
		if ($this->is_debug()) {
			$this->printr($title . ' - ' . $normalised . ' - ' . md5($normalised));
		}
	
		return md5($normalised);
	}

	/**
	 * Debug output.  Utility method that wraps print_r in <pre> tags for easier viewing in HTML
	 *
	 * @param string $string The string to display
	 * @param bool $exit If true the script will exit immediately after diplaying
	 */
	function printr($string, $exit = false) {
		print "<pre>";
		print_r($string);
		print "</pre>";
		if ($exit) {
			exit;
		}
	}

	/**
	 * Checks whether the entries table exists
	 *
	 * @global $wpdb
	 * @return bool True if the table exists
	 */
	function table_exists() {
		global $wpdb;

		return $wpdb->query($wpdb->prepare("show tables like '$this->table_name'")) > 0;
	}

	/**
	 * Create a blank version of the table, checks for existance first
	 **/
	function create_table() {
		global $wpdb;

		if (!$this->table_exists()) {
			$sql = "CREATE TABLE $this->table_name (
			entry_id varchar( 50 ) CHARACTER SET utf8 NOT NULL ,
			title varchar( 200 ) CHARACTER SET utf8 NOT NULL ,
			key_val varchar( 200 ) CHARACTER SET utf8 NOT NULL ,
			post_id int( 10 ) unsigned default NULL ,
			comments longtext CHARACTER SET utf8 NOT NULL ,
			likes longtext CHARACTER SET utf8 NOT NULL ,
			created_on datetime NOT NULL ,
			PRIMARY KEY ( entry_id ) ,
			UNIQUE KEY post_id ( post_id )
			) ENGINE = InnoDB COLLATE = utf8_general_ci";
			$wpdb->query($wpdb->prepare($sql));
		}
	}

	/**
	 * Remove the data table
	 *
	 * @global $wpdb
	 * 
	 */
	function drop_table() {
		global $wpdb;

		$wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS $this->table_name ;"));
	}

	/**
	 * Remove all items from the table
	 * 
	 * @global $wpdb
	 *
	 */
	function empty_table() {
		global $wpdb;

		$wpdb->query($wpdb->prepare("TRUNCATE TABLE $this->table_name ;"));
	}

	/**
	 * Update the entry in the database
	 *
	 * @param string $entry_id
	 * @param array $comments
	 * @param array $likes
	 * @return int Positive if update was successful
	 */
	function update_entry($entry_id, $comments, $likes) {
		global $wpdb;

		if (is_array($comments)) {
			$comments = serialize($comments);
		}
		if (is_array($likes)) {
			$likes = serialize($likes);
		}

		$data = compact( 'comments', 'likes' );
		return $wpdb->update($this->table_name, $data, array('entry_id' => $entry_id));
	}

	/**
	 * Insert an entry into the database
	 *
	 * @global $wpdb
	 * @param string $entry_id
	 * @param string $title
	 * @param array $comments
	 * @param array $likes
	 */
	function add_entry($entry_id, $title, $comments, $likes) {
		global $wpdb;

		if (is_array($comments)) {
			$comments = serialize($comments);
		}
		if (is_array($likes)) {
			$likes = serialize($likes);
		}

		$return = $wpdb->query( $wpdb->prepare("INSERT INTO $this->table_name
		(entry_id, title, key_val, post_id, comments, likes, created_on)
		VALUES ('%s', '%s', '%s', null, '%s', '%s', '%s')", $entry_id, $title, $this->format_title($title), $comments, $likes, current_time('mysql')
		));

	}

	/**
	 * Link an entry to a blog post, remove other links to that entry
	 *
	 * @param string $entry_id
	 * @param int $post_id
	 * @return int Positive if successful
	 */
	function set_post_for_entry($entry_id, $post_id) {
		global $wpdb;
		
		$wpdb->query($wpdb->prepare("UPDATE $this->table_name SET post_id = null where post_id = $post_id"));
		
		return $wpdb->update($this->table_name, array('post_id' => $post_id), array('entry_id' => $entry_id));
	}

	/**
	 * Tries to get the discussions for a post.  If the discussion isn't currently linked to a post,
	 * it will try to link it based on the title
	 *
	 * @param int $post_id
	 * @param bool $check_cache
	 * @return object The discussion object from the db
	 */
	function get_discussion_for_post($post_id, $check_cache = true) {
		global $wpdb;

		$discussion = ($check_cache ? wp_cache_get('post_' . $post_id, $this->cache_key) : false);
		
		if (!is_object($discussion)) {
			//Lookup based on post_id
			$discussion = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE post_id = $post_id" ) );
			if (!$discussion) {
				//Serialise the post's title & look based on that key
				$post = get_post($post_id);

				if ($post && $post->post_title) {
					$key = $this->format_title(trim($post->post_title));

					$discussion = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE key_val = '%s'", $key));
					
					if ($discussion) {
						$discussion->post_id = $post_id;
						$this->set_post_for_entry($discussion->entry_id, $post_id);
					}
				}
			}

			//If the discussion was found, deserialise the comments & likes & add to the cache
			if ($discussion) {
				$discussion->comments = unserialize($discussion->comments);
				$discussion->likes = unserialize($discussion->likes);

				wp_cache_add('post_' . $post_id, $discussion, $this->cache_key);
			}
		}

		return $discussion;
	}

	/**
	 * Load the discussions from the FriendFeed API
	 *
	 * @return void
	 */
	function update_friendfeedcomments() {
		$options = get_option($this->options_key);
		$friendfeed = new FriendFeed($options['nickname']);

		if ($options && $friendfeed) {
			if ($this->is_debug()) {
				$this->printr("Loading FriendFeed Data");
			}

			$ff_entries = $friendfeed->fetch_user_feed($options['nickname'], $options['servicename']);
			if ($ff_entries && is_array($ff_entries->entries)) {

				//Load up the posts from FriendFeed up to the max number of posts to load set
				for ($i = $this->page_size ; $i <= $this->max_posts_to_load - $this->page_size; $i = $i + $this->page_size) {
					$temp_posts = $friendfeed->fetch_user_feed($options['nickname'], $options['servicename'], $i, $this->page_size);

					if ($temp_posts && is_array($temp_posts->entries)) {
						$ff_entries->entries = array_merge($ff_entries->entries, $temp_posts->entries);
					} else {
						$i = $this->max_posts_to_load;
					}
				}

				$blog_url = parse_url(strtolower(get_option('home')));
				$match_count = 0;

				$titles = get_option($this->titles_key);
				if (!is_array($titles)) {
					$titles = array();
				}

				foreach($ff_entries->entries as $entry) {
					$post_url = parse_url(strtolower($entry->service->profileUrl));

					$is_same_blog = 
					(
						$blog_url['host'] == $post_url['host'] || 
						$blog_url['host'] == 'www.' . $post_url['host'] || 
						'www.' . $blog_url['host'] == $post_url['host']
					) 
					&& 
					(
						($post_url['path'] == $blog_url['path'] . '/') ||
						($post_url['path'] .'/' == $blog_url['path']) ||
						($post_url['path'] == $blog_url['path'])
					);
					if ($is_same_blog) {
						
						if ($titles[$entry->id]) {
							$this->update_entry($entry->id, $entry->comments, $entry->likes);
						} else {
							$this->add_entry($entry->id, $entry->title, $entry->comments, $entry->likes);
							$titles[$entry->id] = $entry->title;
						}
						$match_count++;
					}
				}

				update_option($this->titles_key, $titles);

				if ($this->is_debug()) {
					$this->printr(count($ff_entries->entries) . " entries loaded from FriendFeed");
					$this->printr("$match_count entries loaded, " . count($titles) . " total entries stored.");
					$this->printr('** Discussions **');
					$this->printr($titles);
					$this->printr('** Posts **');
					$this->printr($ff_entries);
				}
			} else {
				return false;
			}
		}
		return true;
	}

	/**
	 * Remove all the data stored for the plugin
	 *
	 * @param bool $remove_table If true the database table will be dropped not just deleted
	 */
	function delete_all_data($remove_table = false) {
		delete_option($this->titles_key);

		if($remove_table) {
			$this->drop_table();
		} else {
			$this->empty_table();
		}
	}

	/**
	 * Function to render the options page
	 *
	 */
	function options_page() {
		$options = get_option($this->options_key);
		$error = false;
		if ($_POST["wp_friendfeed"]) {
			if ($_POST['ff_showdebug']) {
				$this->is_debug = true;
			}

			if ($_POST['Clear']) {
				$this->delete_all_data();
				if ($this->update_friendfeedcomments()) {
					$message = "<strong>Discussions reloaded.</strong>";
				} else {
					$error = "Unable to retrieve content from FriendFeed. The API may be down or there may be connectivity problems";
				}
			} elseif ($_POST['Delete']) {
				delete_option($this->options_key);
				$this->delete_all_data(true);
				unset($_POST);
				unset($options);
				$message = "<strong>All information related to this plugin has been deleted.</strong> To finish removing this plugin, deactivate it on the Plugins page";
			} else {
				if (!$error) {
					$options['nickname'] = $_POST['ff_nickname'];
					$options['servicename'] = $_POST['ff_servicename'];
					$options['showcomments'] = $_POST['ff_showcomments'];
					$options['headline'] = $_POST['ff_headline'];
					$options['showform'] = $_POST['ff_showform'];
					$options['usestyles'] = $_POST['ff_usestyles'];
					$options['includeprototype'] = $_POST['ff_includeprototype'];
					update_option($this->options_key, $options);
					if ($this->update_friendfeedcomments()) {
						$message = "<strong>Settings Saved.</strong>  Don't forget to put <code>&lt;?php wp_ffcomments(); ?&gt;</code> on your single post template to display the FriendFeed comments &amp; likes.";
					} else {
						$error = "Unable to retrieve content from FriendFeed. The API may be down or there may be connectivity problems";
					}
				}
			}

			if ($error) { 
				$message = "Error: " . $error;
			}

			if ($message) {
				?>
<div id="message" class="updated fade"<?php echo ($error ? ' style="border-color: #FF0000; font-weight: bold;"' : ''); ?>><p><?php echo $message; ?></p></div>
				<?php
			}
		}
		?>
<div class="wrap">
	<form method="post"><input type="hidden" value="true" name="wp_friendfeed" />
	<h2>FriendFeed Comments</h2>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">FriendFeed Nickname</th>
				<td><input type="text" size="40"
					value="<?php echo $options['nickname']; ?>" id="ff_nickname"
					name="ff_nickname" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Use default stylesheet</th>
				<td><input type="checkbox" name="ff_usestyles" id="ff_usestyles"
					value="1"
					<?php echo ($options['usestyles'] ? ' checked="checked"' : ''); ?> />
				<br />
				If this is checked the plugin will import the default stylesheet for
				styling the display of the comments &amp; likes lists. This is meant
				as a starting point, please feel free to use the styles &amp; adapt
				them for your site.<br />
				<a href="<?php echo $this->plugin_path; ?>defaultStyle.css"
					target="_BLANK">Click here to view the default stylesheet</a>.</td>
			</tr>
			<tr valign="top">
				<th scope="row">Enable comments &amp; likes form</th>
				<td><input type="checkbox" name="ff_showform" id="ff_showform"
					value="1"
					<?php echo ($options['showform'] ? ' checked="checked"' : ''); ?> />
				<br />
				If this is checked the plugin will display a small form under the
				comments to allow the reader to add a comment or 'like' on the post
				FriendFeed.</td>
			</tr>
			<tr valign="top">
				<th scope="row">Headline Text</th>
				<td><input type="text" size="100"
					value="<?php echo $options['headline']; ?>" id="ff_headline"
					name="ff_headline" /> <br />
				This is the text that will be shown above the comments &amp; likes.
				The tokens {comments} &amp; {likes} will be replaced with the number
				of comments &amp; likes for the post. Leave this blank to use the
				default "<em>On FriendFeed, this post was liked by x people and
				commented on x times</em>".</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show comments by default</th>
				<td><input type="checkbox" name="ff_showcomments"
					id="ff_showcomments" value="1"
					<?php echo ($options['showcomments'] ? ' checked="checked"' : ''); ?> />
				<br />
				If this is checked the FriendFeed comments &amp; likes will be shown
				by default. If not checked the user will have to click on the 'show'
				link to display them.</td>
			</tr>
			<tr valign="top">
				<th scope="row">FriendFeed Servicename</th>
				<td><input type="text" size="40"
					value="<?php echo $options['servicename']; ?>" id="ff_servicename"
					name="ff_servicename" /> <br />
				This is the name that FriendFeed knows your blog as. Unless you don't
				use the 'blog' service on FriendFeed, you probably won't need to
				change this.</td>
			</tr>
			<tr valign="top">
				<th scope="row">Include Prototype Javascript Library</th>
				<td><input type="checkbox" name="ff_includeprototype" id="ff_includeprototype"
					<?php echo ($options['includeprototype'] ? ' checked="checked"' : ''); ?>
					value="1" /> <br />
				<strong>Advanced option</strong>.  If your theme already includes a version of the 
				Prototype javascript library &amp; it's conflicting with this plugin, uncheck this box.
				If you don't include Prototype at all, this plugin won't work</td>
			</tr>			
			<tr valign="top">
				<th scope="row">Display debug information</th>
				<td><input type="checkbox" name="ff_showdebug" id="ff_showdebug" value="1" /> <br />
				Check this if you want to see the information that is being loaded by
				the plugin when you save. Only needed for troubleshooting.</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" value="Save Changes" name="Submit" />
		<input type="submit" style="color: #DD0000; font-weight: bold;" value="Reset comments/likes" name="Clear" onclick="return confirm('This will delete then reload all FriendFeed comments & likes.  Are you sure?');" />
		<input type="submit" style="color: #DD0000; font-weight: bold;" value="Delete plugin" name="Delete" onclick="return confirm('This will delete all information relating to this plugin.  Only do this if you're intending to remove this plugin.  Are you sure?');" />
	</p>
	</form>
	<div style="background-color: rgb(238, 238, 238); border: 1px solid rgb(85, 85, 85); padding: 5px; margin-top: 10px;">
		<p>Did you find this plugin useful? Please consider donating to help me continue developing it and other plugins.</p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_xclick" /><input type="hidden" name="business" value="paypal@slaven.net.au" /><input type="hidden" name="item_name" value="FriendFeed Comments Wordpress Plugin" /><input type="hidden" name="no_note" value="1" /><input type="hidden" name="currency_code" value="AUD" /><input type="hidden" name="tax" value="0" /><input type="hidden" name="bn" value="PP-DonationsBF" /><input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" /></form>
	</div>
</div>
				<?php
	}

	/**
	 * Render the list of likes passed in
	 *
	 * @param array $likes
	 * @return string
	 */
	function render_likes_list($likes) {
		$output_string = '';

		if (!is_array($likes)) {
			$comments = unserialize ($likes);
		}

		if (is_array($likes)) {
			$count = count($likes);
			if ($count) {
				$output_string .= "Liked by\n\t\t\t<ul>\n";
				for($i = 0; $i < $count; $i++) {
					$like = $likes[$i];
					$output_string .= "\t\t\t\t<li><a href=\"{$like->user->profileUrl}\">{$like->user->name}</a>" . ($i < $count - 1 ? ", " : '') . "</li>\n";
				}
				$output_string .= "\t\t\t</ul>\n";
			}
		}

		return $output_string;
	}

	/**
	 * Build up the string to display the list of comments.
	 *
	 * @param array $comments
	 * @return string The rendered comments list to display as HTML
	 */
	function render_comments_list($comments) {
		$output_string = '';

		if (!is_array($comments)) {
			$comments = unserialize ($comments);
		}

		if (is_array($comments)) {

			$output_string .= "<ul>\n";
			$i = 0;
			foreach($comments as $comment) {
				if ($i != 0) { //this is added here because the first comment is always a clip of the post from me... so not required or desired.
				$output_string .= $this->render_comment($comment);
				}
				$i++;
			}
			$output_string .= "</ul>\n";
		}
		return $output_string;
	}

	/**
	 * Render an individual comment
	 *
	 * @param object $comment
	 * @return string The rendered comment to display
	 */
	function render_comment($comment) {
		$output_string = '';
		$timeformat = get_option('time_format');
		$dateformat = get_option('date_format');
		$time = date($timeformat, $comment->date);
		$date = date($dateformat, $comment->date);
		$output_string .= "\t\t\t<li class=\"friendfeedcomment\">\n";
		$output_string .= "\t\t\t\t<a href=\"{$comment->user->profileUrl}\" class=\"profileImage\"><img src=\"http://friendfeed.com/{$comment->user->nickname}/picture?size=medium\" alt=\"\" /></a>";
		$output_string .= "\t\t\t\t<div class=\"friendfeedmeta\"><em>{$date} at {$time}</em> <cite><a href=\"{$comment->user->profileUrl}\">{$comment->user->name}</a></cite></div>\n";
		$output_string .= "\t\t\t\t<div class=\"friendfeedcommenttext\">" . make_clickable($comment->body) . "</div><div class=\"clearer\"></div>\n";
		$output_string .= "\t\t\t</li>\n";

		return $output_string;
	}

	/**
	 * Render the output of the plugin
	 *
	 */
	function display_comments() {
		global $post, $wp_query;

		if ( !$post && (is_single() || is_page() )) {
			$post = $wp_query->get_queried_object();
		}

		$discussion = $this->get_discussion_for_post($post->ID);
		$options = get_option($this->options_key);
		if ($this->is_debug()) {
			$this->printr($options);
			$this->printr($discussion);
		}

		if ($discussion) {
			$ffpostid = $discussion->entry_id;
			
			$comment_count = (is_array($discussion->comments) ? count($discussion->comments) : 0);
			$comment_count = $comment_count - 1; //this is added so the number of comments shown is not different to those rendered. Done before text set, so 1&0 should be handled properly.
			$like_count = (is_array($discussion->likes) ? count($discussion->likes) : 0);
		
			if ($options['headline']) {
				$headline_text = str_replace('{comments}', $comment_count, $options['headline']);
				$headline_text = str_replace('{likes}', $like_count, $headline_text);
			} else {
				$headline_text = 'On FriendFeed, this post was ';
				$headline_text .= "liked by $like_count " . ($like_count <> 1 ? 'people' : 'person');
				$headline_text .= " and ";
				$headline_text .= " commented on $comment_count" . ($comment_count <> 1 ? ' times' : ' time');
			}
			?>
<script type="text/javascript">var friendFeedTasks<?php echo $post->ID; ?> = new friendFeedTasks('<?php echo $ffpostid; ?>');</script>
<div id="friendfeedcomments_<?php echo $ffpostid; ?>" class="friendfeedcomments">
	<div id="friendfeedcommentslink_<?php echo $ffpostid; ?>" class="friendfeedcommentslink"><img src="<?php echo $this->plugin_path; ?>friendfeedicon.png" alt="" class="friendfeedicon" id="friendfeedicon_<?php echo $ffpostid; ?>" /><?php echo $headline_text;	?> <a href="" class="togglefriendfeedcommentslink" id="ff_togglecommentslink_<?php echo $ffpostid; ?>" onfocus="blur();"><?php echo ($options['showcomments'] ? 'hide' : 'show'); ?></a></div>
	<div id="friendfeeddiscussions_<?php echo $ffpostid; ?>" class="friendfeeddiscussions" <?php echo ($options['showcomments'] ? '' : ' style="display:none"'); ?>>
		<div class="friendfeedpermalink"><a	href="http://www.friendfeed.com/e/<?php echo $ffpostid; ?>">View this post on FriendFeed</a></div>
<?php
			print "\t\t<div id=\"friendfeedlikeslist_$ffpostid\" class=\"friendfeedlikeslist\">";
			if ($like_count) {
				print $this->render_likes_list($discussion->likes);
			}
			print "</div>\n";
		
			print "\t\t<div id=\"friendfeedcommentslist_$ffpostid\" class=\"friendfeedcommentslist\">\n\t\t";
			if ($comment_count) {
				print $this->render_comments_list($discussion->comments);
			}
			print "</div>\n";
		
			if ($options['showform']) {
				$username = '';
				$apikey = '';
				$auth = $this->get_authentication();
		
				if ($auth) {
					$username = $auth[0];
					$apikey = $auth[1];
				}
		?>
		<form class="ff_commentsform" id="ff_commentsform_<?php echo $ffpostid; ?>" method="post" action="">
			<h3>Add a comment on FriendFeed</h3>
			<textarea class="ff_newcomment"	id="ff_newcomment_<?php echo $ffpostid; ?>" name="ff_newcomment"></textarea><br />
			<div style="display:<?php echo ($username ? 'none' : 'block'); ?>;" class="ff_authentry" id="ff_authentry_<?php echo $ffpostid; ?>">
				<label for="ff_username_<?php echo $ffpostid; ?>">Username:</label><input type="text" name="ff_username" id="ff_username_<?php echo $ffpostid; ?>" value="" autocomplete="off" /><br />
				<label for="ff_apikey_<?php echo $ffpostid; ?>">API Key<sup><a href="https://friendfeed.com/account/api" title="Get your API key from here" target="_BLANK">?</a></sup>:</label><input type="password" name="ff_apikey" id="ff_apikey_<?php echo $ffpostid; ?>" value="" autocomplete="off" /><br />
				<label for="ff_remember_<?php echo $ffpostid; ?>">Remember:</label><input type="checkbox" name="ff_remember" id="ff_remember_<?php echo $ffpostid; ?>" />
			</div>
			<div <?php echo ($username ? '' : ' style="display:none;"'); ?>	class="ff_authsaved" id="ff_authsaved_<?php echo $ffpostid; ?>">
				Logged in as <span id="ff_usernamedisplay_<?php echo $ffpostid; ?>"><?php echo $username; ?></span>
				[<a href="" id="ff_forgetauth_<?php echo $ffpostid; ?>">logout</a>]
			</div>
			<input class="ff_submitcomment" id="ff_submitcomment_<?php echo $ffpostid; ?>" name="ff_submitcomment" value="Add comment" type="submit" />
			<input type="button" id="ff_likeentry_<?php echo $ffpostid; ?>" name="ff_likeentry" value="Like this post" style="<?php echo ($options['nickname'] == $username ? 'display:none;' : ''); ?>" />
			<img src="<?php echo $this->plugin_path; ?>loading.gif" alt="" id="ff_loadingimage_<?php echo $ffpostid; ?>" style="display: none;" />
			<input type="hidden" name="ff_entryid" value="<?php echo $ffpostid; ?>" />
			<input type="hidden" name="ff_postid" value="<?php echo $post->ID; ?>" />
		</form>

		<script type="text/javascript">
		$('ff_commentsform_<?php echo $ffpostid; ?>').observe('submit', friendFeedTasks<?php echo $post->ID; ?>.addComment.bindAsEventListener(friendFeedTasks<?php echo $post->ID; ?>));
		$('ff_likeentry_<?php echo $ffpostid; ?>').observe('click', friendFeedTasks<?php echo $post->ID; ?>.addLike.bindAsEventListener(friendFeedTasks<?php echo $post->ID; ?>));
		$('ff_forgetauth_<?php echo $ffpostid; ?>').observe('click', friendFeedTasks<?php echo $post->ID; ?>.logout.bindAsEventListener(friendFeedTasks<?php echo $post->ID; ?>));
		</script> <?php				
			}
	?> <script type="text/javascript">$('ff_togglecommentslink_<?php echo $ffpostid; ?>').observe('click', friendFeedTasks<?php echo $post->ID; ?>.toggleDiscussions.bindAsEventListener(friendFeedTasks<?php echo $post->ID; ?>));</script>
	</div>
</div>
	<?php
		}
	}
}

$FriendFeedPostComments = new FriendFeedPostComments();

function wp_ffcomments() {
	global $FriendFeedPostComments;
	$FriendFeedPostComments->display_comments();
}
?>
