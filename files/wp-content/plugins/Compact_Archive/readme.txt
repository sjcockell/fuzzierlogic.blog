

=====================================================================

Copyright 2007  Rob Marsh, SJ  (http://rmarsh.com)

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


==== Compact Archive Plugin for WordPress v1.0.6 =====================

Displays the monthly archive of posts in a more compact form than the 
usual long list. It can be shown as a compact block suitable for the 
body of an archives page:

		2006: Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec 
		2005: Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec 
		2004: Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec 

or in a more compact form to fit a sidebar:

				2006: J F M A M J J A S O N D 
				2005: J F M A M J J A S O N D 
				2004: J F M A M J J A S O N D 
or something in between:

			2006: 01 02 03 04 05 06 07 08 09 10 11 12
			2005: 01 02 03 04 05 06 07 08 09 10 11 12
			2004: 01 02 03 04 05 06 07 08 09 10 11 12

Plugin URI: http://rmarsh.com/plugins/compact-archives/
Version: 1.0.6
Author: Rob Marsh, SJ
Author URI: http://rmarsh.com/


==== Installation Instructions ======================================

1. Upload the whole plugin folder (Compact_Archive) to your 
/wp-content/plugins/ directory.

2. Go to your Admin|Plugins page and activate Compact Archive. 

3. Put <?php compact_archive(); ?> at the place in your template 
where you want it to appear, e.g., in your sidebar:

	<ul>
		<?php compact_archive(); ?>
	</ul>

4. You might want to adjust your style sheet to make the months
with no posts fade into the background, for example.


==== Usage ==========================================================

Put <?php compact_archive(); ?> at the place in your template 
where you want it to appear, e.g., in your sidebar:

	<ul>
		<?php compact_archive(); ?>
	</ul>
	
The template tag, compact_archive, has some parameters:

	compact_archive($style='initial', $before='<li>', $after='</li>');
	
If $style == 'initial' (the default) the display will fit into a sidebar.
If $style == 'block' the display will be wide enough to fill the main 
column of a page.
If $style == 'numeric' the display will use month numbers.

$before and $after wrap each line of output. The default values make 
each line of the archive into a list item.

	<ul>
		<?php compact_archive(); ?>
	</ul>
	
The year link at the start of each line is wrapped in <strong></strong> and 
months with no posts are wrapped in <span class="emptymonth"></span> so you 
can differentiate them visually using your style sheet.

Compact Archive honours whatever kind of permalink WordPress is using and the 
month names and abbreviations are chosen in accordance with the language locale 
(WPLANG) set in wp-config.php.


==== Version ========================================================

Version 1.0.6 introduces numeric display
Version 1.0.5 fixes a display bug for locales with accented characters
Version 1.0.4 makes use of the Plugin Output Cache to speed things up
Version 1.0.3 fixes a small problem with undated posts 
Version 1.0.1 speeds up the plugin for WordPress 2.1+ 


==== Acknowledgements  ==============================================

The idea for this plugin comes from the SmartArchives plugin by Justin 
Blanton ( http://justinblanton.com/projects/smartarchives/). It is a 
rewrite of the 'block' half of his tag. I have added a very compact 
version that fits nicely in a sidebar. 