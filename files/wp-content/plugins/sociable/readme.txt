=== Sociable ===
Contributors: joostdevalk
Donate link: http://yoast.com/donate/
Tags: social, bookmark, bookmarks, bookmarking, social bookmarking, social bookmarks
Requires at least: 2.6
Tested up to: 2.8
Stable tag: 3.2.3

Automatically add links on your posts, pages and RSS feed to your favorite social bookmarking sites. WordPress 2.6 or above required!

== Description ==
Automatically add links to your favorite social bookmarking sites on your posts, pages and in your RSS feed. You can choose from 99 different social bookmarking sites! WordPress 2.6 or above is required, if you use an older version, please download [this version,](http://downloads.wordpress.org/plugin/sociable.3.0.6.zip), please keep in mind that version is not maintained.

More info:

* More info on [Sociable](http://yoast.com/wordpress/sociable/), with info on how to add sites to it, and how to integrate it into your WordPress in other ways.
* Check out the authors [WordPress Hosting](http://yoast.com/wordpress-hosting/) experience. Good hosting is hard to come by, but it doesn't have to be expensive, Joost tells you why!
* Check out the other [Wordpress plugins](http://yoast.com/wordpress/) by the same author, and read his blog: [Yoast](http://yoast.com).

**Changelog**

* 3.2.3 Removed the last bit of non jQuery javascript, improved styling and visual feedback when selecting a site
* 3.2.2 Moved style loading to admin_print_styles and scripts to admin_print_scripts
* 3.2.1 Fixed a bug with printing styles in 2.8 beta
* 3.2
	This is a MAJOR update to Sociable. It fixes a few bugs, cleans up (or out, actually) a lot of sites that have gone away, updates others to include the excerpt, adds a fantastic new printing and PDF button, an RSS button, and a bunch of new sites. 
	
	Major Thanks to Jean-Paul of [iPhoneclub](http://www.iphoneclub.nl/) for all his work in looking up all the sites.
	
	The full list of changes:
 	* Restored sociable-admin.js, as it got accidentally removed.
	* Added class="sociablefirst" to the first site in the list and class="sociablelast" to the last one
	* Added:
		* An RSS button, which links to your sites RSS feed
		* Printfriendly.com for both printing and creating a PDF, replacing the original "Print" function, which didn't work from RSS
		* Current
		* FriendFeed
		* MSN Reporter.nl 
		* FS Daily
		* Hello TXT
	* Removed the following sites that were no longer working or active:
		* BlinkBits
		* Blogmemes.cn, .net, .jp
		* Blogsvine
		* Book.mark.hu
		* Bumpzee
		* Del.irio.us
		* Feed Me Links
		* Furl (replaced by Diigo)
		* GeenRedactie
		* Kick.ie
		* Leonaut
		* Magnolia
		* Plug IM
		* Pownce
		* Salesmarks
		* Scuttle
		* Shadows
		* Smarking
		* Spurl
		* Taggly
		* Tailrank
		* Tumblr (due to the change to a POST API, which we, unfortunately, can't support with Sociable)
	* Updated the following sites to include the excerpt when submitting:
		* Connotea
		* Delicious
		* Digg
		* Ekudos
		* Google Bookmarks
		* NuJij
		* Ping.fm
	* Otherwise updated:
		* Google Bookmarks (new icon)
		* Fleck (New URL)
		* Rec6 (new URL)
* 3.1.1 Fixed bug with stylesheet introduced in 3.1
* 3.1 Converted all images to PNG, cleaned up usage of javascript in the backend and switched to the jquery library that comes with WordPress, allowed for usage of an external image directory, removed pre 2.6 compatibility fixes. 
* 3.0.6 Fixed xhtml bug in Netvibes integration and added Bitacoras
* 3.0.5 Added Identi.ca and fixed a bug in Yoast Posts widget
* 3.0.4 Added Netvibes
* 3.0.3 Security enhancements, thx to Mark Jaquith
* 3.0.2 Fixed CSS bug introduced in 3.0
* 3.0.1 Removed some other, now obsolete, code, reducing the code size by another 4KB.
* 3.0 Fixed IE bug in admin. Cleaned up Admin Area and changed support messages. Removed directory checking for all images (speeds up incredibly). Made display: inline !important to prevent vertical icon display. Updated Wykop icon.
* 2.9.15 Added a fallback for strip_shortcodes to maintain backwards compatibility with WordPress 2.3 and below.
* 2.9.14 Make sure there are no tags or shortcodes in the excerpt, added ping.fm, removed indiagram (shut down).
* 2.9.13 Changed Facebook link from sharer.php (meant for a popup window) to share.php (which has the actual menu on it etc.)
* 2.9.12 Added Tip'd
* 2.9.11 Added settings link and Ozh admin menu icon
* 2.9.10 Fixes issue with excerpt not being urlencoded
* 2.9.9 Fixes for the custom fields issue.
* 2.9.8 Fixes for WP 2.7
* 2.9.6 Added Symbaloo, Tumblr,
* 2.9.5 Fixed Fark & Propeller links, added missing i18n strings, added Yahoo Buzz 
* 2.9.4 Removed PopCurrent and Rawsugar as they no longer exist, renamed BlueDot to Faves
* 2.9.3 Added Leonaut, MySpace, fixed plugin description, added option to disable Sociable on a per post basis, added option to display sociable on tag pages, added extra security to config page, fixed print button, fixed Twitter functionality.
* 2.9.2 Added Swedish and Chinese localisations, thx to [Mikael Jorhult](http://www.mishkin.se/) and [Hugo Chen](http://take-ez.com/)
* 2.9.1 Fixed bug where jQuery UI would be loaded twice.
* 2.9 Removed Tool-Man in favor of jQuery, thx to Martin Joosse.
* 2.8.4 Bugfixes.
* 2.8.3 Added wikio.it, upnews.it, muti.co.za, makes LinkedIn work even better, and makes opening in a new window optional
* 2.8.2 Now adds icons to feeds with excerpts too, added LinkedIn
* 2.8.1 Fixed some small issues and made sure tagline shows up again
* 2.8 Added option to show bookmark icons in feed, added Ratimarks, fixed xhtml compliance, fixed blue dot bug
* 2.6.9 Fixed WP 2.6 compatibility
* 2.6.8 Updated documentation
* 2.6.7 Renamed Sk*rt to Kirtsy, Added designfloat, fixed description
* 2.5.4 Added HealthRanker, N4G, Meneame, BarraPunto, Laaik.it and E-mail option
* 2.5.3 Added Global Grind, Salesmarks, Webnews.de, Xerpi, Yigg
* 2.5.2 Added NuJIJ, eKudos, Sk-rt, Socialogs and MisterWong.de
* 2.5.1 Swapped Netscape for Propeller

Special thanks to [Robert Harm](http://www.die-truppe.com/) for coming up with loads of nice ideas.

== Installation ==

Download, Upgrading, Installation:

Upgrade

1. First deactivate Sociable
1. Remove the `sociable` directory

**Install**

1. Unzip the `sociable.zip` file. 
1. Upload the the `sociable` folder (not just the files in it!) to your `wp-contents/plugins` folder. If you're using FTP, use 'binary' mode.

**Activate**

1. In your WordPress administration, go to the Plugins page
1. Activate the Sociable plugin and a subpage for Sociable will appear
   in your Options menu.

If you find any bugs or have any ideas, please mail me.

**Advanced Users**

For advanced use of the plugin, see the [Sociable](http://yoast.com/wordpress/sociable/) page on [Yoast](http://yoast.com/)
