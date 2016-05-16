<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/comment.js"></script>

<?php
	// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
		die ('Please do not load this page directly. Thanks!');
	}
?>

<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
	<div class="block">
		<div class="content small r"><?php _e('Enter your password to view comments.', 'blocks'); ?></div>
	</div>
<?php return; endif; ?>

<?php
	$commentcount = 0;
	$trackbacks = array(); 
?>

<?php if ( $comments ) : ?>
	<ul id="comments">
	<?php
		// Support comments for WordPress 2.7 or higher
		if (function_exists('wp_list_comments')) {
			wp_list_comments('type=comment&callback=custom_comments');
			$trackbacks = $comments_by_type['pings'];
		// Support comments for WordPress 2.6.3 or lower
		} else {
			foreach ($comments as $comment) {
				if($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') {
					array_push($trackbacks, $comment);
				} else {
	?>
		<li id="comment-<?php comment_ID() ?>" class="comment <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admincomment';} else { echo 'regularcomment';} ?>">
			<div class="header">
				<?php
					$author_class = '';
					// Support avatar for WordPress 2.5 or higher
					if (function_exists('get_avatar') && get_option('show_avatars')) {
						$author_class = 'with_avatar';
						echo get_avatar($comment, 24);
					// Support Gravatar for WordPress 2.3.3 or lower
					} else if (function_exists('gravatar')) {
						$author_class = 'with_avatar';
						echo '<img class="avatar" src="'; gravatar('G', 24); echo '" alt="avatar" />';
					}
				?>
				<div class="author <?php echo $author_class; ?>">

					<?php if (get_comment_author_url()) : ?>
						<a id="commentauthor-<?php comment_ID() ?>" href="<?php comment_author_url() ?>" rel="external nofollow">
					<?php else : ?>
						<span id="commentauthor-<?php comment_ID() ?>">
					<?php endif; ?>

						<?php comment_author(); ?>

					<?php if (get_comment_author_url()) : ?>
						</a>
					<?php else : ?>
						</span>
					<?php endif; ?>

				</div>
				<div class="items floatleft">
					<a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('Reply', 'blocks'); ?></a> | 
					<a href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'commentbody-<?php comment_ID() ?>', 'comment');"><?php _e('Quote', 'blocks'); ?></a>
					<?php edit_comment_link(__('Edit', 'blocks'), ' | ', ''); ?>
				</div>
				<div class="date floatright">
					<?php printf( __('%1$s at %2$s', 'blocks'), get_comment_date(__('M jS, Y', 'blocks')), get_comment_time(__('H:i', 'blocks')) ); ?>
					 | <a href="#comment-<?php comment_ID() ?>"><?php printf('#%1$s', ++$commentcount); ?></a>
				</div>
				<div class="fixed"></div>
			</div>
			<div class="body" id="commentbody-<?php comment_ID() ?>">
				<?php comment_text(); ?>
			</div>
			<div class="fixed"></div>
		</li>

	<?php
				} // if pingback/trackback
			} // foreach
		} // if WP2.7  or higher
	?>
	</ul>

<?php
	if (get_option('page_comments')) {
		$comment_pages = paginate_comments_links('echo=0');
		if ($comment_pages) {
?>
		<div id="commentnavi" class="block">
			<div class="content g">
				<span class="pages"><?php _e('Comment pages', 'blocks'); ?></span>
				<div id="commentpager"><?php echo $comment_pages; ?></div>
			</div>
		</div>
<?php
		}
	}
?>

	<?php if($trackbacks) : ?>
		<div id="trackbacks" class="block">
			<div class="header">
				<a id="trackbacks_show" href="javascript:void(0);" onclick="MGJS.setStyleDisplay('trackbacks_hide','');MGJS.setStyleDisplay('trackbacks_box','');MGJS.setStyleDisplay('trackbacks_show','none');"><?php _e('Show', 'blocks'); ?></a>
				<a id="trackbacks_hide" href="javascript:void(0);" onclick="MGJS.setStyleDisplay('trackbacks_show','');MGJS.setStyleDisplay('trackbacks_box','none');MGJS.setStyleDisplay('trackbacks_hide','none');"><?php _e('Hide', 'blocks'); ?></a>
				<span class="title"><?php echo count($trackbacks); _e(' trackbacks', 'blocks'); ?></span>
			</div>
			<div id= "trackbacks_box">
				<ul>
					<?php foreach ($trackbacks as $comment) : ?>
						<li>
							<small><?php comment_date('Y/m/d'); ?> - </small>
							<?php comment_author_link(); ?>
							<?php edit_comment_link(__('Edit', 'blocks'), ' <small>(', ')</small>'); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<script type="text/javascript">MGJS.setStyleDisplay('trackbacks_hide','none');MGJS.setStyleDisplay('trackbacks_box','none');</script>
	<?php endif; ?>

