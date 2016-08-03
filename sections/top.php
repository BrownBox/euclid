<?php
global $post;
$title = get_the_title();
if (has_post_thumbnail($post->ID)) {
    $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
    $image = $image_data[0];
}

if (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $title = $term->name;
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
