=== Plugin Name ===
Contributors: BraveNewCode
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40bravenewcode%2ecom&item_name=WPtouch%20Beer%20Fund&no_shipping=0&no_note=1&tax=0&currency_code=CAD&lc=CA&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: wptouch, iphone, ipod touch, theme, apple, mac, bravenewcode, ajax, mobile, mobile, android, blackberry
Requires at least: 2.3.x
Tested up to: 2.7.1
Stable tag: 1.8.9.1

WPtouch automatically transforms your WordPress blog into an iPhone application-style theme, complete with ajax loading articles and effects, when viewed from an iPhone or iPod touch.

Downloaded over *95,000 times* (bravenewcode.com + wordpress.org) since April 2008. 

== Description ==

With a single click, *WPtouch* transforms your WordPress blog into an iPhone application-style theme, complete with ajax loading articles and effects, when viewed from an iPhone or iPod touch.

The admin panel allows you to customize many aspects of its appearance, and deliver a fast, user-friendly and stylish version of your site to your iPhone and iPod touch visitors without modifying a single bit of code (or affecting) your regular desktop theme.

The theme also includes the ability for your visitors to easily switch between the *WPtouch* view and your site's regular theme.

= New In Version 1.8.9.1: =

* Fixed refresh issue (some pages keep re-loading)
* Fixed mkdir issue on PHP4 installations
* Set viewport to fixed width for device to prevent some sites from loading wide
* Minor revisions to new CSS calendar icon styling
* Added exclusive mode feature to help in situations where other plugins are incompatible, load too many scripts/css files and both break and slow down WPtouch
* Added Fancybox for some feature descriptions in the admin


Please visit http://bravenewcode.com/wptouch/ for a full description & updates on the WPtouch plugin.

You can also checkout our Support Forums at http://support.bravenewcode.com to post questions and learn tips and tricks for WPtouch and our other plugins.


== What's New ==

