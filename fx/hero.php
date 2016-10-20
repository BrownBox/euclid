<?php
/**
 * Gets the hero image URL for large, medium and small screens
 * @param string $post
 * @return boolean|array False on error, else associative array of screen sizes => URLs
 */
function bb_get_hero_images($post = null) {
    if (is_null($post)) {
        global $post;
    } else {
        $post = get_post($post);
    }
    if (!($post instanceof WP_Post)) {
        return false;
    }

    $meta = get_post_meta($post->ID);
    if (!empty($meta['hero_image'][0])) {
        $large_image = wp_get_attachment_image_src($meta["hero_image"][0], 'full');
        $large_hero = $large_image[0];
    } else {
        $large_hero = get_value_from_hierarchy('featured_image', $post->ID);
    }
    if (!empty($meta['hero_image_medium'][0])) {
        $medium_image = wp_get_attachment_image_src($meta['hero_image_medium'][0], 'full');
        $medium_hero = $medium_image[0];
    } else {
        $medium_hero = $large_hero;
    }
    if (!empty($meta['hero_image_small'][0])) {
        $small_image = wp_get_attachment_image_src($meta['hero_image_small'][0], 'full');
        $small_hero = $small_image[0];
    } else {
        $small_hero = $large_hero;
    }

    return array(
            'large' => $large_hero,
            'medium' => $medium_hero,
            'small' => $small_hero,
    );
}
