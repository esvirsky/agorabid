<?php get_header(); ?>

	<div class="post">

		<h3 class="title">
			<?php
				if (is_search()) {
					_e('Search Results', 'blocks');
				} else {
					_e('Archives', 'blocks');
				}
			?>
		</h3>
		
		<div class="content">
			<ul id="archive">

				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
						<li class="archive-post">
							<h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
							<div class="small"><? printf( __('%1$s at %2$s', 'blocks'), get_the_time(__('l, F jS, Y', 'blocks')), get_the_time(__('H:i', 'blocks')) ); ?> | <?php comments_popup_link(__('No comments', 'blocks'), __('1 comment', 'blocks'), __('% comments', 'blocks')); ?><?php edit_post_link(__('Edit', 'blocks'), ' | ', ''); ?></div>
							<div class="small"><?php _e('Categories: ', 'blocks'); the_category(', ') ?></div>
							<div class="small"><?php _e('Tags: ', 'blocks'); the_tags('', ', ', ''); ?></div>
						</li>
					<?php endwhile; ?>

				<?php else: ?>
					<li class="archive-post">
						<div class="small">
							<?php _e('Sorry, no posts matched your criteria.', 'blocks'); ?>
						</div>
					</li>

				<?php endif; ?>
			</ul>
		</div>

		<div class="meta">
			<div class="floatleft">

<?php
// If this is a search
if (is_search()) {
	printf( __('Keyword: &#8216;%1$s&#8217;', 'blocks'), wp_specialchars($s, 1) );
// If this is a category archive
} elseif (is_category()) {
	printf( __('Archive for the &#8216;%1$s&#8217; Category', 'blocks'), single_cat_title('', false) );
// If this is a tag archive
} elseif ( is_tag() ) {
	printf( __('Posts Tagged &#8216;%1$s&#8217;', 'blocks'), single_tag_title('', false) );
// If this is a daily archive
} elseif (is_day()) {
	printf( __('Archive for %1$s', 'blocks'), get_the_time(__('F jS, Y', 'blocks')) );
// If this is a monthly archive
} elseif (is_month()) {
	printf( __('Archive for %1$s', 'blocks'), get_the_time(__('F, Y', 'blocks')) );
// If this is a yearly archive
} elseif (is_year()) {
	printf( __('Archive for %1$s', 'blocks'), get_the_time(__('Y', 'blocks')) );
// If this is an author archive
} elseif (is_author()) {
	_e('Author Archive', 'blocks');
// If this is a paged archive
} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
	_e('Blog Archives', 'blocks');
}
?>

			</div>
			<div class="floatright">
				<?php edit_post_link(_('Edit'), '', ''); ?>
			</div>
			<div class="fixed"></div>
		</div>
	</div>

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

<?php get_footer(); ?>
