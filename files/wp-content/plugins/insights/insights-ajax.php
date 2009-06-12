<?php
  //require_once('../../../wp-load.php'); 
  
  require_once('../../../wp-config.php');
  
  if ($_GET['search']) {
  	  
  	  check_ajax_referer('insights-nonce');
  	  
  	 	if ($_GET['mode'] == '6') // mode 6 is google search
          die(search_google($_GET['search']));
      if ($_GET['mode'] == '5') // mode 5 is map search
          die(search_maps($_GET['search']));
      if ($_GET['mode'] == '4') // mode 4 is wiki search
          die(search_wiki($_GET['search']));
      if ($_GET['mode'] == '3') // mode 3 is video search
          die(search_videos($_GET['search']));
      if ($_GET['mode'] == '2') // mode 2 is image search
          die(search_images($_GET['search']));
      else
          die(search_posts($_GET['search']));
  } else
      die('No results found.');
  
  
  // get the content excerpt
  function get_excerpt($text, $length = 25)
  {
      if (!$length)
          return $text;
      
      $text = strip_tags($text);
      $words = explode(' ', $text, $length + 1);
      if (count($words) > $length) {
          array_pop($words);
          array_push($words, '...');
          $text = implode(' ', $words);
      }
      return $text;
  }
  
  // search posts
  function search_posts($search)
  {
      global $wpdb, $WPInsights;
      
      $options=$WPInsights->get_options();
      $limit=$options['post_results'];
      
      // create query
      $search = $wpdb->escape($search);
      $posts = $wpdb->get_results("SELECT ID, post_title, post_content, post_type FROM $wpdb->posts WHERE post_status = 'publish' AND (post_title LIKE '%$search%' OR post_content LIKE '%$search%') ORDER BY post_title LIMIT 0,".$limit);
      
      // 
      if ($posts)
          foreach ($posts as $post) {
              // display every post link and excerpt
              $output .= '
        <p>
        <a style="text-decoration:none;" href="'. get_permalink($post->ID) . '" target="_blank">
        <strong>' . $post->post_title . '</strong>
        </a> <a href="'.$post->post_type.'.php?action=edit&post='.$post->ID.'"  ><img title="Edit '.$post->post_type.'"  src="'.$WPInsights->plugin_url.'/img/edit.png" /></a>
         <img title="Insert link to selection" style="cursor:pointer;" onclick="insert_link(\''. get_permalink($post->ID) .'\');" src="'.$WPInsights->plugin_url.'/img/link.png" />
        
        <p>
        ' . get_excerpt($post->post_content, 50) . '</p></p>';
          } else
          $output .= 'Nothing found matching "' . stripslashes($search) . '"';
      
      return $output;
  }
  
  function search_images($keyword)
  {
  		global $WPInsights;
      
      $options=$WPInsights->get_options();
      
      // search by tags  
      if ($options['image_tags'])      	
      	$tag_images = search_flickr($_GET['search'], 'tags', $options['image_results'], $options['image_nonc']);
      
      // search by text
      if ($options['image_text'])    
      	$text_images = search_flickr($_GET['search'], 'text', $options['image_results'], $options['image_nonc']);
      
      // if any results
      if ($tag_images || $text_images) {
          // output image size selection box
          $output = '
    <br /><select id="img_size">
    <option value="_s">Thumbnail (75px)</option>
    <option value="_t">Small (100px)</option>
    <option value="_m" selected="selected">Normal (240px)</option>
    <option value="">Medium (500px)</option>
    <option value="_b">Large (1024px)</option>
    </select> To add an image select a size and click on image.
    <br />';
   
            
          // output image
          if ($tag_images)
              $output .= $tag_images;
          
          if ($text_images)
              $output .= $text_images;
      
              
      } else
          $output = 'No images matched "' . $keyword . '"';
      
      
      
      
      return $output;
  }
  
  function search_flickr($keyword, $mode = 'tags', $count = 16, $nonc = 0)
  {
  		
  		if ($nonc)
  			$license="4,6,7";
  		else
  			$license="2,3,4,6,7";  			
  			
      // prepare Flickr query
      $params = array('api_key' => '72c75157d9ef89547c5a7b85748106e4', 'method' => 'flickr.photos.search', 'format' => 'php_serial', 'tag_mode' => 'any', 'per_page' => $count, 'sort' => 'interestingness-desc', $mode => $keyword, 'license' => $license);
      
      $encoded_params = array();
      foreach ($params as $k => $v) {
          // encode parameters    
          $encoded_params[] = urlencode($k) . '=' . urlencode($v);
      }
      
      // call the Flickr API  
      $url = "http://api.flickr.com/services/rest/?" . implode('&', $encoded_params);
      
    
      $rsp = wp_remote_fopen($url);
     
      
      // decode the response
      $rsp_obj = unserialize($rsp);
      
      // if we have photos
      if ($rsp_obj && $rsp_obj['photos']['total'] > 0) {
          foreach ($rsp_obj['photos']['photo'] as $photo) {
              // link to photo page
              $link = 'http://www.flickr.com/photos/' . $photo['owner'] . '/' . $photo['id'];
              
              // img src link
              $src = 'http://farm' . $photo['farm'] . '.static.flickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'];
              
              // create output      
              $output .= '<img hspace="2" vspace="2" src="' . $src . '_s.jpg" title="' . $photo['title'] . '" onclick="insert_image(\'' . $link . '\', \'' . $src . '\', \'' . str_replace("'", "&acute;", $photo['title']) . '\');" />';
              
            
          }
      }
      
      
      
      
      return $output;
  }

 

  function search_videos($keyword)
  {
  		global $WPInsights;
  		
  		$options=$WPInsights->get_options();
 			
  		
  		$url='http://gdata.youtube.com/feeds/api/videos?vq='.urlencode($keyword).'&format=1&max-results='.$options['video_results'];	
  		$rsp = wp_remote_fopen($url);
  		
  		

		$xml_parser = xml_parser_create();
	  xml_parse_into_struct($xml_parser, $rsp, $vals, $index); 
		xml_parser_free($xml_parser);

			//print_r($vals);
			//print_r($index);
			
			
	
	
		$width=425;	
		$height= 344;	
		$disp_rel=0;
		
		$out='';		
		$count=0;
			$preview='
			<div style="padding:10px 0px; display:none;" id="insights-youtube-holder">
				<div style="float:right;width:220px;" id="insights-youtube-data">
					
				</div>
				<div style="width:430px;height:340px;margin-right:230px;" id="insights-youtube-preview">
				
				</div>
				
			</div>';
		foreach ($index['MEDIA:PLAYER'] as $id) {
			preg_match("/http:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $vals[$id]['attributes']['URL'], $matches); 
			$file=$matches[3];			
			$title=$vals[$index['MEDIA:TITLE'][$count]]['value']; 
			$desc=$vals[$index['MEDIA:DESCRIPTION'][$count]]['value'];
			$views=$vals[$index['YT:STATISTICS'][$count]]['attributes']['VIEWCOUNT'];			
			$rating=$vals[$index['GD:RATING'][$count]]['attributes']['AVERAGE'];			
									
			$out.= '<img style="cursor:pointer" src="http://img.youtube.com/vi/'.$file.'/default.jpg" width="130" height="97" hspace="2" vspace="2" border="0" title="'.str_replace('"', "", $title).'" onclick="show_video(\'' . $file . '\', \'' . str_replace("'", "&acute;", $title) . '\', \'' . str_replace("'", "&acute;", $title) . '\', \'' . $views . '\',\'' . $rating . '\');">';									
			$count++;
		}
		
		if ($out)
		{		
			return $preview.'<div style="clear:both">'.$out.'</div>';
		}
			else return 'No videos found';
		
  }
  
  
  function search_wiki($keyword)
  {
  		global $WPInsights;
  		
 			$options=$WPInsights->get_options();
 			
			 			
  		$url='http://en.wikipedia.org/w/api.php?action=query&list=search&srwhat=text&srlimit='.$options['wiki_results'].'&format=php&srsearch='.urlencode($keyword);
  		
  		$rsp = wp_remote_fopen($url);
      
      // decode the response
      $rsp_obj = unserialize($rsp);
      
      
       if ($rsp_obj) {
         foreach($rsp_obj['query']['search'] as $item)
              // display every post link and excerpt
              $output .= '
        <p>
        <a onclick="insert_link(\'http://en.wikipedia.org/wiki/'.str_replace(" ","_", $item['title']) . '\')" style="cursor:pointer;"  >
        <strong>' . $item['title'] . '</strong>
        </a></p>';
         } else
          $output .= 'Nothing found matching "' . stripslashes($keyword) . '"';
      
      //print_r($rsp_obj);
      return $output;
  }
    

?>
