<?php
global $post;
$title = get_the_title();
$meta = get_post_meta($post->ID);
if (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $title = $term->name;
    $archive_page = get_page_by_path(get_post_type($post));
    $image = get_value_from_hierarchy('featured_image', $archive_page->ID);
    $meta = get_post_meta($archive_page->ID);
} elseif (is_home() && !is_front_page()) {
    $blog_page = get_option('page_for_posts', true);
    $title = get_the_title($blog_page);
    $image = get_value_from_hierarchy('featured_image', $blog_page);
    $meta = get_post_meta($blog_page);
} else {
    $image = get_value_from_hierarchy('featured_image');
}

$style = 'background-color: '.$meta['hero_bgcolour'][0].';';
if (!empty($image)) {
    $style .= ' background-image: url('.$image.'); background-position: '.$meta['hero_bgpos_x'][0].' '.$meta['hero_bgpos_y'][0].';';
?>
<div class="hero small-24 medium-24 large-24 column" style="<?php echo $style; ?>">
    <div class="row">
        <div class="small-24 medium-24 large-24 column">
   	        <h1><?php echo $title; ?></h1>
   	    </div>
    </div>
</div>
<?php
}
