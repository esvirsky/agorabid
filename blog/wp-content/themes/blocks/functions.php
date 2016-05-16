<?php

/** blocks options */
class BlocksOptions {
	function getOptions() {
		$options = get_option('blocks_options');
		if (!is_array($options)) {
			$options['description'] = '';
			$options['keywords'] = '';
			$options['sidebar'] = 1;
			$options['sidebar_position'] = 'right';
			$options['left_sidebar_width'] = '';
			$options['google_cse'] = false;
			$options['google_cse_cx'] = '';
			$options['menu_type'] = 'pages';
			$options['notice'] = false;
			$options['notice_content'] = '';
			$options['notice_color'] = 2;
			$options['categories'] = false;
			$options['tags'] = true;
			$options['feed'] = false;
			$options['feed_url'] = '';
			$options['feed_readers'] = true;
			update_option('blocks_options', $options);
		}
		return $options;
	}

//GsL98DGtpo0W

	function add() {
		if(isset($_POST['blocks_save'])) {
			$options = BlocksOptions::getOptions();

			// meta
			$options['description'] = stripslashes($_POST['description']);
			$options['keywords'] = stripslashes($_POST['keywords']);

			// style
			$options['sidebar'] = $_POST['sidebar'];
			$options['sidebar_position'] = $_POST['sidebar_position'];
			$options['left_sidebar_width'] = $_POST['left_sidebar_width'];
			if ($options['left_sidebar_width'] != '' && $options['left_sidebar_width'] < 64) {
				$options['left_sidebar_width'] = 64;
			} else if ($options['left_sidebar_width'] != '' && $options['left_sidebar_width'] > 244) {
				$options['left_sidebar_width'] = 244;
			}

			// google custom search engine
			if ($_POST['google_cse']) {
				$options['google_cse'] = (bool)true;
			} else {
				$options['google_cse'] = (bool)false;
			}
			$options['google_cse_cx'] = stripslashes($_POST['google_cse_cx']);

			// menu
			$options['menu_type'] = stripslashes($_POST['menu_type']);

			// notice
			if ($_POST['notice']) {
				$options['notice'] = (bool)true;
			} else {
				$options['notice'] = (bool)false;
			}
			$options['notice_color'] = $_POST['notice_color'];
			$options['notice_content'] = stripslashes($_POST['notice_content']);

			// categories & tags
			if ($_POST['categories']) {
				$options['categories'] = (bool)true;
			} else {
				$options['categories'] = (bool)false;
			}
			if (!$_POST['tags']) {
				$options['tags'] = (bool)false;
			} else {
				$options['tags'] = (bool)true;
			}

			// feed
			if ($_POST['feed']) {
				$options['feed'] = (bool)true;
			} else {
				$options['feed'] = (bool)false;
			}
			$options['feed_url'] = stripslashes($_POST['feed_url']);
			if (!$_POST['feed_readers']) {
				$options['feed_readers'] = (bool)false;
			} else {
				$options['feed_readers'] = (bool)true;
			}


			update_option('blocks_options', $options);

		} else {
			BlocksOptions::getOptions();
		}

		add_theme_page(__('Current Theme Options', 'blocks'), __('Current Theme Options', 'blocks'), 'edit_themes', basename(__FILE__), array('BlocksOptions', 'display'));
	}

