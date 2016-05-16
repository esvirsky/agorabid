<?php $options = get_option('blocks_options'); ?>
<?php get_header(); ?>

<?php if ($options['notice']) : ?>
	<div class="block">
		<div class="
		<?php if($options['notice_color'] == 1) {echo 'content';}
			else if($options['notice_color'] == 3){echo 'content r';}
			else{echo 'content g';}
		?>">
			<div id="notice_content"><?php echo($options['notice_content']); ?></div>
		</div>
	</div>
<?php endif; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="post">
		<h3 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>

		<div class="content">
			<?php the_excerpt(__('Read more...', 'blocks')); ?>
			<div class="fixed"></div>
		</div>

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
				<div><?php comments_popup_link(__('No comments', 'blocks'), __('1 comment', 'blocks'), __('% comments', 'blocks')); ?></div>
				<div><?php edit_post_link(__('Edit', 'blocks'), '', ''); ?></div>
			</div>
			<div class="fixed"></div>
		</div>
	</div>

<?php endwhile; ?>

	<div id="pagenavi" class="block">
		<?php if(function_exists('wp_pagenavi')) : ?>
				<?php wp_pagenavi() ?>
			<?php else : ?>
				<div class="content g">
					<span class="newer"><?php previous_posts_link(__('&laquo; Newer Entries', 'blocks')); ?></span>
					<span class="older"><?php next_posts_link(__('Older Entries &raquo;', 'blocks')); ?></span>
					<div class="fixed"></div>
				</div>
		<?php endif; ?>
	</div>

<?php else: ?>
	<div class="block">
		<div class="content small r">
			<?php _e('Sorry, no posts matched your criteria.', 'blocks'); ?>
		</div>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
