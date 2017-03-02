<?php
/**
 * Gets meta for a post
 * @param integer|WP_Post $post Optional. Post to retrieve meta for
 * @param string $key Optional. Meta key to retrieve value for
 * @return array|mixed If key is empty will return array of meta values already single-ised, else will return value for the specified key
 */
function bb_get_post_meta($post = null, $key = '') {
    $post = get_post($post);
    $meta = false;
    if ($post instanceof WP_Post) {
        $transients = defined(WP_BB_ENV) && WP_BB_ENV == 'PRODUCTION'; // Set this to false to force all transients to refresh
        $transient = ns_.'meta_'.$post->ID.'_';
        if (false === $transients) {
            delete_transient($transient);
        }
        if (false === ($meta = get_transient($transient))) {
            $meta = bb_rationalise_meta(get_post_meta($post->ID));
            if (true === $transients) {
                set_transient($transient, $meta, LONG_TERM);
            }
        }
        unset($transient);

        if (!empty($key)) {
            return $meta[$key];
        }
    }

    return $meta;
}

/**
 * Clean up meta array to include single values as direct value, like $single parameter to get_post_meta()
 * @param array $meta
 * @return array
 */
function bb_rationalise_meta(array $meta) {
    $clean_meta = array();
    foreach ($meta as $k => $v) {
        if (is_array($v) && count($v) == 1) {
            $clean_meta[$k] = $v[0];
        } else {
            $clean_meta[$k] = $v;
        }
    }
    return $clean_meta;
}
