<?php
/*
Plugin Name: MediaRSS
Plugin URI: http://wordpress.org/extend/plugins/mrss/
Description: Adds &lt;media&gt; tags to your feeds.
Version: 1.1.4
Author: Andy Skelton
Author URI: http://andy.wordpress.com/

FriendFeed image thumbnail compatibility modifications by Daniel J. Pritchett
on the advice of Paul Reynolds (http://friendfeed.com/screwtheman). This adds
the first image in the post as the post's own thumbnail.

*/

add_action('template_redirect', 'mrss_init');

function mrss_init() {
	if ( ! is_feed() )
		return;

	if ( isset( $_GET['mrss'] ) && $_GET['mrss'] == 'off' )
		return;

	add_action('rss2_ns', 'mrss_ns');

	add_action('rss2_item', 'mrss_item', 10, 0);
}

function mrss_ns() {
	?>

	xmlns:media="http://search.yahoo.com/mrss/"
	<?php
}

function mrss_item() {
	global $mrss_gallery_lookup;
	$media = array();

	// Honor the feed settings. Don't include any media that isn't in the feed.
	if ( get_option('rss_use_excerpt') || !strlen( get_the_content() ) ) {
		$content = the_excerpt_rss();
	} else {
		// If any galleries are processed, we need to capture the attachment IDs.
		add_filter( 'wp_get_attachment_link', 'mrss_gallery_lookup', 10, 5 );
		$content = apply_filters( 'the_content', get_the_content() );
		remove_filter( 'wp_get_attachment_link', 'mrss_gallery_lookup', 10, 5 );
		$lookup = $mrss_gallery_lookup;
		unset($mrss_gallery_lookup);
	}

	// img tags
	$images = 0;
	if ( preg_match_all('/<img (.+?)>/', $content, $matches) ) {
		foreach ( $matches[1] as $attrs ) {
			$item = $img = array();
			// Construct $img array from <img> attributes
			foreach ( wp_kses_hair($attrs, array('http')) as $attr )
				$img[$attr['name']] = $attr['value'];
			if ( !isset($img['src']) )
				continue;
			$img['src'] = mrss_url($img['src']);
			// Skip emoticons
			if ( isset( $img['class'] ) && false !== strpos( $img['class'], 'wp-smiley' ) )
				continue;
			$id = false;
			if ( isset( $lookup[$img['src']] ) ) {
				$id = $lookup[$img['src']];
			} elseif ( isset( $img['class'] ) && preg_match( '/wp-image-(\d+)/', $img['class'], $match ) ) {
				$id = $match[1];
			}
			if ( $id ) {
				// It's an attachment, so we will get the URLs, title, and description from functions
				$attachment =& get_post( $id );
				$src = wp_get_attachment_image_src( $id, 'full' );
				if ( !empty( $src[0] ) )
					$img['src'] = $src[0];
				$thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
				if ( !empty( $thumbnail[0] ) && $thumbnail[0] != $img['src'] )
					$img['thumbnail'] = $thumbnail[0];
				$title = get_the_title( $id );
				if ( !empty( $title ) )
					$img['title'] = trim($title);
				$description = get_the_content( $id );
				if ( !empty( $attachment->post_excerpt ) )
					$img['description'] = trim($attachment->post_excerpt);
			}
			// If this is the first image in the markup, make it the post thumbnail
			if ( ++$images == 1 ) {
				if ( isset( $img['thumbnail'] ) )
					$media[]['thumbnail']['attr']['url'] = $img['thumbnail'];
				else
					$media[]['thumbnail']['attr']['url'] = $img['src'];
			}

			$item['content']['attr']['url'] = $img['src'];
			$item['content']['attr']['medium'] = 'image';
			if ( !empty($img['title']) ) {
				$item['content']['children']['title']['attr']['type'] = 'html';
				$item['content']['children']['title']['children'][] = $img['title'];
			} elseif ( !empty($img['alt']) ) {
				$item['content']['children']['title']['attr']['type'] = 'html';
				$item['content']['children']['title']['children'][] = $img['alt'];
			}
			if ( !empty($img['description']) ) {
				$item['content']['children']['description']['attr']['type'] = 'html';
				$item['content']['children']['description']['children'][] = $img['description'];
			}
			if ( !empty($img['thumbnail']) )
				$item['content']['children']['thumbnail']['attr']['url'] = $img['thumbnail'];
			$media[] = $item;
		}
	}

	$media = apply_filters('mrss_media', $media);

	mrss_print($media);
}

