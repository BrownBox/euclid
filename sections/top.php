<?php
global $post;
$title = get_the_title();
if (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $title = $term->name;
    $archive_page = get_page_by_path(get_post_type($post));
    $image = get_value_from_hierarchy('featured_image', $archive_page->ID);
} else {
    $image = get_value_from_hierarchy('featured_image');
}

$style = 'background-color: '.get_post_meta($post->ID, 'hero_bgcolour', true).';';
if (!empty($image)) {
    $style .= ' background-image: url('.$image.'); background-position: '.get_post_meta($post->ID, 'hero_bgpos_x', true).' '.get_post_meta($post->ID, 'hero_bgpos_y', true).';';
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
?>
