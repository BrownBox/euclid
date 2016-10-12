<?php
/**
 * Works its way up the post hierarchy to find a meta value
 * @param string $field Meta key to look for
 * @param integer $id (optional) Post ID to start from
 * @param integer $last_id (optional) Variable to hold the ID of the post the value was found on (or the top-level ancestor if no value found)
 * @return mixed
 */
function get_value_from_hierarchy($field, $id = null, &$last_id = null, $type = "meta") {
    if (empty($id)) {
        global $post;
        if (empty($post))
            return ''; // No global $post var available.
        $id = $post->ID;
    }

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

    // Check for fallback default from customizer
    if (empty($value)) {
        $value = bb_get_theme_mod('default_'.$field);
        if (!empty($value)) {
            $last_id = 0;
        }
    }

    return $value;
}
