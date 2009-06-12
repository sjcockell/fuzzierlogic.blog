<div class="wrap" style="max-width:950px !important;">
	<h2>Insights</h2>
				
	<div id="poststuff" style="margin-top:10px;">
		<div id="sideblock" style="float:right;width:220px;margin-left:10px;"> 
				 <h3>Information</h3>
				 <div id="dbx-content" style="text-decoration:none;">
				 	 <img src="<?php echo $imgpath ?>/home.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/wordpress-plugins/insights"> Insights Home</a><br />
			 <img src="<?php echo $imgpath ?>/rate.png"><a style="text-decoration:none;" href="http://wordpress.org/extend/plugins/insights/"> Rate this plugin</a><br />
			 <img src="<?php echo $imgpath ?>/help.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/forum"> Support and Help</a><br />			 
			 <br />
			 <a style="text-decoration:none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2567254"><img src="<?php echo $imgpath ?>/paypal.gif"></a>			 
			 <br /><br />
			 <img src="<?php echo $imgpath ?>/more.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/wordpress-plugins"> Cool WordPress Plugins</a><br />
			 <img src="<?php echo $imgpath ?>/twit.png"><a style="text-decoration:none;" href="http://twitter.com/vprelovac"> Follow updates on Twitter</a><br />			
			 <img src="<?php echo $imgpath ?>/idea.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/services"> Need a WordPress Expert?</a>
			 					 
		 		</div>
		 	</div>

	 <div id="mainblock" style="width:710px">
	 
		<div class="dbx-content">
		 	<form action="<?php echo $action_url ?>" method="post">
					<input type="hidden" name="submitted" value="1" /> 
					<?php wp_nonce_field('insights'); ?>
					
					<p>Enter the number of search results you would like to see for your searches.</p>
					<input type="text" name="post_results" size="15" value="<?php echo $post_results ?>"/><label for="post_results"> My blog</label><br/>					
					<br /> 				
					<input type="text" name="image_results" size="15" value="<?php echo $image_results ?>"/><label for="image_results"> Image</label><br/>
					<br /> 				
					<input type="text" name="video_results" size="15" value="<?php echo $video_results ?>"/><label for="video_results"> Video</label><br/>
					<br /> 				
					<input type="text" name="wiki_results" size="15" value="<?php echo $wiki_results ?>"/><label for="wiki_results"> Wikipedia</label><br/>					
					<br /> 				
					
					<input type="checkbox" name="image_tags"  <?php echo $image_tags ?> /><label for="image_tags"> Search Flickr images by tag</label>  <br />
					<input type="checkbox" name="image_text"  <?php echo $image_text ?> /><label for="image_text"> Search Flickr images by description</label>  <br />
					<input type="checkbox" name="image_nonc"  <?php echo $image_nonc ?> /><label for="image_nonc"> Search only non-commercial Flickr images</label>  <br />
					<input type="checkbox" name="interactive"  <?php echo $interactive ?> /><label for="interactive"> Show results as you type</label>  <br />
					
					<br />
					<h2>Google Maps</h2>								
					<br /> 				
					<input type="checkbox" name="gmaps"  <?php echo $gmaps ?> /><label for="gmaps"> Turn on Google Maps module</label>  <br />
					<br /> 				
					Enter your Google Maps API key. You can get it free <a href="http://code.google.com/apis/maps/signup.html">here</a>.<br/>
					<input type="text" name="maps_api" size="100" value="<?php echo $maps_api ?>"/><br />  					
					<br /> 				
																								
					<div class="submit"><input type="submit" name="Submit" value="Update" /></div>
			</form>
		</div>
				
	<br/><br/><h3>&nbsp;</h3>	
	 </div>

	</div>
	
<h5>WordPress plugin by <a href="http://www.prelovac.com/vladimir/">Vladimir Prelovac</a></h5>
</div>

