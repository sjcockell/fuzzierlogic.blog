<?php
/*
 * Plugin Name: Easy Gravatars
 * Plugin URI: http://dougal.gunters.org/
 * Description: Add Gravatars to your comments, without requiring any modifications to your theme files. Just activate, and you're done!
 * Version: 1.2
 * License: GPL2
 * Author: Dougal Campbell
 * Author URI: http://dougal.gunters.org/
 * Min WP Version: 2.0.4
 * Max WP Version: 2.5
 */

// Register our activation hook, so we can set our default options:
register_activation_hook(_FILE_,'eg_activate');

function eg_activate() {
	// ...but only if we don't already have options set...
	$eg_opt_size = get_option('eg_size');
	if ( empty($eg_opt_size) ) {
		$defaults = eg_defaults();
		foreach ($defaults as $key => $val) {
			update_option($key, $val);
		}
	}
}

// Let WP know that we have an addition to the admin menus:
add_action('admin_menu', 'eg_opt_menu');

function eg_opt_menu() {
	add_options_page('Easy Gravatars', 'Easy Gravatars', 'manage_options', 'testoptions', 'eg_options_page');
}

// Add in our custom CSS
add_action('admin_head', 'eg_admin_css');

function eg_admin_css() {
        $plugindir = get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__));
        $eg_css = $plugindir . '/easygravatars.css';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $eg_css; ?>" />
<?php

}

// Set the filter requested by the user:
add_action('init', 'eg_set_filter');

function eg_set_filter() {
	$opts = eg_get_options();
	$filter = $opts['eg_api_hook'];
	add_filter($filter, 'eg_gravatar');
}

// Returns a hash of the default option values
function eg_defaults() {
	return array(
		'eg_size' => 80,
		'eg_rating' => 'G',
		'eg_defaulturl' => 'http://use.perl.org/images/pix.gif',
		'eg_style_span' => 'float:right; margin-left:10px; display:block',
		'eg_style_img' => '',
                'eg_api_hook' => 'get_comment_author_link',
	);
}

// return array of plugin options, using defaults if necessary:
function eg_get_options() {
	$eg_size = get_option('eg_size');
	$eg_rating = get_option('eg_rating');
	$eg_defaulturl = get_option('eg_defaulturl');
	$eg_style_span = get_option('eg_style_span');
	$eg_style_img = get_option('eg_style_img');
	$eg_api_hook = get_option('eg_api_hook');
	
	$defaults = eg_defaults();
	// Extra paranoia:
	if(empty($eg_size))
		$eg_size = $defaults['eg_size'];
	if(empty($eg_rating))
		$eg_rating = $defaults['eg_rating'];
	if(empty($eg_defaulturl))
		$eg_defaulturl = $defaults['eg_defaulturl'];
	if(empty($eg_style_span))
		$eg_style_span = $defaults['eg_style_span'];
	if(empty($eg_style_img))
		$eg_style_img = $defaults['eg_style_img'];
        if(empty($eg_api_hook))
                $eg_api_hook = $defaults['eg_api_hook'];
		
	return array(
		'eg_size' => $eg_size,
		'eg_rating' => $eg_rating,
		'eg_defaulturl' => $eg_defaulturl,
		'eg_style_span' => $eg_style_span,
		'eg_style_img' => $eg_style_img,
		'eg_api_hook' => $eg_api_hook,
	);
}

// Filter to add a Gravatar image, prepended:
function eg_gravatar($text) {
	global $comment;
	// no email? no gravatar.
	if ( !empty( $comment->comment_author_email ) ) {
		$opts = eg_get_options();

		$eg_size = $opts['eg_size'];
		$eg_rating = $opts['eg_rating'];
		$default = $opts['eg_defaulturl'];
		$eg_style_span = $opts['eg_style_span'];

		if (function_exists('get_avatar')) {
			// WP 2.5+
			$imgtag = get_avatar($comment->comment_author_email, $eg_size, $default);
		} else {
			// Roll our own
			
		        // The Gravatar server normalizes email addresses to
		        // lowercase, so we should, too. Props to David Potter:
		        //   http://dpotter.net/Technical/index.php/2007/10/22/integrating-gravatar-support/
			$md5 = md5( strtolower( $comment->comment_author_email ) );
			$default = urlencode( $default );
			$imgtag = "<img src='http://www.gravatar.com/avatar.php?gravatar_id=$md5&amp;size=$eg_size&amp;rating=$eg_rating&amp;default=$default' width='$eg_size' height='$eg_size' alt='' class='avatar avatar-{$eg_size} easygravatar' />";
		}

		$cau = $comment->comment_author_url;
		// Did they leave a link?
		if (! ( empty($cau) || 'http://' == $cau ) ) {
			$imgtag = "<a rel='external nofollow' href='$cau'>$imgtag</a>";
		}


		$text = "<span class='eg-image' style='$eg_style_span; width:{$eg_size}px' >$imgtag</span>" . $text;
	}

	return $text;
}

