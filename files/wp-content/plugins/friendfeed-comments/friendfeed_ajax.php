<?php
if (!function_exists('add_action'))
{
	@include_once($GLOBALS['HTTP_ENV_VARS']['DOCUMENT_ROOT'] . "/wp-config.php");

	if (!function_exists('add_action')) {
		include_once("../../../wp-config.php");
	}
}

if (isset($FriendFeedPostComments) && $_REQUEST) {
	$action = $_REQUEST['ac'];
	
	if ($_REQUEST['ffdebugflag']) {
		$FriendFeedPostComments->printr($_REQUEST);
	}
	
	//If the action is logout, just do that
	if ($action == 'logout') {
		$FriendFeedPostComments->remove_authentication();
		print 'true';
	} else {
		//Otherwise extract the username & apikey and process the new comment / like
		$username = $_REQUEST['ff_username'];
		$apikey = $_REQUEST['ff_apikey'];		
	
		//Try & get the username & apikey out of the db from the cookie hash value if no username is passed through
		if (!$username || !$apikey) {
			$auth = $FriendFeedPostComments->get_authentication();
			if ($auth) {
				$username = $auth[0];
				$apikey = $auth[1];
			}
		}
		
		$friendfeed = new FriendFeed($username, $apikey);
		
		if ($FriendFeedPostComments->is_debug()) {
			$FriendFeedPostComments->printr($friendfeed);
		}
		
		if ($action == 'comment') {
			$return_val = $friendfeed->add_comment($_REQUEST['ff_entryid'],stripslashes($_REQUEST['ff_newcomment']));
		} else {
			$return_val = $friendfeed->add_like($_REQUEST['ff_entryid']);
		}
		
		if ($FriendFeedPostComments->is_debug()) {
			$FriendFeedPostComments->printr($return_val);
		}
		if ($return_val) {
			//Store the username & apikey in a cookie if requested
			if ($_REQUEST['ff_remember']) {
				$FriendFeedPostComments->update_apikey($username, $apikey);
			}
			
			$FriendFeedPostComments->update_friendfeedcomments();
			$discussion = $FriendFeedPostComments->get_discussion_for_post($_REQUEST['ff_postid'], false);
			
			if ($discussion) {
				if ($action == 'comment') {
					print $FriendFeedPostComments->render_comments_list($discussion->comments);
				} else {
					print $FriendFeedPostComments->render_likes_list($discussion->likes);
				}
			}
		} else {
			header('HTTP/1.0 401 Unauthorized');
		}
	}
}
?>