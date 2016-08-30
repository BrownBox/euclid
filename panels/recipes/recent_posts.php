<?php
$panel_meta = get_post_meta($panel->ID);
$small_count = $panel_meta['num_per_row_small'][0];
$medium_count = $panel_meta['num_per_row_medium'][0];
$large_count = $panel_meta['num_per_row_large'][0];

$args = array(
        'posts_per_page' => $panel_meta['num_items'],

);
$category = $panel_meta['post_category'][0];
if (!empty($category)) {
    $args['tax_query'] = array(
            array(
                    'taxonomy' => 'category',
                    'terms' => $category,
                    'field' => 'term_id',
            ),
    );
}
$recent_posts = get_posts($args);
?>
<div class="small-up-<?php echo $small_count; ?> medium-up-<?php echo $medium_count; ?> large-up-<?php echo $large_count; ?>">
<?php
foreach ($recent_posts as $recent_post) {
?>
	<div class="column">
	    <div class="image" style="background-image: url(<?php echo bb_get_featured_image_url('medium', $recent_post); ?>);"></div>
        <h3 class="title"><?php echo $recent_post->post_title; ?></h3>
        <p class="content"><?php echo apply_filters('the_content', bb_extract($recent_post->post_content)); ?></p>
        <a class="button" href="<?php echo get_the_permalink($recent_post); ?>">Learn more</a>
	</div>
<?php
}
?>
</div>