<?php elseif (comments_open()) : // If there are no comments yet. ?>
	<div class="block">
		<div class="content small g"><?php _e('No comments yet.', 'blocks'); ?></div>
	</div>

<?php endif; ?>

<?php if (!comments_open()) : // If comments are closed. ?>
	<div class="block">
		<div class="content small g"><?php _e('Comments are closed.', 'blocks'); ?></div>
	</div>

<?php elseif ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in. ?>
	<div class="block">
		<div class="content small g">
			<?php
				if (function_exists('wp_login_url')) {
					$login_link = wp_login_url();
				} else {
					$login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink());
				}
			?>
			<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'blocks'), $login_link); ?>
		</div>
	</div>

<?php else : ?>
	<div id="respond">
	<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
		<div class="body">
			<div class="header">
				<h3 class="title">
					<?php _e('Leave a comment', 'blocks'); ?>
				</h3>
				<?php if (function_exists('wp_list_comments')) : ?>
					<span class="cancel">
						<?php cancel_comment_reply_link(__('Cancel', 'blocks')) ?>
					</span>
				<?php endif; ?>
				<div class="fixed"></div>
			</div>
			<div class="notice">
				<strong>XHTML:</strong> <?php printf(__('You can use these tags: %s', 'blocks'), allowed_tags()); ?>
			</div>

			<div class="text"><textarea name="comment" id="comment" class="textarea" cols="64" rows="8" tabindex="4"></textarea></div>
			<div class="info">

				<div class="part">

				<?php if ( $user_ID ) : ?>
					<?php
						if (function_exists('wp_logout_url')) {
							$logout_link = wp_logout_url();
						} else {
							$logout_link = get_option('siteurl') . '/wp-login.php?action=logout';
						}
					?>
					<div class="row"><?php _e('Logged in as', 'blocks'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><strong><?php echo $user_identity; ?></strong></a>. <a href="<?php echo $logout_link; ?>" title="<?php _e('Log out of this account', 'blocks'); ?>"><?php _e('Logout &raquo;', 'blocks'); ?></a></div>

				<?php else : ?>
					<?php if ( $comment_author != "" ) : ?>
						<?php printf(__('Welcome back <strong>%s</strong>.', 'blocks'), $comment_author) ?>
						<span id="show_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','');MGJS.setStyleDisplay('show_author_info','none');MGJS.setStyleDisplay('hide_author_info','');"><?php _e('Change &raquo;', 'blocks'); ?></a></span>
						<span id="hide_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','none');MGJS.setStyleDisplay('show_author_info','');MGJS.setStyleDisplay('hide_author_info','none');"><?php _e('Close &raquo;', 'blocks'); ?></a></span>
					<?php endif; ?>

					<div id="author_info">
						<div><label for="author" class="small"><?php _e('Name', 'blocks'); ?> <?php if ($req) _e('*', 'blocks'); ?></label></div>
						<div><input type="text" class="textfield" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" /></div>
						<div><label for="email" class="small"><?php _e('E-Mail', 'blocks');?> <?php if ($req) _e('*', 'blocks'); ?> <?php _e('(will not be published)', 'blocks');?></label></div>
						<div><input type="text" class="textfield" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" /></div>
						<div><label for="url" class="small"><?php _e('Website', 'blocks'); ?></label></div>
						<div><input type="text" class="textfield" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" /></div>
					</div>

					<?php if ( $comment_author != "" ) : ?>
						<script type="text/javascript">MGJS.setStyleDisplay('hide_author_info','none');MGJS.setStyleDisplay('author_info','none');</script>
					<?php endif; ?>

				<?php endif; ?>
				</div>

				<?php if (function_exists('wp_list_comments')) : ?>
					<?php comment_id_fields(); ?>
				<?php endif; ?>

				<div class="part">
					<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Submit Comment', 'blocks'); ?>" />
					<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
				</div>
			</div>

			<div class="fixed"></div>
		</div>
		<?php do_action('comment_form', $post->ID); ?>
	</form>
	</div>

<?php endif; ?>
