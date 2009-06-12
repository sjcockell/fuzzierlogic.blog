=== Easy Gravatars ===

Contributors: dougal
Donate link: http://dougal.gunters.org/donate
Tags: comments, gravatars, gravatar, avatars, avatar, images, personalization
Requires at least: 2.0.4
Tested up to: 2.5
Stable tag: 1.2

Add Gravatars to your comments without modifying any template files. Just
activate, and you're done!

== Description ==

According to the Gravatar.com website, Gravatars are Globally Recognized
Avatars, or an "avatar image that follows you from weblog to weblog
appearing beside your name when you comment on gravatar enabled sites." 
You register with the Gravatar server, and upload an image which you will
use as your avatar. The gravatar image is keyed to your email address, so
that it is unique to you. 

This plugin will display gravatars for the people who comment on your posts.
You do not need to modify any of your template files -- just activate the
plugin, and it will add gravatars to your comments template automatically.

== Installation ==

Copy the easygravatars folder and its contents to your wp-content/plugins
directory, then activate the plugin.

The plugin will add a new Easy Gravatars section under the Options menu.
There, you can configure the size and maximum rating of the gravatars that
you wish to display. You can also set the location of a default image to
display for users who have no gravatar (the default is a 1px transparent
gif), you can tweak the CSS for the wrapper around the image, and you can
select to attach the gravatar to either the comment author link, or the text
of the comment. 

By default, the gravatar is floated to the right of the comment author's
name, which should work well with most templates.

== Frequently Asked Questions ==

= I have a Gravatar, but it doesn't appear =

The Gravatar service allows you to register multiple email addresses. Make
sure that the email address you use in your WordPress comments matches an
email address registered on the Gravatar server.

Also, check the rating of your gravatar versus the maximum allowed rating in
the Easy Gravatar options. If your gravatar is rated 'R', but you set the
maximum Easy Gravatar rating to 'PG', the image will be blocked.

= Some people see Gravatars, but others don't =

In some cases, there have been reports that Internet Explorer 6.0 does not
display gravatars properly. This is probably due to CSS bugs in IE6. I will
try to isolate this as time allows, but the best advice is for users to
upgrade to IE7 (for security and better standards support), or to use
another browser, like Firefox or Opera.

= My comment author links disappear =

You may have another plugin which is conflicting with Easy Gravatars. In
particular, Joost de Valk's Google Analytics plugin version 1.3 also tried
to modify the comment author link. Upgrade to version 1.4 or higher of his
plugin, or select the "Comment Text" API hook in Easy Gravatar's Advanced
Options.

= Gravatars appear, but... =
The placement of the Gravatars within your comments template is controlled
by the `Span style` and `API hook` options. The gravatar is rendered as an
image tag within a span, which is prepended to either the comment author
information or the text of the comment, depending on the hook selected.

The default options should work fine with most WordPress templates, but
there's always the chance that a template with a complex layout might have a
conflict. If simply changing the API hook selection doesn't give a result
which is to your liking, you will need to modify the CSS in the span style.

Sorry, but help with CSS styling is beyond the scope of this document. 

== Credits ==

Based on a code snippet from Matt Mullenweg:
  http://photomatt.net/2007/10/20/gravatar-enabled/
  http://pastebin.ca/743979

Props to David Potter for pointing out that Gravatar normalizes email
addresses to lowercase before hashing with MD5:
  http://dpotter.net/Technical/index.php/2007/10/22/integrating-gravatar-support/

== Changelog ==

= 1.1      2007-11-16 =

* Gravatar images now link to comment author's URL, if provided.
* Gravatar code now prepended to text instead of appended.
* Code reorganized. Functions for filters and hooks are now near the actions
that actually call them.
* Added stylesheet for options page and improved layout. Split options into
'General' and 'Advanced'.
* New advanced option for selecting either 'Comment Author Link' or 'Comment
Text' API hook.

= 1.0 =

* Initial release.

