<?php
/**
 * Get panels for the current page
 * @param array $params
 * @return array
 */
function bb_get_panels() {
    $panels = wp_cache_get('bb_panels');
    if (false === $panels) {
        global $post;
        $args = array(
                'posts_per_page' => -1,
                'post_type' => 'panel',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_parent' => 0,
                'tax_query' => array(
                        array(
                                'taxonomy' => 'pageascategory',
                                'field' => 'slug',
                                'terms' => (string)$post->ID,
                        ),
                ),
        );
        $panels = get_posts($args);
        wp_cache_set('bb_panels', $panels);
    }
    return $panels;
}

add_shortcode('bb_panels', 'bb_show_panels');
/**
 * Main loop for displaying panels
 */
function bb_show_panels($position = '') {
    $panels = bb_get_panels();

    foreach ($panels as $panel) {
        if (empty($position) || ($position == 'top' && $panel->menu_order < 50) || ($position == 'bottom' && $panel->menu_order >= 50)) {
            bb_show_panel($panel);
        }
    }
}

/**
 * Display a single panel
 * @param WP_Post $panel
 */
function bb_show_panel(WP_Post $panel) {
    $args = array(
            'posts_per_page' => -1,
            'post_type' => 'panel',
            'orderby' => 'menu_order',
            'order' => 'DESC',
            'post_parent' => $panel->ID,
    );
    $slides = get_posts($args);
    if (count($slides) > 0) {
        $slider = $panel;
        include(get_stylesheet_directory().'/panels/slider.php');
    } else {
        include(get_stylesheet_directory().'/panels/banner.php');
    }
}

/**
 * Get list of available panel recipes
 * @return array
 */
function bb_panels_get_recipes() {
    $recipes = array();
    $dir = opendir(get_stylesheet_directory().'/panels/recipes/');
    while (false !== ($filename = readdir($dir))) {
        if (strpos($filename, '.php') !== false) {
            $recipes[] = str_replace('.php', '', $filename);
        }
    }
    return $recipes;
}

/**
 * Convert list of recipes into associative array of value => label for us in select fields
 * @return array
 */
function bb_panels_get_recipe_options() {
    $recipes = bb_panels_get_recipes();
    sort($recipes);
    $recipe_options = array();
    foreach ($recipes as $recipe) {
        $recipe_options[$recipe] = ucwords(str_replace('_', ' ', $recipe));
    }
    return $recipe_options;
}

/**
 * Build a panel using the relevant recipe
 * @param WP_Post $panel
 */
function bb_panel_cook_recipe(WP_Post $panel) {
    include(get_stylesheet_directory().'/panels/recipes/'.get_post_meta($panel->ID, 'recipe', true).'.php');
}

/**
 * Determine whether the panel title should be displayed or not
 * @param WP_Post $panel
 * @return boolean
 */
function bb_panel_show_title(WP_Post $panel) {
    $hide_title = get_post_meta($panel->ID, 'hide_title', true);
    return empty($hide_title);
}

/**
 * Display the panel title (unless configured to hide title)
 * @param WP_Post $panel
 */
function bb_panel_title(WP_Post $panel) {
    if (bb_panel_show_title($panel)) {
        echo '<h1>'.$panel->post_title.'</h1>'."\n";
    }
}

/**
 * Display the panel content
 * @param WP_Post $panel
 */
function bb_panel_content(WP_Post $panel) {
    echo apply_filters('the_content', $panel->post_content);
}
