<?php
/**
 * Get the children of the specified post
 * @param int|WP_Post (optional) $post
 * @return array List of posts
 */
function bb_get_children($post = null) {
    if (is_null($post)) {
        global $post;
    }
    if (is_int($post)) {
        $post = get_post($post);
    }
    $args = array(
            'posts_per_page' => -1,
            'orderby' => array('menu_order' => 'ASC', 'title' => 'ASC'),
            'post_type' => get_post_type($post),
            'post_parent' => $post->ID,
    );
    return get_posts($args);
}
/**
 * Check if the post has children
 *
 * @param int $post_id
 * @return bool
 */
function bb_has_children($post_id = null) {
    if (is_null($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    $query = new WP_Query(array('post_parent' => $post_id, 'post_type' => get_post_type($post_id)));
    return $query->have_posts();
}

/**
 * Add excerpts to pages
 */
add_action('init', 'bb_add_excerpts_to_pages');
function bb_add_excerpts_to_pages() {
     add_post_type_support('page', 'excerpt');
}