	function display() {
		$options = BlocksOptions::getOptions();
?>

<form action="#" method="post" enctype="multipart/form-data" name="blocks_form" id="blocks_form">
	<div class="wrap">
		<h2><?php _e('Current Theme Options', 'blocks'); ?></h2>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Meta', 'blocks'); ?></th>
					<td>
						<?php _e('Description:', 'blocks'); ?>
						<br/>
						<input type="text" name="description" id="description" class="code" style="width:98%;" value="<?php echo($options['description']); ?>">
						<br/>
						<?php _e('Keywords:', 'blocks'); ?> <small><?php _e('( Separate keywords with commas )', 'blocks'); ?></small>
						<br/>
						<input type="text" name="keywords" id="keywords" class="code" style="width:98%;" value="<?php echo($options['keywords']); ?>">
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Sidebar', 'blocks'); ?></th>
					<td>
						<select name="sidebar" size="1">
							<option value="1" <?php if($options['sidebar'] != 2) echo ' selected '; ?>><?php _e('Single', 'blocks'); ?></option>
							<option value="2" <?php if($options['sidebar'] == 2) echo ' selected '; ?>><?php _e('Double', 'blocks'); ?></option>
						</select>
						 <?php _e('sidebar(s).', 'blocks'); ?>
						<br/>
						<select name="sidebar_position" size="1">
							<option value="left" <?php if($options['sidebar_position'] == left) echo ' selected '; ?>><?php _e('Left', 'blocks'); ?></option>
							<option value="right" <?php if($options['sidebar_position'] != left) echo ' selected '; ?>><?php _e('Right', 'blocks'); ?></option>
						</select>
						 <?php _e('side of page.', 'blocks'); ?>
						<br/>
						<input name="left_sidebar_width" type="input" class="code" size="3" value="<?php echo($options['left_sidebar_width']) ?>" />
						<?php _e('px. Width of left-sidebar, between 64 to 244, default 124.', 'blocks'); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Search', 'blocks'); ?></th>
					<td>
						<label>
							<input name="google_cse" type="checkbox" value="checkbox" <?php if($options['google_cse']) echo "checked='checked'"; ?> />
							 <?php _e('Using google custom search engine.', 'blocks'); ?>
						</label>
						<br/>
						<?php _e('CX:', 'blocks'); ?>
						 <input type="text" name="google_cse_cx" id="google_cse_cx" class="code" size="40" value="<?php echo($options['google_cse_cx']); ?>">
						<br/>
						<?php _e('Find <code>name="cx"</code> in the <strong>Search box code</strong> of <a href="http://www.google.com/coop/cse/">Google Custom Search Engine</a>, and type the <code>value</code> here.<br/>For example: <code>014782006753236413342:1ltfrybsbz4</code>', 'blocks'); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Menubar', 'blocks'); ?></th>
					<td>
						<label style="margin-right:20px;">
							<input name="menu_type" type="radio" value="pages" <?php if($options['menu_type'] != 'categories') echo "checked='checked'"; ?> />
							 <?php _e('Show pages as menu.', 'blocks'); ?>
						</label>
						<label>
							<input name="menu_type" type="radio" value="categories" <?php if($options['menu_type'] == 'categories') echo "checked='checked'"; ?> />
							 <?php _e('Show categories as menu.', 'blocks'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<?php _e('Notice', 'blocks'); ?>
						<br/>
						<small style="font-weight:normal;"><?php _e('HTML enabled', 'blocks'); ?></small>
					</th>
					<td>
						<div style="width:98%;">
							<div style="float:left;">
								<label>
									<input name="notice" type="checkbox" value="checkbox" <?php if($options['notice']) echo "checked='checked'"; ?> />
									 <?php _e('Show notice.', 'blocks'); ?>
								</label>
							</div>
							<div style="float:right;">
								<?php _e('Color: ', 'blocks'); ?>
								<select name="notice_color" size="1">
									<option value="1" <?php if($options['notice_color'] == 1) echo ' selected '; ?>><?php _e('Blue', 'blocks'); ?></option>
									<option value="2" <?php if($options['notice_color'] != 1 && $options['notice_color'] != 3) echo ' selected '; ?>><?php _e('Green', 'blocks'); ?></option>
									<option value="3" <?php if($options['notice_color'] == 3) echo ' selected '; ?>><?php _e('Red', 'blocks'); ?></option>
								</select>
							</div>
							<div style="clear:both;"></div>
						</div>
						<label>
							<textarea name="notice_content" cols="50" rows="10" id="notice_content" class="code" style="width:98%;font-size:12px;"><?php echo($options['notice_content']); ?></textarea>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Categories & Tags', 'blocks'); ?></th>
					<td>
						<label style="margin-right:20px;">
							<input name="categories" type="checkbox" value="checkbox" <?php if($options['categories']) echo "checked='checked'"; ?> />
							 <?php _e('Show categories on posts.', 'blocks'); ?>
						</label>
						<label>
							<input name="tags" type="checkbox" value="checkbox" <?php if($options['tags']) echo "checked='checked'"; ?> />
							 <?php _e('Show tags on posts.', 'blocks'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Feed', 'blocks'); ?></th>
					<td>
						<label>
							<input name="feed" type="checkbox" value="checkbox" <?php if($options['feed']) echo "checked='checked'"; ?> />
							 <?php _e('Using custom feed.', 'blocks'); ?>
						</label>
						 <?php _e('Feed URL:', 'blocks'); ?>
						 <input type="text" name="feed_url" id="feed_url" class="code" size="40" value="<?php echo($options['feed_url']); ?>">
						<br/>
						<label>
							<input name="feed_readers" type="checkbox" value="checkbox" <?php if($options['feed_readers']) echo "checked='checked'"; ?> />
							 <?php _e('Show the feed reader list when mouse over on feed button.', 'blocks'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input class="button-primary" type="submit" name="blocks_save" value="<?php _e('Save Changes', 'blocks'); ?>" />
		</p>
	</div>

</form>

<!-- donation -->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<div class="wrap" style="background:#E3E3E3; margin-bottom:1em;">

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Donation</th>
					<td>
						If you find my work useful and you want to encourage the development of more free resources, you can do it by donating...
						<br />
						<input type="hidden" name="cmd" value="_s-xclick" />
						<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
						<input style="border:none;" type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" name="submit" alt="" />
						<img alt="" src="https://www.paypal.com/zh_XC/i/scr/pixel.gif" width="1" height="1" />
					</td>
				</tr>
			</tbody>
		</table>

	</div>
</form>

<?php
	}
}

// Register functions
add_action('admin_menu', array('BlocksOptions', 'add'));

/** l10n */
function theme_init(){
	load_theme_textdomain('blocks', get_template_directory() . '/languages');
}
add_action ('init', 'theme_init');

/** widgets */
$options = get_option('blocks_options');

if(function_exists('register_sidebar') && $options['sidebar'] == 1) {
	register_sidebar(array(
		'name' => 'Sidebar_single',
		'before_widget' => '<li class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

} else if(function_exists('register_sidebar') && $options['sidebar'] == 2) {
	register_sidebar(array(
			'name' => 'Sidebar_left',
			'before_widget' => '<li class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
	));
	register_sidebar(array(
			'name' => 'Sidebar_right',
			'before_widget' => '<li class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
	));
}

/** Comments */
if (function_exists('wp_list_comments')) {
	// comment count
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $commentcount ) {
		global $id;
		$_comments = get_comments('post_id=' . $id);
		$comments_by_type = &separate_comments($_comments);
		return count($comments_by_type['comment']);
	}

	// custom comments
	function custom_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		global $commentcount;
		if(!$commentcount) {
			$commentcount = 0;
		}
?>
		<li id="comment-<?php comment_ID() ?>" class="comment <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admincomment';} else { echo 'regularcomment';} ?>">
			<div class="header">
				<?php
					$author_class = '';
					// Support avatar for WordPress 2.5 or higher
					if (function_exists('get_avatar') && get_option('show_avatars')) {
						$author_class = 'with_avatar';
						echo get_avatar($comment, 24);
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
					<?php if (!get_option('thread_comments')) : ?>
						<a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('Reply', 'blocks'); ?></a> | 
					<?php else : ?>
						<?php comment_reply_link(array('depth' => $depth, 'max_depth'=> $args['max_depth'], 'reply_text' => __('Reply', 'blocks'), 'after' => ' | '));?>
					<?php endif; ?>
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
	}
}
?>
