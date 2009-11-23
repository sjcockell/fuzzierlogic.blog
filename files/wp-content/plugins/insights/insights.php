<?php

// pluginname Insights for WordPress
// shortname WPInsights
// dashname insights


/*
Plugin Name: Insights
Version: 1.0.2
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/insights
Author: Vladimir Prelovac
Author URI: http://www.prelovac.com/vladimir
Description: Insights allows you to quickly search and insert information (links, images, videos, maps, news..) into your blog posts.


*/

// todo: more for images (page)
// auto tags



global $wp_version;	

$exit_msg='Insights for WordPress requires WordPress 2.3 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version,"2.3","<"))
{
	exit ($exit_msg);
}


// Avoid name collisions.
if ( !class_exists('WPInsights') ) :

class WPInsights {
	
	// Name for our options in the DB
	var $DB_option = 'WPInsights_options';
	var $plugin_url;
	
	// Initialize WordPress hooks
	function WPInsights() {	
		$this->plugin_url = defined('WP_PLUGIN_URL') ? WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)); 
				
		// Add Options Page
		add_action('admin_menu',  array(&$this, 'admin_menu'));
		
		// print scripts action
		add_action('admin_print_scripts-post.php',  array(&$this, 'scripts_action'));		
		add_action('admin_print_scripts-page.php',  array(&$this, 'scripts_action'));		
		add_action('admin_print_scripts-post-new.php',  array(&$this, 'scripts_action'));
		add_action('admin_print_scripts-page-new.php',  array(&$this, 'scripts_action'));
		
	  
		//add_action( 'init', array( &$this, 'add_tinymce' ));

	}

function add_tinymce() {
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
		return;

	if ( get_user_option('rich_editing') == 'true' ) {

		add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( &$this, 'add_tinymce_button' ));
	}
}


function add_tinymce_button( $buttons ) {
	array_push( $buttons, "separator", 'btnInsights' );
	return $buttons;
}

function add_tinymce_plugin( $plugin_array ) {
	$plugin_array['insights'] = $this->plugin_url. '/insights-mceplugin.js';
	return $plugin_array;
}

function scripts_action() 
	{	
		$options=$this->get_options();
	
		$interactive=$options['interactive'] ? 1 : 0; ;
		$google_maps_api_key=$options['maps_api'];
	
		$nonce=wp_create_nonce('insights-nonce');
		
		if ($options['gmaps'])
			echo '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$google_maps_api_key.'" type="text/javascript"></script>';

		wp_enqueue_script('jquery');		 	
		wp_enqueue_script('insights', $this->plugin_url.'/js/insights.js', array('jquery'));
		wp_localize_script('insights', 'InsightsSettings', array('insights_url' => $this->plugin_url, 'insights_interactive' => $interactive, 'insights_maps_api' => $google_maps_api_key, 'nonce' => $nonce)); 
		  
		   					 	
		if ($options['gmaps'])
		 	wp_enqueue_script('insights-maps', $this->plugin_url.'/js/insights-maps.js'); 			  
		wp_enqueue_script('jQuery.jCache', $this->plugin_url.'/js/jQuery.jCache.js'); 

						
}




	function draw_insights() {
		$options=$this->get_options();
	?>
<p>Enter keywords you would like to search for and press Search button.</p>
<input type="text" id="insights-search" name="insights-search" size="60" autocomplete="off" />
<input id="insights-submit" class="button" type="button" value="Search"  /> <br />

<input name="insights-radio" type="radio" checked="" value="1" /><label> My Blog </label>
<input name="insights-radio" type="radio" value="2"/><label> Images </label>
<input name="insights-radio" type="radio" value="3"/><label> Videos </label>
<input name="insights-radio" type="radio" value="4"/><label> Wikipedia </label>
<input name="insights-radio" type="radio" value="6"/><label> Google </label>
<input name="insights-radio" type="radio" value="7"/><label> News </label>
<input name="insights-radio" type="radio" value="10"/><label> Blogs </label>
<input name="insights-radio" type="radio" value="11"/><label> Books</label>
<?php
if ($options['gmaps'])
	echo '<input name="insights-radio" type="radio" value="5"/><label> Maps </label>';

?>

<div id="insights-results"></div>
	<div id="insights-map-all" style="display:none" >
		<p>
		<input class="button" type="button" value="Add Map" onclick="insert_map();">
		<input class="button" type="button" value="Add Marker" onclick="createMarkerAt();">
		<input class="button" type="button" value="Clear Markers" onclick="clearMarkers();">
		<input class="button" type="button" value="Clear Path" onclick="clearPolys();">
		</p>
	<div id="insights-map" style="height:450px; width:100%; padding:0px; margin:0px;"></div>
</div>
<?php			
			
	}

	// Hook the options mage
	function admin_menu() {
	
		add_options_page('Insights Options', 'Insights', 8, basename(__FILE__), array(&$this, 'handle_options'));
		add_meta_box( 'WPInsights', 'Insights', array(&$this,'draw_insights'), 'post', 'normal', 'high' );
    add_meta_box( 'WPInsights', 'Insights', array(&$this,'draw_insights'), 'page', 'normal', 'high' );
	
	} 
	
	// Handle our options
	function get_options() {
	   $options = array(		
			'post_results' => 5,
			'image_results' => 16,
			'wiki_results' => 10,
			'video_results' => 20,
			'image_tags' => "on",
			'image_text' => "on",
			'image_nonc' => "",
			'interactive' => '',
			'gmaps' => '',
			'maps_api' => 'enter your key'
		);

    $saved = get_option($this->DB_option);

    if (!empty($saved)) {
        foreach ($saved as $key => $option)
            $options[$key] = $option;
    }           
    
    if ($saved != $options)
    	update_option($this->DB_option, $options); 
    	
    return $options;
	}

	// Set up everything
	function install() {
		$this->get_options();		
		
	}
	
	function handle_options()
	{
		$options = $this->get_options();


		if ( isset($_POST['submitted']) ) {
			
			check_admin_referer('insights');
			
			$options = array();
		
			
			$options['post_results']=(int) $_POST['post_results'];					
			$options['image_results']=(int) $_POST['image_results'];	
			$options['wiki_results']=(int) $_POST['wiki_results'];		
			$options['video_results']=(int) $_POST['video_results'];						
			$options['image_tags']=$_POST['image_tags'];						
			$options['image_nonc']=$_POST['image_nonc'];						
			$options['image_text']=$_POST['image_text'];						
			$options['interactive']=$_POST['interactive'];						
			$options['gmaps']=$_POST['gmaps'];		
			$options['maps_api']=$_POST['maps_api'];
			
		
			update_option($this->DB_option, $options);
			echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
		}

		$post_results=$options['post_results'];
		$image_results=$options['image_results'];
		$wiki_results=$options['wiki_results'];
		$video_results=$options['video_results'];
		$image_tags=$options['image_tags']=='on'?'checked':'';
		$image_text=$options['image_text']=='on'?'checked':'';
		$image_nonc=$options['image_nonc']=='on'?'checked':'';
		$interactive=$options['interactive']=='on'?'checked':'';
		$gmaps=$options['gmaps']=='on'?'checked':'';
		$maps_api=$options['maps_api'];


		$action_url = $_SERVER['REQUEST_URI'];	
		$imgpath=$this->plugin_url.'/img';	

		
		include('insights-options.php');
	
		
	}
	
	

}

endif; 

if ( class_exists('WPInsights') ) :
	
	$WPInsights = new WPInsights();
	if (isset($WPInsights)) {
		register_activation_hook( __FILE__, array(&$WPInsights, 'install') );
	}
endif;

?>