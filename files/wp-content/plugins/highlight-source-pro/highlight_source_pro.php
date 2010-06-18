<?php
/*
Plugin Name: Highlight Source Pro
Plugin URI: http://blog.kno.at/tools/highlight-source-pro
Description: Powerful, XHTML-compliant, server-side code highlighting, credits for everything 
but 'making it a plugin' go to <a href="http://jpipes.com/index.php?/archives/216-Syntax-Highlighting-and-Allowing-HTML-in-Comments.html">Jay Pipes</a> 
and <a href="http://geshi.org/">GeSHi</a>
Version: 1.3
Author: Christian Knoflach
Author URI: http://kno.at/

Copyright 2008 Christian Knoflach (christian@kno.at)

This plugin is pretty much copy & paste from this article:
http://jpipes.com/index.php?/archives/216-Syntax-Highlighting-and-Allowing-HTML-in-Comments.html
with a few modifications to work as a plugin. Credits for all of this go to the authors of
GeSHi, HTMLPurifier and Jay Pipes.

====================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited 
to, the implied warranties of merchantibility and fitness for a particular purpose are disclaimed. 
In no event shall the copyright owner or contributors be liable for any direct, indirect, 
incidental, special, exemplary, or consequential damages (including, but not limited to, 
procurement of substitute goods or services; loss of use, data, or profits; or business 
interruption) however caused and on any theory of liability, whether in contract, strict liability, 
or tort (including negligence or otherwise) arising in any way out of the use of this software, 
even if advised of the possibility of such damage.

For full license details see license.txt
================================================================================================= */

add_filter('the_content', 'hsp_clean_and_codify', 1);
add_action('wp_head', 'hsp_add_css');

function hsp_add_css()
{
    print('<link rel="stylesheet" type="text/css" href="'
            . get_option('siteurl') 
            . '/wp-content/plugins/highlight-source-pro/all.css" />');
    print('<!--[if IE]><link rel="stylesheet" type="text/css" href="'
            . get_option('siteurl') 
            . '/wp-content/plugins/highlight-source-pro/ie.css" /><![endif]-->');
}

/**
  * Highlights the text as code in the supplied language
  *
  * @return string The marked up code
  * @param subject The text to markup
  * @param language The language to use for highlighting
  */
function hsp_syntax_highlight($subject, $language, $offset=1, $title='', $encoded = false)
{
    /* Format the code with GeSHi */
    include_once('geshi/geshi.php');
    
    if ( $encoded ) 
    {
        $subject = html_entity_decode($subject);
    }
    $subject = $subject;
    $geshi= new GeSHi($subject, $language);
    $geshi->set_header_type(GESHI_HEADER_DIV);
    $geshi->enable_classes();
    $geshi->enable_strict_mode($mode);
    $geshi->enable_keyword_links(false);
    $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
    
    if ( $offset != 0 )
    {
        $geshi->start_line_numbers_at($offset);
        $geshi->set_overall_class('geshi '. $language);
    } else {
        $geshi->set_overall_class('geshi no '. $language);
    }
    
    if ( !empty($title) )
    {
        $geshi->set_header_content($title);
    }
    
    return str_replace("'", '&#39;', $geshi->parse_code());
}
 
/**
  * Returns a cleaned and syntax-highlighted string of HTML
  *
  * @return string Cleaned and codified text
  * @param subject The text to cut into code pieces
  */
function hsp_clean_and_codify($subject)
{
    $original= $subject;
    $code_pieces= array();
//    $code_regex = '/[\[\<]pre\s*(lang|language)\=[\"\'](\w+)[\"\'][\]\>]([\D\S]+?)[\[\<]\/pre[\]\>]/';
//    $code_regex = '/[\[\<]pre\s*(lang|language)\=[\"\'](\w+)[\"\'](\s*class=[\"\']([0-9]+)[\"\'])?\s*[\]\>]([\D\S]+?)[\[\<]\/pre[\]\>]/';
//    $code_regex = '/[\[\<]pre\s*(lang|language)\=[\"\'](\w+)[\"\'](\s*class=[\"\']([0-9]+)[\"\'])?\s*[\]\>]([ ]*([^\\n]+))?([\D\S]+?)[\[\<]\/pre[\]\>]/';
    $code_regex = '/[\[\<]pre\s*(lang|language)\=[\"\'](enc__)?(\w+)[\"\'](\s*class=[\"\']([0-9]+)[\"\'])?\s*[\]\>]([ ]*([^\n]+))?\n([\D\S]+?)[\[\<]\/pre[\]\>]/';
    $code_delimiter= "CODECODECODE";
     
    /* First split the text into code and non-code blocks */
    while (preg_match($code_regex, $subject, $code_matches) == 1) 
    {
        $encoded_flag = (strtolower($code_matches[2]) == 'enc__');
        $language= trim(strtolower($code_matches[3])); // 0-index is the full match
        $start_line = $code_matches[5];
        $code_title = trim($code_matches[7]);
        $code_sample= $code_matches[8];
        $entire_code_string= $code_matches[0];
        $code_sample= str_replace("\t", " ", $code_sample); /* Replace tabs with spaces */
        $code_pieces[]= array('enc'=>$encoded_flag, 'lang'=>$language, 'text'=>$code_sample, 'offset'=>$start_line, 'title'=>$code_title);
        $subject= str_replace($entire_code_string, $code_delimiter, $subject);
        $code_matches= array(); //reset
    }
     
    /*
      * Now $subject should contain CleanMarkup\n|||CODE|||\nCleanMarkup...
      * We now replace the code sections by passing an executable string
      * to the regex parser (the /e option) and using the syntax_highlight
      * function to do the grunt work
      */
    $num_code_pieces= count($code_pieces);
    $i= 0;
    
    if ($num_code_pieces > 0) 
    {
        $replacement= "hsp_syntax_highlight(rtrim(\$code_pieces[\$i]['text']), \$code_pieces[\$i]['lang'], \$code_pieces[\$i]['offset'], \$code_pieces[\$i]['title'], \$code_pieces[\$i++]['enc']);";
        $subject= preg_replace('/' . $code_delimiter . '/e', $replacement, $subject);
    }
    
    return $subject;
}
?>