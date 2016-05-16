<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
	$options = get_option('blocks_options');
	if (is_home()) {
		$home_menu = 'current_page_item';
	} else {
		$home_menu = 'page_item';
	}
	if($options['feed'] && $options['feed_url']) {
		if (substr(strtoupper($options['feed_url']), 0, 7) == 'HTTP://') {
			$feed = $options['feed_url'];
		} else {
			$feed = 'http://' . $options['feed_url'];
		}
	} else {
		$feed = get_bloginfo('rss2_url');
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<?php
		if (is_home()) { 
			$description = $options['description'];
			$keywords = $options['keywords'];
		} else if (is_single()) {
			$description =  $post->post_title;
			$keywords = "";
			$tags = wp_get_post_tags($post->ID);
			foreach ($tags as $tag ) {
				$keywords = $keywords . $tag->name . ", ";
			}
		} else if (is_category()) {
			$description = category_description();
		}
	?>
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<meta name="description" content="<?php echo $description; ?>" />

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<link rel="alternate" type="application/rss+xml" title="<?php _e('RSS 2.0 - all posts', 'blocks'); ?>" href="<?php echo $feed; ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php _e('RSS 2.0 - all comments', 'blocks'); ?>" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<!-- style START -->
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
	<?php if (strtoupper(get_locale()) == 'ZH_CN') : ?>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/zh_CN.css" type="text/css" media="screen" />
	<?php endif; ?>
	<?php if ($options['sidebar'] == 2) : ?>
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style_3col.css" type="text/css" media="screen" />
	<?php endif; ?>
	<?php if ($options['sidebar_position'] == 'left') : ?>
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/sidebar_left.css" type="text/css" media="screen" />
	<?php endif; ?>
	<!--[if IE 6]>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie6.css" type="text/css" media="screen" />
	<![endif]-->
	<?php
		if ($options['sidebar'] == 2 && $width = (int)$options['left_sidebar_width'] ) {
			$styles = '<style type="text/css">';
			$styles .= '#sidebar_left {width: ' . $width . 'px;}';
			$styles .= '#sidebar_right {width: ' . (308 - $width) . 'px;}';
			$styles .= '</style>';
			echo($styles);
		}
	?>
	<!-- style END -->

	<!-- script START -->
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/util.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/menu.js"></script>
	<!-- script END -->

	<?php if(is_singular()) wp_enqueue_script('comment-reply'); ?>
	<?php wp_head(); ?>
</head>

<body>

<!-- container START -->
<div id="container">

<script type='text/javascript' src='/js/third_party/jquery-latest.min.js'></script>
<script type='text/javascript' src='/js/global.js'></script>

<style>

/* HEADER */

#divIE6 { display: none; }
#divHeader { margin: 8px auto 10px auto;	width: 926px; height: 90px;	position: relative; }
#divHeader .UsernameSpan { font-size: 8pt; }
#divHeader #divLogo { position: relative; top: 36px; }
#divHeader #divMenu { position: absolute; top: 30px; right: 0px; }
#divHeader #divAccount { position: absolute; top: 9px; right: 10px; font-size: 9pt; }
#divHeader #divAccount a { color:#6a6a6a; text-decoration:none; }
#divHeader #divValidationErrors, #divTopErrorMessage { margin-top: 10px; font-size: 11pt; color: #cf0000; text-align: center; }
#footer { display: none; }

</style>

<div id="divHeader">
	<div id="divLogo"><a href="/"><img id="imgLogo" src="/images/logo.png" alt="Logo" /></a></div>
	
	<div id="divMenu">
		<a href="/"><img id="imgMenuHome" src="<?=$selected == "imgMenuHome" ? "/images/menu_home_selected.png" : "/images/menu_home.png"?>" alt="Menu Home" /></a
		><a href="/service_request/create"><img id="imgMenuNew" src="<?=$selected == "imgMenuNew" ? "/images/menu_new_selected.png" : "/images/menu_new.png"?>" alt="Menu New Request" /></a
		><a href="/service_request/search"><img id="imgMenuSearch" src="<?=$selected == "imgMenuSearch" ? "/images/menu_search_selected.png" : "/images/menu_search.png"?>" alt="Menu Search Requests" /></a>
		<input id="hdnHeaderMenuSelected" type="hidden" value="<?=$selected?>"/>
	</div>
</div>

<?php /* ?>
	<!-- top START -->
	<div id="top">
		<ul>
			<?php wp_register(); ?>
			<li class="s"><?php wp_loginout(); ?></li>
		</ul>
	</div>
	<div class="fixed"></div>
	<!-- top END -->

	<!-- header START -->
	<div id="header">
		<div class="content">
			<div id="title">
				<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
				<div id="tagline"><?php bloginfo('description'); ?></div>
			</div>
			<div class="fixed"></div>
		</div>
		<div class="meta">
			<ul id="menubar">
				<li class="<?php echo($home_menu); ?>"><a href="<?php echo get_settings('home'); ?>/"><?php _e('Home', 'blocks'); ?></a></li>
				<?php
					if($options['menu_type'] == 'categories') {
						wp_list_categories('depth=2&title_li=0&orderby=name&show_count=0');
					} else {
						wp_list_pages('depth=2&title_li=0&sort_column=menu_order');
					}
				?>
			</ul>
			<div id="subscribe" class="feed">
				<a title="<?php _e('Subscribe to this blog...', 'blocks'); ?>" class="feedlink" href="<?php echo $feed; ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr> feed', 'blocks'); ?></a>
				<?php if($options['feed_readers']) : ?>
					<ul>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('Youdao', 'blocks'); ?>"	href="http://reader.youdao.com/#url=<?php echo $feed; ?>">								<?php _e('Youdao', 'blocks'); ?></a></li>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('Xian Guo', 'blocks'); ?>"	href="http://www.xianguo.com/subscribe.php?url=<?php echo $feed; ?>">					<?php _e('Xian Guo', 'blocks'); ?></a></li>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('Zhua Xia', 'blocks'); ?>"	href="http://www.zhuaxia.com/add_channel.php?url=<?php echo $feed; ?>">					<?php _e('Zhua Xia', 'blocks'); ?></a></li>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('Google', 'blocks'); ?>"	href="http://fusion.google.com/add?feedurl=<?php echo $feed; ?>">						<?php _e('Google', 'blocks'); ?></a></li>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('My Yahoo!', 'blocks'); ?>"	href="http://add.my.yahoo.com/rss?url=<?php echo $feed; ?>">							<?php _e('My Yahoo!', 'blocks'); ?></a></li>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('newsgator', 'blocks'); ?>"	href="http://www.newsgator.com/ngs/subscriber/subfext.aspx?url=<?php echo $feed; ?>">	<?php _e('newsgator', 'blocks'); ?></a></li>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('Bloglines', 'blocks'); ?>"	href="http://www.bloglines.com/sub/<?php echo $feed; ?>">								<?php _e('Bloglines', 'blocks'); ?></a></li>
						<li><a title="<?php _e('Subscribe with ', 'blocks'); _e('iNezha', 'blocks'); ?>"	href="http://inezha.com/add?url=<?php echo $feed; ?>">									<?php _e('iNezha', 'blocks'); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>
			<?php if ( $user_ID ) : ?>
				<div id="newpost">
					<a title="Write a NEW post" class="greedlink" href="<?php echo get_settings('home'); ?>/wp-admin/post-new.php"><?php _e('NEW post', 'blocks'); ?></a>
				</div>
			<?php endif; ?>
			<span id="copyright">
				<?php
					global $wpdb;
					$post_datetimes = $wpdb->get_results("SELECT YEAR(min(post_date_gmt)) AS firstyear, YEAR(max(post_date_gmt)) AS lastyear FROM $wpdb->posts WHERE post_date_gmt > 1970");
					if ($post_datetimes) {
						$firstpost_year = $post_datetimes[0]->firstyear;
						$lastpost_year = $post_datetimes[0]->lastyear;

						$copyright = __('Copyright &copy; ', 'blocks') . $firstpost_year;
						if($firstpost_year != $lastpost_year) {
							$copyright .= '-'. $lastpost_year;
						}
						$copyright .= ' ';

						echo $copyright;
						bloginfo('name');
					}
				?>
			</span>
			<div class="fixed"></div>
		</div>
	</div>
	<!-- header END -->
<?php */ ?>
	<!-- content START -->
	<div id="content">

		<!-- main START -->
		<div id="main">