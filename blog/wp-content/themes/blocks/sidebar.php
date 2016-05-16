<?php $options = get_option('blocks_options'); ?>

<!-- sidebar START -->
<div id="sidebar">

	<div class="sidebar">

		<!-- search box -->
		<div class="widget s">
			<?php if($options['google_cse'] && $options['google_cse_cx']) : ?>
				<form action="http://www.google.com/cse" method="get">
					<div id="searchbox">
						<input type="text" class="textfield" name="q" size="24" />
						<input type="hidden" name="cx" value="<?php echo $options['google_cse_cx']; ?>" />
						<input type="hidden" name="ie" value="UTF-8" />
						<div class="operation">
							<span class="floatleft"><?php _e('Search this blog', 'blocks'); ?></span>
							<input id="search_submit" class="button floatright" type="submit" value="<?php _e('Search', 'blocks'); ?>" />
							<div class="fixed"></div>
						</div>
					</div>
				</form>
			<?php else : ?>
				<form action="<?php bloginfo('home'); ?>/" id="search" method="get">
					<div id="searchbox">
						<input type="text" class="textfield" name="s" value="<?php echo wp_specialchars($s, 1); ?>" />
						<div class="operation">
							<span class="floatleft"><?php _e('Search this blog', 'blocks'); ?></span>
							<input id="search_submit" class="button floatright" type="submit" value="<?php _e('Search', 'blocks'); ?>" />
							<div class="fixed"></div>
						</div>
					</div>
				</form>
			<?php endif; ?>
		</div>

	</div>

	<!-- sidebar right -->
	<div id="sidebar_right" class="sidebar">
		<ul class="widgets">

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_single') ) : // single ?>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_right') ) : // right ?>

			<!-- recent posts -->
			<li id="pages" class="widget widget_pages">
				<h3>Recent Posts</h3>
				<ul>
					<?php $posts = get_posts('numberposts=5&orderby=post_date'); foreach($posts as $post) : setup_postdata($post); ?>
					<li>
						<?php if ($options['sidebar'] == 1) : ?>
							<small class="floatright sidedate"><?php the_time('y/m/d') ?></small>
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>"><?php the_title(); ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</li>

			<!-- recent comments -->
			<?php if( function_exists('wp_recentcomments') ) : ?>
				<li class="widget">
					<h3>Recent Comments</h3>
					<ul>
						<?php wp_recentcomments('length=13&pingback=false&post=false'); ?>
					</ul>
				</li>
			<?php endif; ?>

			<!-- tag cloud -->
			<li class="widget widget_tag_cloud">
				<h3>Tag Cloud</h3>
				<?php wp_tag_cloud('smallest=8&largest=16'); ?>
			</li>

<?php endif; // right ?>


<?php if ($options['sidebar'] >= 2) : ?>
		</ul>
	</div>

	<!-- sidebar left -->
	<div id="sidebar_left" class="sidebar">
		<ul class="widgets">
<?php endif; ?>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_left') ) : // left ?>

			<!-- categories -->
			<li class="widget widget_categories">
				<h3>Categories</h3>
				<ul>
					<?php wp_list_cats('sort_column=name&optioncount=1'); ?>
				</ul>
			</li>
<?php /*?>
			<!-- archives -->
			<li id="archives" class="widget widget_archive">
				<h3>Archives</h3>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<!-- blogroll -->
			<li class="widget widget_links">
				<h3>Blogroll</h3>
				<ul>
					<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
				</ul>
			</li>
<?php */ ?>

<div class="AdLink"><a href="/service_request/create?categoryId=49">Find a computer repair specialist today</a></div>

<?php endif; // left ?>
<?php endif; // single ?>

		</ul>
	</div>
</div>
<!-- sidebar END -->