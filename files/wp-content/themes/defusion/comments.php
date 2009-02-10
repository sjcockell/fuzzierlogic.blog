<?php /*Lines 77-79 for FF-Comments plugin!*/
// Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments"><?php _e('Enter your password to view comments.'); ?></p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->
<?php if ($comments) : ?>

<div class="box-left">

	<h4 id="comments"><?php comments_number(__('No Comments'), __('1 Comment'), __('% Comments')); ?> to <em><?php the_title(); ?></em></h4>

	<ol class="commentlist">
	
		<?php foreach ($comments as $comment) : ?>
		
			<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
			
			<div class="quote">
				<?php echo get_avatar( $comment, 64 ); ?>
				<?php comment_text() ?>
				<div class="clear"></div>
			</div>
			
				<?php if ($comment->comment_approved == '0') : ?>
				<em>Your comment is awaiting moderation.</em>
				<?php endif; ?>
				
			</li>
			
			<cite>
				<?php comment_author_link() ?> on <a href="#comment-<?php comment_ID() ?>" title="">
				<?php comment_date('F jS, Y') ?></a>
				<?php edit_comment_link('[edit comment]','&nbsp;',''); ?>
			</cite>

<?php
/* Changes every other comment to a different class */
$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
?>

<?php endforeach; /* end for each comment */ ?>

</ol><!-- end commentlist -->
	
</div><!-- end box-left -->

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
	
	<div class="box-left">
		
		<p><em><?php _e('No comments yet.'); ?></em></p>
		
	</div>
		
	 <?php endif; ?>

<?php endif; ?>

<?php /*this enclosure added for FF-Comments plugin*/
wp_ffcomments(); 
?>

<?php if ('open' == $post->comment_status) : ?>

<div class="box-left">

<h4 id="respond"><?php _e('Leave a comment'); ?></h4>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p class="alert"><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), get_option('siteurl')."/wp-login.php?redirect_to=".urlencode(get_permalink()));?></p>
</div><!-- end box-left -->
<?php else : ?>

<div id="commentform">
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

<?php if ( $user_ID ) : ?>

<p><?php printf(__('Logged in as %s.'), '<a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>"><?php _e('Log out &raquo;'); ?></a></p>

<?php else : ?>

<label for="author"><?php _e('Name'); ?> <?php if ($req) _e('(required)'); ?></label>
<input type="text" name="author" id="name" class="text" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />

<label for="email"><?php _e('Mail (will not be published)');?> <?php if ($req) _e('(required)'); ?></label>
<input type="text" name="email" id="email" class="text" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />

<label for="url"><?php _e('Website'); ?></label>
<input type="text" name="url" id="website" class="text" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />

<?php endif; ?>

<label for="message"><?php _e('Message'); ?></label>
<textarea name="comment" id="message" tabindex="4"></textarea>

<p><input name="submit" type="submit" class="button" tabindex="5" value="<?php echo attribute_escape(__('Submit')); ?>" /></p>
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

<?php do_action('comment_form', $post->ID); ?>

</form>

</div><!-- end commentform -->

</div><!-- end box-left -->

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
