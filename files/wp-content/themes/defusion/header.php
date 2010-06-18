<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>
		Simon Cockell |
		<?php if ( is_home() ) { ?><?php bloginfo('description'); ?> | <? bloginfo('name'); ?><?php } ?>
		<?php if ( is_search() ) { ?><?php echo $s; ?> | <? bloginfo('name'); ?><?php } ?>
		<?php if ( is_single() ) { ?><?php wp_title(''); ?> | <? bloginfo('name'); ?><?php } ?>
		<?php if ( is_page() ) { ?><?php wp_title(''); ?> | <? bloginfo('name'); ?><?php } ?>
		<?php if ( is_category() ) { ?>Archive <?php single_cat_title(); ?> | <? bloginfo('name'); ?><?php } ?>
		<?php if ( is_month() ) { ?>Archive <?php the_time('F'); ?> | <? bloginfo('name'); ?><?php } ?>
		<?php if ( is_tag() ) { ?><?php single_tag_title();?> | <? bloginfo('name'); ?><?php } ?>
		<?php if ( is_404() ) { ?>Sorry, not found! | <? bloginfo('name'); ?><?php } ?>
</title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/nav.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/dropdowns.js"></script>
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
<!--Added for Google Analytics -->
<link rel="me" type="text/html" href="http://www.google.com/profiles/sjcockell"/>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
	var pageTracker = _gat._getTracker("UA-8218814-2");
	pageTracker._trackPageview();
} catch(err) {}</script>
<!-- Added for ClaimID -->
<meta name="microid" content="mailto+http:sha1:afed9572ec71ac8029afa9603c4cafd932c8729d" />
</head>

<body>

<div id="page">

<div id="header">

	<h1><a href="<?php bloginfo('url'); ?>"><? bloginfo('name'); ?></a></h1>
	<h2 id="blog-description"><? bloginfo('description'); ?></h2>

	<ul id="nav">
		<li class="page_item current_page_item" id="first"><a href="<?php bloginfo('url'); ?>">Home</a></li>
		<?php wp_list_pages('title_li=&depth=2&sort_column=menu_order'); ?>
	</ul>
	
	<ul id="top-nav">
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		<?php wp_meta(); ?>
		<li id="rss"><a href="<?php bloginfo('rss2_url'); ?>">Subscribe RSS Feed</a></li>
	</ul>
	
	<div class="clear"></div>

</div><!-- end header -->