(Here's the complete changelog)

= Version 1.8.9.1 (latest): =

* Fixed refresh issue (some pages keep re-loading)
* Fixed mkdir issue on PHP4 installations
* Set viewport to fixed width for device to prevent some sites from loading wide
* Minor revisions to new CSS calendar icon styling
* Added exclusive mode feature to help in situations where other plugins are incompatible, load too many scripts/css files and both break and slow down WPtouch
* Added Fancybox for some feature descriptions in the admin

= Versions 1.8 to 1.8.7: =

* Changed calendar icons from images to CSS-based only (they look sexay!)
* Refined styling of header logo, text shadow, general appearance
* Removed unneeded declarations from the WPtouch stylesheet
* Tested and works efficiently with WordPress MU when installed site-wide (Finally!)
* Please read the details in this readme regarding use on WordPress MU
* Disqus commenting plugins out-of-the-box styling enhancements
* Changed post nav on the single post page to prev/next post, instead of entry titles for length's sake
* We're working on IntenseDebate plugin support, should be out with the next revision
* Added javascript to support framesets and screen size detection
* Fixed bug related to RSS feeds being broken in some situations
* Fixed fatal error on line 153 undefined 'is_front_page' function for WP 2.3.x users
* Fixed jQuery failing to load for WP 2.3.x users
* Added option for font-zoom on rotate for accessibility, on by default
* Fixed various styling bugs
* Changed switch link in WPtouch to remain fixed width
* Fixed various content overflow issues in WPtouch theme files
* As a note for WordPress 2.3 users, WPtouch 1.9 will require WordPress 2.5+
* Fixed new switch link to work under different WordPress install scenarios
* Fixed switch link CSS style-sheet loading issues in some situations
* Fixed missing mime types for icon upload through IE7
* Fixed issues related to automatic favicon generation on a Links page
* Changed footer switch links to mimic iPhone settings app appearance
* Fixed misc scenarios for ajax-upload errors
* Fixed path issues related to custom icons (sites on windows servers)
* Fixed issues related to ajax comments not working in some situations
* Added check for 'Allow Comments' on pages
* Fixed hidden Apache error (reported in logs)
* Fixed admin styling issues on IE7, Firefox
* Fixed issue with custom icons and the header logo
* Fixed issue with the Classic background not appearing
* Significant rewrite of core code for increased efficiency
* Changed database calls to use wpdb object, will hopefully work with wpmu
* Internationalization preparation of the admin and theme files (for WPtouch 1.9)
* Added ability to add/delete custom icons that survive WPtouch & WordPress upgrades
* Added ability to select left/full text justification, 3 font sizes
* Changed how WPtouch admin panel shows icons, more room for custom icons
* Added channel capability for Adsense
* Now suppresses banners created by the Ribbon Manger Plugin
* Minor tweaks to login, register, admin links, footer appearance
* Minor tweaks to drop down menus, header styling
* More refinements for search, categories & tag pages, 'load more' link
* Text & code refinements in the WPtouch admin
* Experimental support for the Blackberry Storm
* Fixed issue with WPtouch header title display issue
* Fixed issue related to login/logout/admin/register link path issues
* Fixed issue where Bookmarks link when Advanced JS is turned off
* Fixed issue with default icon case
* Fixed issue with switch code on systems with PHP4
* Fixed issue related to fresh installs
* Fixed issue with pre 2.7 versions of WordPress admin
* Fixed issue with Android and the sub-header menu links not working


= Version 1.7 =

* Added option to do GZIP compression
* Suppressed warning about multiple gzhandlers
* Fixed user agent detection code
* Added ability to choose if WPtouch or regular version of your site is shown first 
* Fixed WP login/out button bugs
* Added login/out auto-detect for WP 2.7 or pre-WP 2.7 sites
* Fixed loading path issue that caused drop-down menu button to fail
* Added choice between alphabetical or page order sorting of the drop down menu
* Added clock icon
* Fixes for categories drop-down menu (now shows post #'s)
* Minor fix for categories drop-down menu
* Automatic detection & support for Peter's anti-spam plugin
* Built-in support for Adsense in posts
* Moved Stats tracking box beside Advertising Options
* Better WordPress version support detection
* More refined image auto-sizing with WP added images & galleries in posts / pages
* Fix for WordPress shortcodes appearing in excerpts
* Changed how WPtouch shows switch links
* Auto-adjusting width/height for MobileSafari plugin objects (YouTube, Quicktime)


= Version 1.6 =

* Auto-resizing images in posts/pages on orientation change!
* Auto-resizing WP image galleries
* Better support for captions on images, gallery items
* Added the ability to enable a quick login button w/ drop-down in the WPtouch header
* Added the ability to enable categories as a drop-down item in the WPtouch header
* Added the ability to disable WPtouch automatic homepage redirection (resolves white page issue)
* Added the ability to manually select a re-direct landing page
* Refinements in WPtouch admin
* Enhanced support for WordPress 2.7 admin
* Re-designed post comment bubble icon
* Input box to inject custom code (Google Analytics, MINT, etc) into WPtouch
* Basic support for Incognito & WebMate browsers on iPhone & iPod touch
* Other code fixes, cleanups & optimizations
* Other theme style cleanups and enhancements


= Version 1.5 =

* Added support for WordPress image galleries
* Added support for single post page split navigation
* Fixed admin footer links which did not locate WordPress install correctly
* Added basic Google Android support
* Changes in WPtouch admin appearance and styling
* Added donate message in WPtouch admin
* WPtouch now supports WordPress 2.3 or higher


= Version 1.4 =

* More jQuery tune-ups, now loads through wp_enqueue_script() or Google to prevent collisions
* Changed $J to $wptouch to prevent collisions using jQuery
* Offloaded jQuery loading from our folder to Google instead for WP > 2.5 sites
* Fixed a bug in wptouch.php on line 232, fixing drop-down menu display issue
* Fixed a bug where blank admin options were allowed instead of refused
* Fixed a bug with overriding the site title in the WPtouch admin
* Fixed some instances where ajax comments would not work
* Fixed a bug where the loading of javascript files would load in your site's default theme
* Enhanced drop-down menu appearance
* More compatibility with other plugins
* Code cleanups and optimizations


= Version 1.3 =

* Tweaks for the jQuery bugs
* No conflict setting added for jQuery
* Support for DISQUS 2.0.2-x Plugin
* Minor style edits and enhancements for the search dropdown
* Another fix for drop-down Menu not working
* Added ability to change the header border background color
* Fix for slashes appearing before apostrophes in the header title
* Admin wording changes, styling changes
* Minor style enhancements to the theme
* Fix for Menu not working on some installations
* Style enhancements for the menu, search, drop downs
* Style enhancements for comments, logged in users
* Font adjustments for titles
* Style changes for single post page heading, for better clarity
* Admin wording changes


= Version 1.2 =

* Fix for the theme appearing in Safari browsers
* Switch from Prototype to the more WordPress-native jQuery for javascript (much faster!)
* Fix for wrong re-directs happening unintentionally if you use a static home page
* Elimination of unneeded images, javascript (shaving more than 100KB!)
* More template file cleanups, image & code optimizations
* The addition of more comments in code templates to help you make custom modifications
* Option to enable comments on pages
* Option to manually enter in a new blog title (fixes cases where the blog title runs the length of the header and wraps)
* Option to hide/show excerpts by default on the home, search, and archive pages
* Switch code links are automatically injected into your regular theme's footer now, and is only seen on the iPhone/ipod touch
* In all, despite the addition of new features we've cut load times for WPtouch in half with this release over 1.2.x releases!
* The ability to disable Gravatars in comments (more control over optimization & speed)
* Redundant, unused template file cleanups (archive.php, search.php & page.php are now all just index.php)
* More style enhancements and tweaks, fixes
* Switched to Snoopy from CURL for the admin news section (thanks to Joost de Valk (yoast.com)


= Version 1.1 =

* The ability to disable advanced javascript effects (fixes effects not working for some, speeds up the load time considerably)
* Proper styling of embedded YouTube videos on mobileSafari in iPhone 2.0
* Fix for the switch code not working on some blog installations
* Redundant, unused code cleanups
* More style enhancements and tweaks, fixes
* the ability to enable/disable the default home, rss and email menu items
* support for WordPress installations that have static home pages
* dynamic WPtouch news in the administration panel
* the ability to modify the default hyperlink color
* major CSS & PHP cleaning, resulting in reduced size and faster load times
* the ability to enable/disable tags, categories and author names on the index, search and author pages
* support for DISQUS commenting
* CSS refinements for comments, the drop-down menu, and overall appearance
* styling for YouTube embedded videos
* bug fixes for blogs installed in directories other than root


== Installation ==

= Pre 2.7 =
Download, unzip, and upload the 'wptouch' folder and all its contents to your WordPress wp-content/plugins folder using FTP. Visit the plugin tab in the WordPress admin, and activate *WPtouch*. You can then setup your plugin options by visiting the Settings - >WPtouch tab.

= 2.7+ =
You can now install *WPtouch* directly from the WordPress admin!

= WordPress MU =
If you'd like to use *WPtouch* with WordPress MU as a site-wide plugin, simple install the wptouch folder in the mu-plugins directory.  Once complete, either move wptouch.php back a directory (into the mu-plugins directory), or create a symbolic link to it.


Please visit http://www.bravenewcode.com/wptouch/ for comprehensive installation instructions.

You can also checkout our Support Forums at http://support.bravenewcode.com to post questions and learn tips and tricks for *WPtouch* and our other plugins.


== Frequently Asked Questions ==

= I thought the iPhone/iPod touch/Android shows my website fine the way it is now? =

Yes, that's true for the most part. However, not all websites are created equal, with some sites failing to translate well in the viewport of a small mobile device. Many WordPress sites today make heavy use of different javascripts which significantly increase the load time of pages, and drive your visitors on 3G/EDGE batty. So we've come up with *WPtouch*, a lightweight, fast-loading, feature-rich and highly-customized "theme application" which includes an admin interface to let you customize many aspects of your site's presentation.

= Well, what if my users don't like it and want to see my regular site? =

There's a mobile switch option in the footer on *WPtouch* for your users to easily switch between the *WPtouch* view and your site's regular appearance. It's that easy. We even automatically put a little snippet of code into your current theme which will be shown only to iPhone/iPod/Android visitors, giving them control to switch between the two site themes easily.

= Will it slow down my blog, or increase my server load? =

Not bloody likely! Unless of course you're getting slammed with all sorts of traffic because you've installed this sexy thang. The entire theme files package for *WPtouch* is small (300kb or so without the screenshots). It was designed to be as lightweight and speedy as possible, while still serving your site's content in a richly presented way, sparing no essential features like search, login, categories, tags, comments etc.

== Screenshots ==

1. Posts on the front page
2. Drop down menu navigation
3. Single post page w/ YouTube video
4. Post meta data, mail, social bookmarking and navigation
5. Comment box (ajax powered)
6. Links with automatic favicon support
7. Archives page with tag cloud & monthly list 
8. Sample post with image auto-size
9. Built-in 'e-mail post' capability
10. WordPress Photo Gallery & Post Nav Support
11. Theme switch link appearance