// Options update page:
function eg_options_page() {
	// defaults
	$eg_ratings = array('G', 'PG', 'R', 'X');
	$maxsize = 80;
	$minsize = 1;
	$eg_hooks = array(
	  'get_comment_author_link' => 'Comment Author Link',
	  'comment_text' => 'Comment Text',
        );
	
	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( $_POST[ 'eg_submitted' ] == 'Y' ) {
		check_admin_referer('easy-gravatars-update-options');
		// Read their posted value
		$eg_rating = $_POST['eg_rating'];
		$eg_size = (int) $_POST['eg_size'];
		$eg_defaulturl = $_POST['eg_defaulturl'];
		$eg_style_span = $_POST['eg_style_span'];
		$eg_api_hook = $_POST['eg_api_hook'];
		
		if ($eg_size > $maxsize) {
			$eg_size = $maxsize;
		}

		if ($eg_size < $minsize) {
			$eg_size = $minsize;
		}

		// Save the posted value in the database
		update_option( 'eg_rating', $eg_rating );
		update_option( 'eg_size', $eg_size );
		update_option( 'eg_defaulturl', $eg_defaulturl );
		update_option( 'eg_style_span', $eg_style_span );
		update_option( 'eg_api_hook', $eg_api_hook );

		// Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'eg_trans_domain' ); ?></strong></p></div>
<?php
	} // endif
	
	$opts = eg_get_options();

	// Get existing option values:
	$eg_opt_rating = $opts['eg_rating'];
	$eg_opt_size = $opts['eg_size'];
	$eg_opt_defaulturl = $opts['eg_defaulturl'];
	$eg_opt_style_span = $opts['eg_style_span'];
        $eg_opt_api_hook = $opts['eg_api_hook'];
	
// Main options form:
?>
<div class="wrap">
<h2><?php _e( 'Easy Gravatars Plugin Options', 'eg_trans_domain' ); ?></h2>

<form id="easygravatars" name="eg_opts" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'eg_trans_domain' ) ?>" />
</p>

<?php wp_nonce_field('easy-gravatars-update-options') ?>
<input type="hidden" name="eg_submitted" value="Y">

<fieldset id="general">
<legend><?php _e("General Options", 'eg_trans_domain'); ?></legend>
<p>
<label for="eg_size"><?php _e("Size, in pixels (maximum: 80):", 'eg_trans_domain' ); ?></label>
<input type="text" name="eg_size" id="eg_size" value="<?php echo $eg_opt_size; ?>" size="4">
</p>

<p>
<label for="eg_rating"><?php _e("Allowed Rating:", 'eg_trans_domain' ); ?></label>
<select name="eg_rating" id="eg_rating">
<?php
	foreach ($eg_ratings as $rating) {
		$selected = ($rating == $eg_opt_rating) ? 'selected="selected"' : '';
		echo "\t<option value='$rating' $selected>$rating</option>\n";
	}
?>
</select>
</p>

<p>
<div style="float:right;margin-left:10px;width=<?php echo $eg_opt_size ?>"><img src="<?php echo $eg_opt_defaulturl; ?>" width="<?php echo $eg_opt_size; ?>" height="<?php echo $eg_opt_size; ?>" /></div>
<label for="eg_defaulturl"><?php _e("Default image url:", 'eg_trans_domain' ); ?></label>
<input type="text" name="eg_defaulturl" id="eg_defaulturl" value="<?php echo $eg_opt_defaulturl; ?>" size="40">
</p>
</fieldset>

<fieldset id="advanced">
<legend><?php _e('Advanced Options', 'eg_trans_domain'); ?></legend>
<p>
<label for="eg_style_span"><?php _e("Span style:", 'eg_trans_domain' ); ?></label>
<input type="text" name="eg_style_span" id="eg_style_span" value="<?php echo $eg_opt_style_span; ?>" size="40">
</p>

<p>
<label for="eg_api_hook"><?php _e("API Hook:", 'eg_trans_domain' ); ?></label>
<select name="eg_api_hook" id="eg_api_hook">
<?php
	foreach ($eg_hooks as $hook => $label) {
		$selected = ($hook == $eg_opt_api_hook) ? 'selected="selected"' : '';
		echo "\t<option value='$hook' $selected>$label</option>\n";
	}
?>
</select>
</p>
</fieldset>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'eg_trans_domain' ) ?>" />
</p>

</form>
</div>

<?php
 
}

?>
