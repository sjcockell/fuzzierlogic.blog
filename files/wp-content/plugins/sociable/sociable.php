<?php
/*
Plugin Name: Sociable
Plugin URI: http://yoast.com/wordpress/sociable/
Description: Automatically add links on your posts, pages and RSS feed to your favorite social bookmarking sites.
Version: 3.2.3
Author: Joost de Valk
Author URI: http://yoast.com/

Copyright 2006 Peter Harkins (ph@malaprop.org)
Copyright 2008-2009 Joost de Valk (joost@yoast.com)

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Guess the location
$sociablepluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

function sociable_init_locale(){
	global $sociablepluginpath;
	load_plugin_textdomain('sociable', $sociablepluginpath);
}
add_filter('init', 'sociable_init_locale');

$sociable_known_sites = Array(

	'BarraPunto' => Array(
		'favicon' => 'barrapunto.png',
		'url' => 'http://barrapunto.com/submit.pl?subj=TITLE&amp;story=PERMALINK',
	),
	
	'Bitacoras.com' => Array(
		'favicon' => 'bitacoras.png',
		'url' => 'http://bitacoras.com/anotaciones/PERMALINK',
	),
	
	'BlinkList' => Array(
		'favicon' => 'blinklist.png',
		'url' => 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=PERMALINK&amp;Title=TITLE',
	),

	'BlogMemes Fr' => Array(
		'favicon' => 'blogmemes.png',
		'url' => 'http://www.blogmemes.fr/post.php?url=PERMALINK&amp;title=TITLE',
	),

	'BlogMemes Sp' => Array(
		'favicon' => 'blogmemes.png',
		'url' => 'http://www.blogmemes.com/post.php?url=PERMALINK&amp;title=TITLE',
	),

	'blogmarks' => Array(
		'favicon' => 'blogmarks.png',
		'url' => 'http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=PERMALINK&amp;title=TITLE',
	),

	'Blogosphere News' => Array(
		'favicon' => 'blogospherenews.png',
		'url' => 'http://www.blogospherenews.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),

	'blogtercimlap' => Array(
		'favicon' => 'blogter.png',
		'url' => 'http://cimlap.blogter.hu/index.php?action=suggest_link&amp;title=TITLE&amp;url=PERMALINK',
	),

	'Faves' => Array(
		'favicon' => 'bluedot.png',
		'url' => 'http://faves.com/Authoring.aspx?u=PERMALINK&amp;title=TITLE',
	),

	'co.mments' => Array(
		'favicon' => 'co.mments.png',
		'url' => 'http://co.mments.com/track?url=PERMALINK&amp;title=TITLE',
	),

	'connotea' => Array(
		'favicon' => 'connotea.png',
		'url' => 'http://www.connotea.org/addpopup?continue=confirm&amp;uri=PERMALINK&amp;title=TITLE&amp;description=EXCERPT',
	),

	'Current' => Array(
		'favicon' => 'current.png',
		'url' => 'http://current.com/clipper.htm?url=PERMALINK&amp;title=TITLE'
	),
	
	'del.icio.us' => Array(
		'favicon' => 'delicious.png',
		'url' => 'http://delicious.com/post?url=PERMALINK&amp;title=TITLE&amp;notes=EXCERPT',
	),

	'Design Float' => Array(
		'favicon' => 'designfloat.png',
		'url' => 'http://www.designfloat.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),

	'Digg' => Array(
		'favicon' => 'digg.png',
		'url' => 'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE&amp;bodytext=EXCERPT',
		'description' => 'Digg',
	),

	'Diigo' => Array(
		'favicon' => 'diigo.png',
		'url' => 'http://www.diigo.com/post?url=PERMALINK&amp;title=TITLE',
	),

	'DotNetKicks' => Array(
		'favicon' => 'dotnetkicks.png',
		'url' => 'http://www.dotnetkicks.com/kick/?url=PERMALINK&amp;title=TITLE',
	),

	'DZone' => Array(
		'favicon' => 'dzone.png',
		'url' => 'http://www.dzone.com/links/add.html?url=PERMALINK&amp;title=TITLE',
	),

	'eKudos' => Array(
		'favicon' => 'ekudos.png',
		'url' => 'http://www.ekudos.nl/artikel/nieuw?url=PERMALINK&amp;title=TITLE&amp;desc=EXCERPT',
	),

	'email' => Array(
		'favicon' => 'email_link.png',
		'url' => 'mailto:?subject=TITLE&amp;body=PERMALINK',
		'description' => __('E-mail this story to a friend!','sociable'),
	),

	'Facebook' => Array(
		'favicon' => 'facebook.png',
		'url' => 'http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE',
	),

	'Fark' => Array(
		'favicon' => 'fark.png',
		'url' => 'http://cgi.fark.com/cgi/fark/farkit.pl?h=TITLE&amp;u=PERMALINK',
	),

	'Fleck' => Array(
		'favicon' => 'fleck.png',
		'url' => 'http://beta3.fleck.com/bookmarklet.php?url=PERMALINK&amp;title=TITLE',
	),

	'FriendFeed' => Array(
		'favicon' => 'friendfeed.png',
		'url' => 'http://www.friendfeed.com/share?title=TITLE&amp;link=PERMALINK',
	),

	'FSDaily' => Array(
		'favicon' => 'fsdaily.png',
		'url' => 'http://www.fsdaily.com/submit?url=PERMALINK&amp;title=TITLE',
	),

	'Global Grind' => Array (
		'favicon' => 'globalgrind.png',
		'url' => 'http://globalgrind.com/submission/submit.aspx?url=PERMALINK&amp;type=Article&amp;title=TITLE'
	),
	
	'Google' => Array (
		'favicon' => 'googlebookmark.png',
		'url' => 'http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=PERMALINK&amp;title=TITLE&amp;annotation=EXCERPT',
		'description' => 'Google Bookmarks'
	),
	
	'Gwar' => Array(
		'favicon' => 'gwar.png',
		'url' => 'http://www.gwar.pl/DodajGwar.html?u=PERMALINK',
	),

	'Haohao' => Array(
		'favicon' => 'haohao.png',
		'url' => 'http://www.haohaoreport.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),

	'HealthRanker' => Array(
		'favicon' => 'healthranker.png',
		'url' => 'http://healthranker.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),

	'HelloTxt' => Array(
        'favicon' => 'hellotxt.png',
        'url' => 'http://hellotxt.com/?status=TITLE+PERMALINK',
    ),

	'Hemidemi' => Array(
		'favicon' => 'hemidemi.png',
		'url' => 'http://www.hemidemi.com/user_bookmark/new?title=TITLE&amp;url=PERMALINK',
	),

	'Identi.ca' => Array(
		'favicon' => 'identica.png',
		'url' => 'http://identi.ca/notice/new?status_textarea=PERMALINK',
	),

	'IndianPad' => Array(
		'favicon' => 'indianpad.png',
		'url' => 'http://www.indianpad.com/submit.php?url=PERMALINK',
	),

	'Internetmedia' => Array(
		'favicon' => 'im.png',
		'url' => 'http://internetmedia.hu/submit.php?url=PERMALINK'
	),

	'Kirtsy' => Array(
		'favicon' => 'kirtsy.png',
		'url' => 'http://www.kirtsy.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),

	'laaik.it' => Array(
		'favicon' => 'laaikit.png',
		'url' => 'http://laaik.it/NewStoryCompact.aspx?uri=PERMALINK&amp;headline=TITLE&amp;cat=5e082fcc-8a3b-47e2-acec-fdf64ff19d12',
	),

	'LinkArena' => Array(
		'favicon' => 'linkarena.png',
		'url' => 'http://linkarena.com/bookmarks/addlink/?url=PERMALINK&amp;title=TITLE',
	),
	
	'LinkaGoGo' => Array(
		'favicon' => 'linkagogo.png',
		'url' => 'http://www.linkagogo.com/go/AddNoPopup?url=PERMALINK&amp;title=TITLE',
	),

	'LinkedIn' => Array(
		'favicon' => 'linkedin.png',
		'url' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;source=BLOGNAME&amp;summary=EXCERPT',
	),

	'Linkter' => Array(
		'favicon' => 'linkter.png',
		'url' => 'http://www.linkter.hu/index.php?action=suggest_link&amp;url=PERMALINK&amp;title=TITLE',
	),
	
	'Live' => Array(
		'favicon' => 'live.png',
		'url' => 'https://favorites.live.com/quickadd.aspx?marklet=1&amp;url=PERMALINK&amp;title=TITLE',
	),

	'Meneame' => Array(
		'favicon' => 'meneame.png',
		'url' => 'http://meneame.net/submit.php?url=PERMALINK',
	),
	
	'MisterWong' => Array(
		'favicon' => 'misterwong.png',
		'url' => 'http://www.mister-wong.com/addurl/?bm_url=PERMALINK&amp;bm_description=TITLE&amp;plugin=soc',
	),

	'MisterWong.DE' => Array(
		'favicon' => 'misterwong.png',
		'url' => 'http://www.mister-wong.de/addurl/?bm_url=PERMALINK&amp;bm_description=TITLE&amp;plugin=soc',
	),
	
	'Mixx' => Array(
		'favicon' => 'mixx.png',
		'url' => 'http://www.mixx.com/submit?page_url=PERMALINK&amp;title=TITLE',
	),
	
	'muti' => Array(
		'favicon' => 'muti.png',
		'url' => 'http://www.muti.co.za/submit?url=PERMALINK&amp;title=TITLE',
	),
	
	'MyShare' => Array(
		'favicon' => 'myshare.png',
		'url' => 'http://myshare.url.com.tw/index.php?func=newurl&amp;url=PERMALINK&amp;desc=TITLE',
	),

	'MySpace' => Array(
		'favicon' => 'myspace.png',
		'url' => 'http://www.myspace.com/Modules/PostTo/Pages/?u=PERMALINK&amp;t=TITLE',
	),

	'MSNReporter' => Array(
		'favicon' => 'msnreporter.png',
		'url' => 'http://reporter.msn.nl/?fn=contribute&amp;Title=TITLE&amp;URL=PERMALINK&amp;cat_id=6&amp;tag_id=31&amp;Remark=EXCERPT',
		'description' => 'MSN Reporter'
	),
	
	'N4G' => Array(
		'favicon' => 'n4g.png',
		'url' => 'http://www.n4g.com/tips.aspx?url=PERMALINK&amp;title=TITLE',
	),
	
	'Netvibes' => Array(
		'favicon' => 'netvibes.png',
		'url' =>	'http://www.netvibes.com/share?title=TITLE&amp;url=PERMALINK',
	),
		
	'NewsVine' => Array(
		'favicon' => 'newsvine.png',
		'url' => 'http://www.newsvine.com/_tools/seed&amp;save?u=PERMALINK&amp;h=TITLE',
	),

	'Netvouz' => Array(
		'favicon' => 'netvouz.png',
		'url' => 'http://www.netvouz.com/action/submitBookmark?url=PERMALINK&amp;title=TITLE&amp;popup=no',
	),

	'NuJIJ' => Array(
		'favicon' => 'nujij.png',
		'url' => 'http://nujij.nl/jij.lynkx?t=TITLE&amp;u=PERMALINK&amp;b=EXCERPT',
	),
	
	'Ping.fm' => Array(
		'favicon' => 'ping.png',
		'url' => 'http://ping.fm/ref/?link=PERMALINK&amp;title=TITLE&amp;body=EXCERPT',
	),

	'ppnow' => Array(
		'favicon' => 'ppnow.png',
		'url' => 'http://www.ppnow.net/submit.php?url=PERMALINK',
	),
	
	'PDF' => Array(
		'favicon' => 'pdf.png',
		'url' => 'http://www.printfriendly.com/getpf?url=PERMALINK',
		'description' => __('Turn this article into a PDF!', 'sociable'),
	),
	
	'Print' => Array(
		'favicon' => 'printfriendly.png',
		'url' => 'http://www.printfriendly.com/print?url=PERMALINK',
		'description' => __('Print this article!', 'sociable'),
	),
	
	'Propeller' => Array(
		'favicon' => 'propeller.png',
		'url' => 'http://www.propeller.com/submit/?url=PERMALINK',
	),

	'Ratimarks' => Array(
		'favicon' => 'ratimarks.png',
		'url' => 'http://ratimarks.org/bookmarks.php/?action=add&address=PERMALINK&amp;title=TITLE',
	),

	'Rec6' => Array(
		'favicon' => 'rec6.png',
		'url' => 'http://rec6.via6.com/link.php?url=PERMALINK&amp;=TITLE',
	),

	'Reddit' => Array(
		'favicon' => 'reddit.png',
		'url' => 'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE',
	),

	'RSS' => Array(
		'favicon' => 'rss.png',
		'url' => 'FEEDLINK',
	),
	
	'Scoopeo' => Array(
		'favicon' => 'scoopeo.png',
		'url' => 'http://www.scoopeo.com/scoop/new?newurl=PERMALINK&amp;title=TITLE',
	),	

	'Segnalo' => Array(
		'favicon' => 'segnalo.png',
		'url' => 'http://segnalo.alice.it/post.html.php?url=PERMALINK&amp;title=TITLE',
	),

	'Simpy' => Array(
		'favicon' => 'simpy.png',
		'url' => 'http://www.simpy.com/simpy/LinkAdd.do?href=PERMALINK&amp;title=TITLE',
	),

	'Slashdot' => Array(
		'favicon' => 'slashdot.png',
		'url' => 'http://slashdot.org/bookmark.pl?title=TITLE&amp;url=PERMALINK',
	),

	'Socialogs' => Array(
		'favicon' => 'socialogs.png',
		'url' => 'http://socialogs.com/add_story.php?story_url=PERMALINK&amp;story_title=TITLE',
	),
	
	'SphereIt' => Array(
		'favicon' => 'sphere.png',
		'url' => 'http://www.sphere.com/search?q=sphereit:PERMALINK&amp;title=TITLE',
	),

	'Sphinn' => Array(
		'favicon' => 'sphinn.png',
		'url' => 'http://sphinn.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),

	'StumbleUpon' => Array(
		'favicon' => 'stumbleupon.png',
		'url' => 'http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE',
	),

	'Symbaloo' => Array(
		'favicon' => 'symbaloo.png',
		'url' => 'http://www.symbaloo.com/nl/add/url=PERMALINK&amp;title=TITLE&amp;icon=http%3A//static01.symbaloo.com/_img/favicon.png',
	),
	
	'Technorati' => Array(
		'favicon' => 'technorati.png',
		'url' => 'http://technorati.com/faves?add=PERMALINK',
	),

	'ThisNext' => Array(
		'favicon' => 'thisnext.png',
		'url' => 'http://www.thisnext.com/pick/new/submit/sociable/?url=PERMALINK&amp;name=TITLE',
	),

	'Tipd' => Array(
		'favicon' => 'tipd.png',
		'url' => 'http://tipd.com/submit.php?url=PERMALINK',
	),
	
	'TwitThis' => Array(
		'favicon' => 'twitter.png',
		'url' => 'http://twitter.com/home?status=PERMALINK',
	),

	'Upnews' => Array(
			'favicon' => 'upnews.png',
			'url' => 'http://www.upnews.it/submit?url=PERMALINK&amp;title=TITLE',
	),
	
	'Webnews.de' => Array(
        'favicon' => 'webnews.png',
        'url' => 'http://www.webnews.de/einstellen?url=PERMALINK&amp;title=TITLE',
    ),

	'Webride' => Array(
		'favicon' => 'webride.png',
		'url' => 'http://webride.org/discuss/split.php?uri=PERMALINK&amp;title=TITLE',
	),

	'Wikio' => Array(
		'favicon' => 'wikio.png',
		'url' => 'http://www.wikio.com/vote?url=PERMALINK',
	),

	'Wikio FR' => Array(
		'favicon' => 'wikio.png',
		'url' => 'http://www.wikio.fr/vote?url=PERMALINK',
	),

	'Wikio IT' => Array(
		'favicon' => 'wikio.png',
		'url' => 'http://www.wikio.it/vote?url=PERMALINK',
	),
	
	'Wists' => Array(
		'favicon' => 'wists.png',
		'url' => 'http://wists.com/s.php?c=&amp;r=PERMALINK&amp;title=TITLE',
		'class' => 'wists',
	),

	'Wykop' => Array(
		'favicon' => 'wykop.png',
		'url' => 'http://www.wykop.pl/dodaj?url=PERMALINK',
	),

	'Xerpi' => Array(
		'favicon' => 'xerpi.png',
		'url' => 'http://www.xerpi.com/block/add_link_from_extension?url=PERMALINK&amp;title=TITLE',
	),

	'YahooBuzz' => Array(
		'favicon' => 'yahoobuzz.png',
		'url' => 'http://buzz.yahoo.com/submit/?submitUrl=PERMALINK&amp;submitHeadline=TITLE&amp;submitSummary=EXCERPT&amp;submitCategory=science&amp;submitAssetType=text',
		'description' => 'Yahoo! Buzz',
	),
	
	'Yahoo! Bookmarks' => Array(
		'favicon' => 'yahoomyweb.png',
		'url' => 'http://bookmarks.yahoo.com/toolbar/savebm?u=PERMALINK&amp;t=TITLE&opener=bm&amp;ei=UTF-8&amp;d=EXCERPT',
	),

	'Yigg' => Array(
		'favicon' => 'yiggit.png',
		'url' => 'http://yigg.de/neu?exturl=PERMALINK&amp;exttitle=TITLE',
	 ),
);

// For maintaining backwards compatability
if (!function_exists('strip_shortcodes')) {
	function strip_shortcodes($content) {
		return $content;
	}
}

function sociable_html($display=Array()) {
	global $sociable_known_sites, $sociablepluginpath, $wp_query, $post; 

	$sociableooffmeta = get_post_meta($post->ID,'sociableoff',true);
	if ($sociableooffmeta == "true") {
		return "";
	}

	$active_sites = get_option('sociable_active_sites');

	$html = "";

	if (get_option('sociable_imagedir') == "")
		$imagepath = $sociablepluginpath.'images/';
	else
		$imagepath = get_option('sociable_imagedir');
		
	// if no sites are specified, display all active
	// have to check $active_sites has content because WP
	// won't save an empty array as an option
	if (empty($display) and $active_sites)
		$display = $active_sites;
	// if no sites are active, display nothing
	if (empty($display))
		return "";

	// Load the post's data
	$blogname 	= urlencode(get_bloginfo('name')." ".get_bloginfo('description'));
	$blogrss	= get_bloginfo('rss2_url'); 
	$post 		= $wp_query->post;
	
	$excerpt	= urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));
	if ($excerpt == "") {
		$excerpt = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,250));
	}
	$excerpt	= str_replace('+','%20',$excerpt);
	
	$permalink 	= urlencode(get_permalink($post->ID));
	
	$title 		= urlencode($post->post_title);
	$title 		= str_replace('+','%20',$title);
	
	$rss 		= urlencode(get_bloginfo('ref_url'));

	$html .= "\n<div class=\"sociable\">\n";
	
	$tagline = get_option("sociable_tagline");
	if ($tagline != "") {
		$html .= "<div class=\"sociable_tagline\">\n";
		$html .= stripslashes($tagline);
		$html .= "\n</div>";
	}
	
	$html .= "\n<ul>\n";

	$i = 0;
	$totalsites = count($display);
	foreach($display as $sitename) {
		// if they specify an unknown or inactive site, ignore it
		if (!in_array($sitename, $active_sites))
			continue;

		$site = $sociable_known_sites[$sitename];

		$url = $site['url'];
		$url = str_replace('PERMALINK', $permalink, $url);
		$url = str_replace('TITLE', $title, $url);
		$url = str_replace('RSS', $rss, $url);
		$url = str_replace('BLOGNAME', $blogname, $url);
		$url = str_replace('EXCERPT', $excerpt, $url);
		$url = str_replace('FEEDLINK', $blogrss, $url);
		
		if (isset($site['description']) && $site['description'] != "") {
			$description = $site['description'];
		} else {
			$description = $sitename;
		}
		if ($i == 0) {
			$link = '<li class="sociablefirst">';
		} else if ($totalsites == ($i+1)) {
			$link = '<li class="sociablelast">';
		} else {
			$link = '<li>';
		}
		$link .= "<a rel=\"nofollow\"";
		if (get_option('sociable_usetargetblank')) {
			$link .= " target=\"_blank\"";
		}
		$link .= " href=\"$url\" title=\"$description\">";
		$link .= "<img src=\"".$imagepath.$site['favicon']."\" title=\"$description\" alt=\"$description\" class=\"sociable-hovers";
		if (isset($site['class']) && $site['class'])
			$link .= " sociable_{$site['class']}";
		$link .= "\" />";
		$link .= "</a></li>";
		
		// $html .= "\t".$link."\n";
		$html .= "\t".apply_filters('sociable_link',$link)."\n";
		$i++;
	}

	$html .= "</ul>\n</div>\n";

	return $html;
}

// Hook the_content to output html if we should display on any page
$sociable_contitionals = get_option('sociable_conditionals');
if (is_array($sociable_contitionals) and in_array(true, $sociable_contitionals)) {
	add_filter('the_content', 'sociable_display_hook');
	add_filter('the_excerpt', 'sociable_display_hook');
	// add_filter('the_excerpt_rss', 'sociable_display_hook');
	
	function sociable_display_hook($content='') {
		$conditionals = get_option('sociable_conditionals');
		if ((is_home()     and $conditionals['is_home']) or
		    (is_single()   and $conditionals['is_single']) or
		    (is_page()     and $conditionals['is_page']) or
		    (is_category() and $conditionals['is_category']) or
			(is_tag() 	   and $conditionals['is_tag']) or
		    (is_date()     and $conditionals['is_date']) or
			(is_author()   and $conditionals['is_author']) or
		    (is_search()   and $conditionals['is_search'])) {
			$content .= sociable_html();
		} elseif ((is_feed() and $conditionals['is_feed'])) {
			$sociable_html = sociable_html();
			$sociable_html = strip_tags($sociable_html,"<a><img>");
			$content .= $sociable_html . "<br/><br/>";
		}
		return $content;
	}
}

// Plugin config/data setup
register_activation_hook(__FILE__, 'sociable_activation_hook');

function sociable_activation_hook() {
	return sociable_restore_config(False);
}

// restore built-in defaults, optionally overwriting existing values
function sociable_restore_config($force=False) {
	// Load defaults, taking care not to smash already-set options
	global $sociable_known_sites;

	if ($force or !is_array(get_option('sociable_active_sites')))
		update_option('sociable_active_sites', array(
			'Print',
			'Digg',
			'Sphinn',
			'del.icio.us',
			'Facebook',
			'Mixx',
			'Google',
		));

	// tagline defaults to a Hitchiker's Guide to the Galaxy reference
	if ($force or !is_string(get_option('sociable_tagline')))
		update_option('sociable_tagline', "<strong>" . __("Share and Enjoy:", 'sociable') . "</strong>");

	// only display on single posts and pages by default
	if ($force or !is_array(get_option('sociable_conditionals')))
		update_option('sociable_conditionals', array(
			'is_home' => False,
			'is_single' => True,
			'is_page' => True,
			'is_category' => False,
			'is_tag' => False,
			'is_date' => False,
			'is_search' => False,
			'is_author' => False,
			'is_feed' => False,
		));

	if ($force or !is_bool(get_option('usecss')))
		update_option('sociable_usecss', true);
}

// Hook the admin_menu display to add admin page
add_action('admin_menu', 'sociable_admin_menu');
function sociable_admin_menu() {
	add_submenu_page('options-general.php', 'Sociable', 'Sociable', 8, 'Sociable', 'sociable_submenu');
}

function sociable_admin_js() {
	if (isset($_GET['page']) && $_GET['page'] == 'Sociable') {
		global $sociablepluginpath;
		
		wp_enqueue_script('jquery'); 
		wp_enqueue_script('jquery-ui-core',false,array('jquery')); 
		wp_enqueue_script('jquery-ui-sortable',false,array('jquery','jquery-ui-core')); 
		wp_enqueue_script('sociable-js',$sociablepluginpath.'sociable-admin.js',array('jquery','jquery-ui-core','jquery-ui-sortable')); 
	}
}
add_action('admin_print_scripts', 'sociable_admin_js');

function sociable_admin_css() {
	global $sociablepluginpath;
	if (isset($_GET['page']) && $_GET['page'] == 'Sociable')
		wp_enqueue_style('sociable-css',$sociablepluginpath.'sociable-admin.css'); 
}
add_action('admin_print_styles', 'sociable_admin_css');

function sociable_js() {
	if (in_array('Wists', get_option('sociable_active_sites'))) {
		global $sociablepluginpath;
		wp_enqueue_script('sociable-wists',$sociablepluginpath.'wists.js'); 
	}	
}
add_action('wp_print_scripts', 'sociable_js');

function sociable_css() {
	if (get_option('sociable_usecss') == true) {
		global $sociablepluginpath;
		echo '<link rel="stylesheet" href="'.$sociablepluginpath.'sociable.css" type="text/css" media="screen" charset="utf-8"/>';
	}
}
add_action('wp_head', 'sociable_css');

function sociable_message($message) {
	echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}

function sociable_meta() {
	global $post;
	$sociableoff = false;
	$sociableoffmeta = get_post_meta($post->ID,'sociableoff',true);
	if ($sociableoffmeta == "true") {
		$sociableoff = true;
	}
	?>
	<input type="checkbox" name="sociableoff" <?php if ($sociableoff) { echo 'checked="checked"'; } ?>/> <?php _e('Sociable disabled?','sociable') ?>
	<?php
}

function sociable_meta_box() {
	add_meta_box('sociable','Sociable','sociable_meta','post');
	add_meta_box('sociable','Sociable','sociable_meta','page');
}
add_action('admin_menu', 'sociable_meta_box');

function sociable_insert_post($pID) {
	delete_post_meta($pID, 'sociableoff');
	update_post_meta($pID, 'sociableoff', ($_POST['sociableoff'] ? 'true' : 'false'));
}
add_action('wp_insert_post', 'sociable_insert_post');

// The admin page
function sociable_submenu() {
	global $sociable_known_sites, $sociable_date, $sociablepluginpath;

	// update options in db if requested
	if (isset($_REQUEST['restore']) && $_REQUEST['restore']) {
		check_admin_referer('sociable-config');
		sociable_restore_config(True);
		sociable_message(__("Restored all settings to defaults.", 'sociable'));
	} else if (isset($_REQUEST['save']) && $_REQUEST['save']) {
		check_admin_referer('sociable-config');
		// update active sites
		$active_sites = Array();
		if (!$_REQUEST['active_sites'])
			$_REQUEST['active_sites'] = Array();
		foreach($_REQUEST['active_sites'] as $sitename=>$dummy)
			$active_sites[] = $sitename;
		update_option('sociable_active_sites', $active_sites);
		// have to delete and re-add because update doesn't hit the db for identical arrays
		// (sorting does not influence associated array equality in PHP)
		delete_option('sociable_active_sites', $active_sites);
		add_option('sociable_active_sites', $active_sites);

		if (isset($_POST['usetargetblank']) && $_POST['usetargetblank']) {
			update_option('sociable_usetargetblank',true);
		} else {
			update_option('sociable_usetargetblank',false);
		}
		
		// update conditional displays
		$conditionals = Array();
		if (!$_POST['conditionals'])
			$_POST['conditionals'] = Array();
		
		$curconditionals = get_option('sociable_conditionals');
		if (!array_key_exists('is_feed',$curconditionals)) {
			$curconditionals['is_feed'] = false;
		}
		foreach($curconditionals as $condition=>$toggled)
			$conditionals[$condition] = array_key_exists($condition, $_POST['conditionals']);
			
		update_option('sociable_conditionals', $conditionals);

		// update tagline
		if (!$_REQUEST['tagline'])
			$_REQUEST['tagline'] = "";
		update_option('sociable_tagline', $_REQUEST['tagline']);

		update_option('sociable_imagedir', $_REQUEST['imagedir']);

		if (!$_REQUEST['usecss'])
			$usecss = false;
		else
			$usecss = true;
		update_option('sociable_usecss', $usecss);
		
		sociable_message(__("Saved changes.", 'sociable'));
	}
	
	// show active sites first and in order
	$active_sites = get_option('sociable_active_sites');
	$active = Array(); $disabled = $sociable_known_sites;
	foreach($active_sites as $sitename) {
		$active[$sitename] = $disabled[$sitename];
		unset($disabled[$sitename]);
	}
	uksort($disabled, "strnatcasecmp");

	// load options from db to display
	$tagline 		= stripslashes(get_option('sociable_tagline'));
	$imagedir 		= stripslashes(get_option('sociable_imagedir'));
	$conditionals 	= get_option('sociable_conditionals');
	$updated 		= get_option('sociable_updated');
	$usetargetblank = get_option('sociable_usetargetblank');
	
	// display options
?>
<form action="<?php echo attribute_escape( $_SERVER['REQUEST_URI'] ); ?>" method="post">
<?php
	if ( function_exists('wp_nonce_field') )
		wp_nonce_field('sociable-config');
?>

<div class="wrap">
	<h2><?php _e("Sociable Options", 'sociable'); ?></h2>
	<table class="form-table">
	<tr>
		<th>
			<?php _e("Sites", "sociable"); ?>:<br/>
			<small><?php _e("Check the sites you want to appear on your site. Drag and drop sites to reorder them.", 'sociable'); ?></small>
		</th>
		<td>
			<div style="width: 100%; height: 100%">
			<ul id="sociable_site_list">
				<?php foreach (array_merge($active, $disabled) as $sitename=>$site) { ?>
					<li id="<?php echo $sitename; ?>"
						class="sociable_site <?php echo (in_array($sitename, $active_sites)) ? "active" : "inactive"; ?>">
						<input
							type="checkbox"
							id="cb_<?php echo $sitename; ?>"
							name="active_sites[<?php echo $sitename; ?>]"
							<?php echo (in_array($sitename, $active_sites)) ? ' checked="checked"' : ''; ?>
						/>
						<img src="<?php echo $sociablepluginpath.'images/'.$site['favicon']; ?>" width="16" height="16" alt="" />
						<?php echo $sitename; ?>
					</li>
				<?php } ?>
			</ul>
			</div>
			<input type="hidden" id="site_order" name="site_order" value="<?php echo join('|', array_keys($sociable_known_sites)) ?>" />
		</td>
	</tr>
	<tr>
		<th scope="row" valign="top">
			<?php _e("Tagline", "sociable"); ?>
		</th>
		<td>
			<?php _e("Change the text displayed in front of the icons below. For complete customization, copy the contents of <em>sociable.css</em> in the Sociable plugin directory to your theme's <em>style.css</em> and disable the use of the sociable stylesheet below.", 'sociable'); ?><br/>
			<input size="80" type="text" name="tagline" value="<?php echo attribute_escape($tagline); ?>" />
		</td>
	</tr>
	<tr>
		<th scope="row" valign="top">
			<?php _e("Position:", "sociable"); ?>
		</th>
		<td>
			<?php _e("The icons appear at the end of each blog post, and posts may show on many different types of pages. Depending on your theme and audience, it may be tacky to display icons on all types of pages.", 'sociable'); ?><br/>
			<br/>
			<input type="checkbox" name="conditionals[is_home]"<?php echo ($conditionals['is_home']) ? ' checked="checked"' : ''; ?> /> <?php _e("Front page of the blog", 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_single]"<?php echo ($conditionals['is_single']) ? ' checked="checked"' : ''; ?> /> <?php _e("Individual blog posts", 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_page]"<?php echo ($conditionals['is_page']) ? ' checked="checked"' : ''; ?> /> <?php _e('Individual WordPress "Pages"', 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_category]"<?php echo ($conditionals['is_category']) ? ' checked="checked"' : ''; ?> /> <?php _e("Category archives", 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_tag]"<?php echo ($conditionals['is_tag']) ? ' checked="checked"' : ''; ?> /> <?php _e("Tag listings", 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_date]"<?php echo ($conditionals['is_date']) ? ' checked="checked"' : ''; ?> /> <?php _e("Date-based archives", 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_author]"<?php echo ($conditionals['is_author']) ? ' checked="checked"' : ''; ?> /> <?php _e("Author archives", 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_search]"<?php echo ($conditionals['is_search']) ? ' checked="checked"' : ''; ?> /> <?php _e("Search results", 'sociable'); ?><br/>
			<input type="checkbox" name="conditionals[is_feed]"<?php echo ($conditionals['is_feed']) ? ' checked="checked"' : ''; ?> /> <?php _e("RSS feed items", 'sociable'); ?><br/>
		</td>
	</tr>
	<tr>
		<th scope="row" valign="top">
			<?php _e("Use CSS:", "sociable"); ?>
		</th>
		<td>
			<input type="checkbox" name="usecss" <?php echo (get_option('sociable_usecss')) ? ' checked="checked"' : ''; ?> /> <?php _e("Use the sociable stylesheet?", "sociable"); ?>
		</td>
	</tr>
	<tr>
		<th scope="row" valign="top">
			<?php _e("Image directory", "sociable"); ?>
		</th>
		<td>
			<?php _e("Sociable comes with a nice set of images, if you want to replace those with your own, enter the URL where you've put them in here, and make sure they have the same name as the ones that come with Sociable.", 'sociable'); ?><br/>
			<input size="80" type="text" name="imagedir" value="<?php echo attribute_escape($imagedir); ?>" />
		</td>
	</tr>
	<tr>
		<th scope="row" valign="top">
			<?php _e("Open in new window:", "sociable"); ?>
		</th>
		<td>
			<input type="checkbox" name="usetargetblank" <?php echo (get_option('sociable_usetargetblank')) ? ' checked="checked"' : ''; ?> /> <?php _e("Use <code>target=_blank</code> on links? (Forces links to open a new window)", "sociable"); ?>
		</td>		
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<span class="submit"><input name="save" value="<?php _e("Save Changes", 'sociable'); ?>" type="submit" /></span>
			<span class="submit"><input name="restore" value="<?php _e("Restore Built-in Defaults", 'sociable'); ?>" type="submit"/></span>
		</td>
	</tr>
</table>

<h2><?php _e('Like this plugin?','sociable'); ?></h2>
<p><?php _e('Why not do any of the following:','sociable'); ?></p>
<ul class="sociablemenu">
	<li><?php _e('Link to it so other folks can find out about it.','sociable'); ?></li>
	<li><?php _e('<a href="http://wordpress.org/extend/plugins/sociable/">Give it a good rating</a> on WordPress.org.','sociable'); ?></li>
	<li><?php _e('<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2017947">Donate a token of your appreciation</a>.','sociable'); ?></li>
</ul>
<h2><?php _e('Need support?','sociable'); ?></h2>
<p><?php _e('If you have any problems or good ideas, please talk about them in the <a href="http://wordpress.org/tags/sociable">Support forums</a>.', 'sociable'); ?></p>

<h2><?php _e('Credits','sociable'); ?></h2>
<p><?php _e('<a href="http://yoast.com/wordpress/sociable/">Sociable</a> was originally developed by <a href="http://push.cx/">Peter Harkins</a> and has been maintained by <a href="http://yoast.com/">Joost de Valk</a> since the beginning of 2008. It\'s released under the GNU GPL version 2.','Sociable'); ?></p>

</div>
</form>
<?php
}

function sociable_add_ozh_adminmenu_icon( $hook ) {
	static $sociableicon;
	if (!$sociableicon) {
		$sociableicon = WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/book_add.png';
	}
	if ($hook == 'Sociable') return $sociableicon;
	return $hook;
}

function sociable_filter_plugin_actions( $links, $file ){
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=Sociable">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

add_filter( 'plugin_action_links', 'sociable_filter_plugin_actions', 10, 2 );
add_filter( 'ozh_adminmenu_icon', 'sociable_add_ozh_adminmenu_icon' );				

if (get_option('sociable_usecss_set_once') != true) {
	update_option('sociable_usecss', true);
	update_option('sociable_usecss_set_once', true);
}

require_once("yoast-posts.php");
?>