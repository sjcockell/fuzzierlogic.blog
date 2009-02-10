function friendFeedTasks(id) {
	this.id = id;
	this.toggleDiscussions = function (e) { 
		Event.stop(e);

		$('friendfeeddiscussions_' + this.id).toggle(); 
		$('ff_togglecommentslink_' + this.id).update((!$('friendfeeddiscussions_' + this.id).visible() ? 'show' : 'hide'));  
	}

	this.logout = function (e) {
		Event.stop(e);
		var id = this.id;
		$('ff_loadingimage_' + id).show();
		
		var form = $('ff_commentsform_' + id);
		
		body = Form.serialize(form, true);
		body.ac = 'logout';
		Form.disable(form);
		
		new Ajax.Request(friendFeedServicePath + "friendfeed_ajax.php", {
			method : 'get',
			parameters : body,
			postBody : body,
			onSuccess : function(e) { 
				form['ff_username'].clear();
				form['ff_apikey'].clear();
				$('ff_authentry_' + id).show();
				$('ff_authsaved_' + id).hide();
				$('ff_likeentry_' + id).show();
			},
			onFailure : function(e) {
				alert("Sorry, an error occured: (" + e.status + ") " + e.statusText + "\n" + e.responseText);
			},
			onComplete : function() { 
				Form.enable(form);
				$('ff_loadingimage_' + id).hide();
			}
		});
	}
	
	this.addComment = function (e) {
		Event.stop(e);
		
		if ($F('ff_newcomment_' + this.id)) {
			this.sendAjaxRequest('friendfeedcommentslist', 'comment');
		}
	}
	
	this.addLike = function (e) { 
		Event.stop(e);
		this.sendAjaxRequest('friendfeedlikeslist', 'like');
	}
	
	this.sendAjaxRequest = function (updateDivId, action) {
		var form = $('ff_commentsform_' + this.id);
		var id = this.id;
		$('ff_loadingimage_' + id).show();

		if ((!$F('ff_username_' + id) || !$F('ff_apikey_' + id)) && !$('ff_authsaved_' + id).visible()) {
			alert('Please enter your FriendFeed username (nickname) and API Key.');
			form['ff_username'].focus();
		} else {		
			body = Form.serialize(form, true);
			body.ac = action;
			Form.disable(form);

			new Ajax.Request(friendFeedServicePath + "friendfeed_ajax.php", {
				method : 'get',
				parameters : body,
				postBody : body,
				onSuccess : function(resp) { 
					$(updateDivId + '_' + id).update(resp.responseText); 
					if (form['ff_remember'].checked && $('ff_authentry_' + id).visible()) {
						$('ff_usernamedisplay_' + id).update($F('ff_username_' + id));
						$('ff_authentry_' + id).hide();
						$('ff_authsaved_' + id).show();							
					}
					form['ff_newcomment'].clear();
				},
				onFailure : function(e) { 
					if (e.status == 401) {
						alert("Sorry, the username & API key don't match");
						form['ff_username'].focus();
					} else {
						alert("Sorry, an error occured: (" + e.status + ") " + e.statusText + "\n" + e.responseText);
						form['ff_newcomment'].focus();
					}
				},
				onComplete : function() { 
					Form.enable(form); 
					 $('ff_loadingimage_' + id).hide();
				}
			 });
		}				 
	}
}