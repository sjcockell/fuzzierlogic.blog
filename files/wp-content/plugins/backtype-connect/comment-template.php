<?php
global $post;
require_once BTC_DIR . '/db.php';

if (($btc_summary = btc_db_get_comment_summary($post->ID)) === false) {
	$btc_summary = btc_db_comment_summary($post->ID);
	btc_db_set_comment_summary($post->ID, $btc_summary);
}

if ($btc_summary) {
	$summary_html = '';
	if ($btc_summary[0]->comment_src == 'blog') {
		array_push($btc_summary, array_shift($btc_summary));
	}
	if ($src = array_shift($btc_summary)) {
		$plural = ($src->cnt != 1) ? 's' : '';
		$summary_html .= ' <img src="' . WP_PLUGIN_URL . '/backtype-connect/images/' . $src->comment_src . '-16.gif" alt=""/> <a href="http://www.backtype.com/connect/' . _btc_url_encode(get_permalink($post->ID));
		$summary_html .= ($src->comment_src == 'twitter') ? '/tweets" title="' . $src->cnt . ' tweet' . $plural . ' on ' . $src->comment_src . '">' . $src->cnt . ' Tweet' : '?src=' . $src->comment_src . '" title="' . $src->cnt . ' comment' . $plural . ' on ' . (($src->comment_src == 'blog') ? 'other sites' : $src->comment_src) . '">' . $src->cnt . (($src->comment_src == 'blog') ? ' Other' : '') . ' Comment';
		$summary_html .= $plural . '</a>';
	}
	foreach ($btc_summary as $src) {
		$plural = ($src->cnt != 1) ? 's' : '';
		$summary_html .= ' <img src="' . WP_PLUGIN_URL . '/backtype-connect/images/' . $src->comment_src . '-16.gif" style="margin-left:1em" alt=""/> <a href="http://www.backtype.com/connect/' . _btc_url_encode(get_permalink($post->ID));
		$summary_html .= ($src->comment_src == 'twitter') ? '/tweets" title="' . $src->cnt . ' tweet' . $plural . ' on ' . $src->comment_src . '">' . $src->cnt . ' Tweet' : '?src=' . $src->comment_src . '" title="' . $src->cnt . ' comment' . $plural . ' on ' . (($src->comment_src == 'blog') ? 'other sites' : $src->comment_src) . '">' . $src->cnt . (($src->comment_src == 'blog') ? ' Other' : '') . ' Comment';
		$summary_html .= $plural . '</a>';
	}
	echo '<p class="btc-summary">' . $summary_html . '</p>';
}

if (file_exists(STYLESHEETPATH . $file))
	require(STYLESHEETPATH . $file);
elseif (file_exists(TEMPLATEPATH . $file))
	require(TEMPLATEPATH . '/comments.php');
else
	require(get_theme_root() . '/default/comments.php');

echo '<p class="btc-powered">Additional comments powered by <a href="http://www.backtype.com/search?q=' . get_permalink($post->ID) . '">BackType</a></p>';
?>