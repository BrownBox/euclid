<?php
/**
 * Gets the hero image URL for large, medium and small screens
 * @param mixed $post (optional) Post ID or WP_Post object. If empty will use global $post.
 * @param integer $last_id (optional) Variable to hold the ID of the post the large image was found on (or the top-level ancestor if no value found)
 * @return boolean|array False on error, else associative array of screen sizes => URLs
 */
function bb_get_hero_images($post = null, &$last_id = null) {
    if (is_null($post)) {
        global $post;
    } else {
        $post = get_post($post);
    }
    if (!($post instanceof WP_Post)) {
        return false;
    }

    $large_hero_meta = get_value_from_hierarchy('hero_image', $post->ID, $last_id);
    if (!empty($large_hero_meta)) {
        $large_image = wp_get_attachment_image_src($large_hero_meta, 'full');
        $large_hero = $large_image[0];
    } else {
        $large_hero = get_value_from_hierarchy('featured_image', $post->ID, $last_id);
    }
    $medium_hero_meta = get_value_from_hierarchy('hero_image_medium', $post->ID);
    if (!empty($medium_hero_meta)) {
        $medium_image = wp_get_attachment_image_src($medium_hero_meta, 'full');
        $medium_hero = $medium_image[0];
    } else {
        $medium_hero = $large_hero;
    }
    $small_hero_meta = get_value_from_hierarchy('hero_image_small', $post->ID);
    if (!empty($small_hero_meta)) {
        $small_image = wp_get_attachment_image_src($small_hero_meta, 'full');
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
