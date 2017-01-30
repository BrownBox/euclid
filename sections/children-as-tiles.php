<?php
/**
 * Section for displaying children as tiles
 */
global $post;
$children = bb_get_children($post);
?>
<div class="small-24 column">
    <h1><?php the_title(); ?></h1>
</div>
<?php
if (get_value_from_hierarchy('hero_style') == 'alternate') {
?>
<div class="small-24 column"><?php include(trailingslashit(dirname(__FILE__)).'partials/alternate-hero.php'); ?></div>
<?php
}
?>
<div class="small-24 column">
    <article <?php post_class() ?>>
        <?php echo apply_filters('the_content', $post->post_content); ?>
    </article>
    <div class="row small-up-1 medium-up-3 child-tiles text-center">
<?php
$tmp_post = $post;
foreach ($children as $post) {
    setup_postdata($post);
    $id = $post->ID;
    $slug = get_the_slug($post->ID);
    $title = get_the_title($post);
    if (!empty($post->post_excerpt)) {
        $content = apply_filters('the_content', get_the_excerpt());
    } else {
        $content = apply_filters('the_content', $post->post_content);
    }

    $images = bb_get_hero_images($post->ID);

    if (!empty($images['small'])) {
        $meta = get_post_meta($post->ID);
        $bgpos_x = !empty($meta['hero_bgpos_x_small'][0]) ? $meta['hero_bgpos_x_small'][0] : $meta['hero_bgpos_x'][0];
        $bgpos_y = !empty($meta['hero_bgpos_y_small'][0]) ? $meta['hero_bgpos_y_small'][0] : $meta['hero_bgpos_y'][0];
        $image_style = 'background-image: url('.$images['small'].'); background-position: '.$bgpos_x_small.' '.$bgpos_y_small.';';
?>
        <article id="<?php echo $slug; ?>" <?php post_class('child column', $post->ID); ?>>
            <div class="image" style="<?php echo $image_style; ?>">
                <a class="link" href="<?php echo $slug; ?>"><span><h2 class="tile-menu-header text10"><?php echo $title; ?></h2></span></a>
            </div>
        </article>
<?php
    }
}
$post = $tmp_post;
?>
    </div>
</div>