function mrss_url($url) {
	if ( preg_match( '!^https?://!', $url ) )
		return $url;
	if ( $url{0} == '/' )
		return rtrim( get_bloginfo('home'), '/' ) . $url;
	return get_bloginfo('home') . $url;
}

function mrss_gallery_lookup($link, $id, $size, $permalink, $icon) {
	global $mrss_gallery_lookup;
	preg_match( '/ src="(.*?)"/', $link, $matches );
	$mrss_gallery_lookup[$matches[1]] = $id;
	return $link;
}

function mrss_print($media) {
	if ( !empty($media) )
		foreach( $media as $element )
			mrss_print_element($element);
	echo "\n";
}

function mrss_print_element($element, $indent = 2) {
	echo "\n";
	foreach ( $element as $name => $data ) {
		echo str_repeat("\t", $indent) . "<media:$name";
		if ( !empty($data['attr']) ) {
			foreach ( $data['attr'] as $attr => $value )
				echo " $attr=\"" . ent2ncr($value) . "\"";
		}
		if ( !empty($data['children']) ) {
			$nl = false;
			echo ">";
			foreach ( $data['children'] as $_name => $_data ) {
				if ( is_int($_name) ) {
					echo ent2ncr($_data);
				} else {
					$nl = true;
					mrss_print_element( array( $_name => $_data ), $indent + 1 );
				}
			}
			if ( $nl )
				echo "\n" . str_repeat("\t", $indent);
			echo "</media:$name>";
		} else {
			echo " />";
		}
	}
}

/*
	SAMPLE CODE
	The following examples are intented to show you how you can develop your own MediaRSS filters.
*/

/*
This function will result in code like this:
		<media:content url="http://localhost/admin.gif" medium="image">
			<media:title type="html">admin</media:title>
		</media:content>
*/
/*
function mrss_add_author_image($media) {
	$name = get_the_author();

	foreach ( array('jpg', 'gif', 'png') as $ext ) {
		if ( file_exists( ABSPATH . "/$name.$ext") ) {
			$item['content']['attr']['url'] = get_option('siteurl') . "/$name.$ext";
			$item['content']['attr']['medium'] = 'image';
			$item['content']['children']['title']['attr']['type'] = 'html';
			$item['content']['children']['title']['children'][] = "$name";
			array_unshift($media, $item);
			break;
		}
	}
	return $media;
}
add_filter('mrss_media', 'mrss_add_author_image');
*/

/*
This function will search post_content and if it finds "[audio http://example.com/song.mp3]" it adds this to the feed:
		<media:content url="http://example.com/song.mp3" medium="audio">
			<media:player url="http://localhost/wp-content/plugins/audio-player/player.swf?soundFile=http://example.com/song.mp3" />
		</media:content>
*/
/*
function mrss_audio_macro($media) {
	$content = get_the_content();

	if ( preg_match_all('/\[audio (.+)]/', $content, $matches) ) {
		foreach ( $matches[1] as $url ) {
			$item = array();
			$url = html_entity_decode($url);
			$url = preg_replace('/[<>"\']/', '', $url);
			$item['content']['attr']['url'] = $url;
			$item['content']['attr']['medium'] = 'audio';
			$item['content']['children']['player']['attr']['url'] = get_option( 'siteurl' ). "/wp-content/plugins/audio-player/player.swf?soundFile=" . $url;
			$media[] = $item;
		}
	}
	
	return $media;
}
add_filter('mrss_media', 'mrss_audio_macro');
*/

