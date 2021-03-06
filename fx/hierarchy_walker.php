<?php
/**
 * Works its way up the post hierarchy to find a meta value
 * @param string $field Key to look for
 * @param integer|WP_Post $post (optional) Post to start from
 * @param integer $last_id (optional) Variable to hold the ID of the post the value was found on (or the top-level ancestor if no value found)
 * @param string $type Type of data to look for. Accepts either "meta" (default) or "taxonomy".
 * @return mixed
 */
function get_value_from_hierarchy($field, $post = null, &$last_id = null, $type = "meta") {
    $post = get_post($post);

    if ($post instanceof WP_Post) {
        $id = $post->ID;
        $ancestors = get_ancestors($id, get_post_type($id));
        do {
            $last_id = $id;
            if ($type == 'taxonomy') {
                $value = wp_get_object_terms($id, $field);
            } else {
                if ($field == 'featured_image') {
                    $value = bb_get_featured_image_url('full', $id);
                } else {
                    $value = get_post_meta($id, $field, true);
                }
            }
            $id = array_shift($ancestors);
        } while ($id > 0 && empty($value));
    }

    // Check for fallback default from customizer
    if (empty($value)) {
        $value = bb_get_theme_mod('default_'.$field);
        if (!empty($value)) {
            $last_id = 0;
        }
    }

    return $value;
}
