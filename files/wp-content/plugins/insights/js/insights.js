// Insights for WordPress plugin

function send_wp_editor(html) {
    var win = window.dialogArguments || opener || parent || top;
    win.send_to_editor(html);

    // alternatively
    // tinyMCE.execCommand("mceInsertContent", false, html);
}

function insert_link(html_link) {
    if ((typeof tinyMCE != "undefined") && (edt = tinyMCE.getInstanceById('content')) && !edt.isHidden()) {
        var sel = edt.selection.getSel();
        //sel.toString()
        if (sel) {
            var link = '<a href="' + html_link + '" title="' + sel + '">' + sel + '</a>';

            send_wp_editor(link);
        }
    }
    return false;
}

function insert_image(link, src, title) {
    var size = document.getElementById('img_size').value;
    var img = '<a href="' + link + '"><img src="' + src + size + '.jpg" alt="' + title + '" title="' + title + '" hspace="5" border="0" /></a>';

    send_wp_editor(img);
}


var videoid = 0;

function insert_video() {
    var video = '<object type="application/x-shockwave-flash" width="425" height="344" data="http://www.youtube.com/v/' + videoid + '&amp;rel=0&amp;fs=1"><param name="movie" value="http://www.youtube.com/v/' + videoid + '&amp;rel=0&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="wmode" value="transparent" /></object>';

    send_wp_editor(video);
}

function insert_map() {
    var maphtml = '<img src="' + updateImage() + '" alt="" />';

    send_wp_editor(maphtml);

}

function show_video(ytfile, yttitle, ytdesc, ytviews, ytrating) {
  
  videoid=ytfile;	
	var link='<span style="padding: 2px"><object type="application/x-shockwave-flash" width="425" height="344" data="http://www.youtube.com/v/'+ytfile+'&amp;rel=0&amp;fs=1"><param name="movie" value="http://www.youtube.com/v/'+ytfile+'&amp;rel=0&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="wmode" value="transparent" /></object></span>';
  var data='<h4>'+yttitle+'</h4><p><a href="http://www.youtube.com/watch?v='+ytfile+'">link</a></p><p>'+ytdesc+'</p><p><strong>Views:</strong> '+ytviews+'</p><p><strong>Rating: </strong>'+ytrating+'</p>';
  var button='<br /><p><input class="button" type="button" value="Add Video" onclick="insert_video();" ></p><p>(you may need to go from Visual to HTML mode and back to see the video object)</p>';
	
	jQuery('#insights-youtube-preview').html(link);
	jQuery('#insights-youtube-data').html(data+button);
	jQuery('#insights-youtube-holder').fadeIn();
}


