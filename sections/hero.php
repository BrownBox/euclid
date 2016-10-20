<?php
global $post;
if (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $title = $term->name;
    $archive_page = get_page_by_path(get_post_type($post));
    $images = bb_get_hero_images($archive_page);
    $meta = get_post_meta($archive_page->ID);
} elseif (is_home() && !is_front_page()) {
    $blog_page = get_option('page_for_posts', true);
    $title = get_the_title($blog_page);
    $images = bb_get_hero_images($blog_page);
    $meta = get_post_meta($blog_page);
} else {
    $title = get_the_title();
    $images = bb_get_hero_images();
    $meta = get_post_meta($post->ID);
}

if (!empty($images['large'])) {
    $bgpos_x_large = $meta['hero_bgpos_x'][0];
    $bgpos_y_large = $meta['hero_bgpos_y'][0];
    $bgpos_x_medium = !empty($meta['hero_bgpos_x_medium'][0]) ? $meta['hero_bgpos_x_medium'][0] : $bgpos_x_large;
    $bgpos_y_medium = !empty($meta['hero_bgpos_y_medium'][0]) ? $meta['hero_bgpos_y_medium'][0] : $bgpos_y_large;
    $bgpos_x_small = !empty($meta['hero_bgpos_x_small'][0]) ? $meta['hero_bgpos_x_small'][0] : $bgpos_x_large;
    $bgpos_y_small = !empty($meta['hero_bgpos_y_small'][0]) ? $meta['hero_bgpos_y_small'][0] : $bgpos_y_large;
?>
<style>
.hero {background-position: <?php echo $bgpos_x_large.' '.$bgpos_y_large; ?>;}
@media only screen and (min-width: 40em) and (max-width: 63.9375em) {
    .hero {background-position: <?php echo $bgpos_x_medium.' '.$bgpos_y_medium; ?>;}
}
@media only screen and (max-width: 39.9375em) {
    .hero {background-position: <?php echo $bgpos_x_small.' '.$bgpos_y_small; ?>;}
}
</style>
<div class="hero small-24 medium-24 large-24 column" style="background-color: <?php echo $meta['hero_bgcolour'][0]; ?>;" bg-srcset="<?php echo $images['small']; ?> 640w, <?php echo $images['medium']; ?> 1200w, <?php echo $images['large']; ?>">
    <div class="row">
        <div class="small-24 medium-24 large-24 column">
   	        <h1><?php echo $title; ?></h1>
   	    </div>
    </div>
</div>
<script>
jQuery(document).ready(function() {
    var bgss = new bgsrcset();
    bgss.init('.hero');
});
</script>
<?php
}
