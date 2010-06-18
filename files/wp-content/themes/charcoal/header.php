<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--[if IE 7]>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/ie7.css" type="text/css" media="screen" />
<![endif]-->
<!--[if lt IE 7]>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/ie.css" type="text/css" media="screen" />
	<script src="<?php bloginfo('template_directory'); ?>/scripts/jquery.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php bloginfo('template_directory'); ?>/scripts/jquery.pngfix.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php bloginfo('template_directory'); ?>/scripts/fixpngs.js" type="text/javascript" charset="utf-8"></script>
<![endif]-->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
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

	<div id="page-top">
		<div id="wrapper-top">
			<div id="top-primary">
				<div id="header">
					<div id="title">
							<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
							<div class="tagline"><?php bloginfo('description'); ?></div>
					</div><!-- end "title" --> 
					<div id="navbar">
						<ul>
							<?php wp_list_pages('sort_column=menu_order&depth=1&title_li='); ?>
							<?php wp_register('<li class="admintab">','</li>'); ?>
						</ul>
					</div><!-- end "navbar" -->
				</div><!-- end "header" -->

				<div id="main-posts">