// setup everything when document is ready
jQuery(document).ready(function($) {

    // initialize the variables
    var search_timeout = undefined;
    var last_mode = undefined;
    var last_search = undefined;

   	function show_results(output, mode)
   	{   		
   		 var curr_mode = $("input[name='insights-radio']:checked").val();
   		 if (mode==curr_mode)
   		 	  $('#insights-results').html(output);
   		 else
   		 	$('#insights-results').html('');
   	}

    function submit_me() {

        // check if the search string is empty
        if ($('#insights-search').val().length == 0) {
            $('#insights-results').html('');
            return;
        }

        // get the search phrase
        var phrase = $('#insights-search').val();

        // get active radio checkbox
        var mode = $("input[name='insights-radio']:checked").val();

       
        if (mode == 5) { // maps
            $('#insights-results').html('');
            $('#insights-map-all').fadeIn(600);

            if (!map) init_map();
            else showAddress();
            	
            last_mode = mode;
        		last_search = phrase;      	
        
            return;
        } else {
            $('#insights-map-all').fadeOut(500);
        }
        
        if ((jQuery.trim(phrase) == last_search) && last_mode == mode) {
            return;
        }
 				last_mode = mode;
        last_search = phrase;      
       
        $('#insights-results').html('<img src="' + InsightsSettings.insights_url + '/img/loading.gif" />');
                  
         if (mode==4) // wikipedia
       	{
       		
       		$.getJSON('http://en.wikipedia.org/w/api.php?action=query&list=search&srwhat=text&srlimit=10&srsearch='+escape(phrase)+'&format=json&callback=?',
		        function(data){
		        	var output='';
		        	var wikipediaUrl = "http://en.wikipedia.org/wiki/";
		        	if (!data.query.search.length)
		        		output='No results matching "'+phrase+'".';
		          else
		          $.each(data.query.search, function(i,item){    
		          	output = output+'<p><a  target="_blank" style="text-decoration:none;" href="'+ wikipediaUrl + item.title.replace(/ /g, "_")+'" ><strong>'+item.title+ '</strong></a> <img title="Insert link to selection" style="cursor:pointer;" onclick="insert_link(\''+wikipediaUrl + item.title.replace(/ /g, "_")+'\');" src="'+InsightsSettings.insights_url+'/img/link.png" /></p>';                  
		          });				
		          show_results(output, mode);
		          

       			 });
        	return;
       	} 
        

        if (mode==6)  // google
        {   
		       $.getJSON("http://ajax.googleapis.com/ajax/services/search/web?q="+escape(phrase)+"&v=1.0&rsz=large&callback=?",
		        function(data){
		        	var output='';
		        	if (!data.responseData.results.length)
		        		output='No results matching "'+phrase+'".';
		          else
		          $.each(data.responseData.results, function(i,item){           		         
		           output=output+'<p><a  target="_blank" style="text-decoration:none;" href="'+item.url+'"><strong>'+ item.titleNoFormatting+'</strong></a> <img style="cursor:pointer;" title="Insert link to selection" onclick="insert_link(\''+item.url+'\');" src="'+InsightsSettings.insights_url+'/img/link.png" /><p>'+item.content+'</p></p>';
		          });
		
		          show_results(output, mode);
		        });

        	return;
        }
        
        if (mode==7)  // news
        {   
		       $.getJSON("http://ajax.googleapis.com/ajax/services/search/news?q="+escape(phrase)+"&v=1.0&scoring=d&rsz=large&callback=?",
		        function(data){
		        	var output='';
		        	if (!data.responseData.results.length)
		        		output='No results matching "'+phrase+'".';
		          else
		          $.each(data.responseData.results, function(i,item){           
		           output=output+'<p><a  target="_blank" style="text-decoration:none;" href="'+item.unescapedUrl+'"><strong>'+ item.titleNoFormatting+'</strong></a> <img style="cursor:pointer;" title="Insert link to selection" onclick="insert_link(\''+item.url+'\');" src="'+InsightsSettings.insights_url+'/img/link.png" /><br />'+item.publisher+', '+item.location+' on '+item.publishedDate +'<p>'+item.content+'</p></p>';
		           
		          });
		
		         show_results(output, mode);
		        });

        	return;
        }
        
				
				if (mode==10)  // blogs
        {   
		       $.getJSON("http://ajax.googleapis.com/ajax/services/search/blogs?q="+escape(phrase)+"&v=1.0&scoring=d&rsz=large&callback=?",
		        function(data){
		        	var output='';
		        	if (!data.responseData.results.length)
		        		output='No results matching "'+phrase+'".';
		          else
		          $.each(data.responseData.results, function(i,item){           		          
		           output=output+'<p><a  target="_blank" style="text-decoration:none;" href="'+item.postUrl+'"><strong>'+ item.titleNoFormatting+'</strong></a> <img style="cursor:pointer;" title="Insert link to selection" onclick="insert_link(\''+item.postUrl+'\');" src="'+InsightsSettings.insights_url+'/img/link.png" /><br />'+item.blogUrl+'<p>'+item.content+'</p></p>';		          
		          });
		           
		
		          show_results(output, mode);
		        });

        	return;
        }

	    
        if (mode==11)  // books
        {           	
					$.getJSON("http://ajax.googleapis.com/ajax/services/search/books?q="+escape(phrase)+"&v=1.0&as_brr=1&rsz=large&callback=?",		      
		        function(data){
		        	var output='';
		        	if (!data.responseData.results.length)
		        		output='No results matching "'+phrase+'".';
		          else
		          $.each(data.responseData.results, function(i,item){           		          		          		
		          		output=output+'<p><a target="_blank" style="text-decoration:none;" href="'+item.unescapedUrl+'"><strong>'+ item.titleNoFormatting+'</strong></a> <img style="cursor:pointer;" title="Insert link to selection" onclick="insert_link(\''+item.unescapedUrl+'\');" src="'+InsightsSettings.insights_url+'/img/link.png" /><p>'+ item.authors+', published '+item.publishedYear+', '+item.pageCount+' pages </p></p>';		          		          		
		          });
		
		          show_results(output, mode);
		        });

        	return;
        }

        // create the query
        var query = InsightsSettings.insights_url + '/insights-ajax.php?search=' + escape(phrase) + '&mode=' + mode;

        var cached = $.jCache.getItem(query);
        
        if (cached)
        		show_results(cached, mode);
        else
       	{		
	        var apiParams = {
						search: phrase,
						mode: mode,
						_ajax_nonce:InsightsSettings.nonce			
					};
	     
	       $.ajax({
						type: "GET",
						url: InsightsSettings.insights_url + "/insights-ajax.php",
						data: apiParams,
						datatype: "string",
						error: function() {
							$('#insights-results').html('Can not retrieve results');
						},
						success: function(searchReponse) {
					
	           show_results(searchReponse, mode);
	           $.jCache.setItem(query, searchReponse);
	   					
						}
					});	
				}

    }
    
    // measure time
  	// var startTime=new Date();
    // search button click event
    // var endTime=new Date();
	  // var responseTime=(endTime.getTime()-startTime.getTime());
    
    
    $('#insights-submit').click(function() {
        submit_me();
    });

    // check for ENTER or ArrowDown keys
    $('#insights-search').keypress(function(e) {
        if (e.keyCode == 13 || e.keyCode == 40) {
            submit_me();
            return false;
        }

    });

    if (parseInt(InsightsSettings.insights_interactive))

    // automatically refresh the view
    $('#insights-search').keyup(function(e) {
        if (search_timeout != undefined) {
            clearTimeout(search_timeout);
        }
        if ($('#insights-search').val().length < 3) {            
            return;
        }

        search_timeout = setTimeout(function() {
            search_timeout = undefined;
            submit_me();
        },
        700);
    });

});