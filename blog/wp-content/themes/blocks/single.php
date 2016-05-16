<?php $options = get_option('blocks_options'); ?>
<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

	<div class="post">
		<h3 class="title"><?php the_title(); ?></h3>

		<div class="content">
			<?php the_content(); ?>
			<div class="fixed"></div>
		</div>

		<?php if(function_exists('wp23_related_posts')) : ?>
			<div id="related_posts">
				<div class="related_posts">
					<?php wp23_related_posts(); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="meta">
			<div class="alignleft floatleft">
				<div class="date"><? printf( __('%1$s at %2$s', 'blocks'), get_the_time(__('l, F jS, Y', 'blocks')), get_the_time(__('H:i', 'blocks')) ); ?></div>
				<?php if ($options['categories']) : ?>
					<div class="post_info"><?php _e('Categories: ', 'blocks'); the_category(', ') ?></div>
				<?php endif; ?>
				<?php if ($options['tags']) : ?>
					<div class="post_info"><?php _e('Tags: ', 'blocks'); the_tags('', ', ', ''); ?></div>
				<?php endif; ?>
			</div>
			<div class="alignright floatright">
				<div class="feed"><?php comments_rss_link(__('<abbr title="Really Simple Syndication">RSS</abbr> feed for comments on this post', 'blocks')); ?></div>
				<div>
					<a href="#respond"><?php _e('Leave a comment', 'blocks'); ?></a>
					<?php if(pings_open()) : ?>
					 | <a href="<?php trackback_url(); ?>" rel="trackback"><?php _e('Trackback', 'blocks'); ?></a>
					<?php endif; ?>
					<?php edit_post_link(__('Edit', 'blocks'), ' | ', ''); ?>
				</div>
			</div>
			<div class="fixed"></div>
		</div>
	</div>

	<div id="postnavi" class="block">
		<div class="content g">
			<span class="prev"><?php previous_post_link('&laquo; %link') ?></span>
			<span class="next"><?php next_post_link('%link &raquo;') ?></span>
			<div class="fixed"></div>
		</div>
	</div>

<?php else : ?>

	<div class="block">
		<div class="content small r">
			<?php _e('Sorry, no posts matched your criteria.', 'blocks'); ?>
		</div>
	</div>

<?php endif; ?>

<?php
	// Support comments for WordPress 2.7 or higher
	if (function_exists('wp_list_comments')) {
		comments_template('', true);
	} else {
		comments_template();
	}
?>

<?php get_footer(); ?>
