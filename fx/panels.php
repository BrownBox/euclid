<?php
/**
 * Get panels for the current page
 * @param array $params
 * @return array
 */
function bb_get_panels() {
    $panels = wp_cache_get('bb_panels');
    if (false === $panels) {
        $args = array(
                'posts_per_page' => -1,
                'post_type' => 'panel',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_parent' => 0,
        );
        if (!is_search()) {
            global $post;
            if ($post->post_type == 'page') {
                $post_id = $post->ID;
                if (is_archive()) {
                    $page = get_page_by_path(get_post_type($post));
                    $post_id = $page->ID;
                }
                $args['tax_query'] = array(
                        array(
                                'taxonomy' => 'pageascategory',
                                'field' => 'slug',
                                'terms' => (string)$post_id,
                        ),
                );
            } else {
                $args['meta_query'] = array(
                        array(
                                'key' => 'post_types',
                                'value' => '"'.$post->post_type.'"', // Values are stored as a serialised array, so we look for the value surrounded by quotes to avoid false positives (e.g. posts and reposts)
                                'compare' => 'LIKE',
                        ),
                );
            }
            $panels = get_posts($args);
            wp_cache_set('bb_panels', $panels);
        }
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
            'order' => 'ASC',
            'post_parent' => $panel->ID,
    );
    $children = get_posts($args);
    if (count($children) > 0) {
        $wrapper = $panel;
        if (get_post_meta($panel->ID, 'children', true) == 'tiles') {
            include(get_stylesheet_directory().'/panels/tiles.php');
        } else {
            include(get_stylesheet_directory().'/panels/slider.php');
        }
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
        echo '<p class="h1">'.$panel->post_title.'</p>'."\n";
    }
}

/**
 * Display the panel content
 * @param WP_Post $panel
 */
function bb_panel_content(WP_Post $panel) {
    echo apply_filters('the_content', $panel->post_content);
}

function bb_panels_get_menus() {
    $menus = get_terms('nav_menu', array('hide_empty' => false));
    $menu_choices = array();

    foreach ($menus as $menu) {
        $menu_choices[$menu->name] = $menu->name;
    }
    return $menu_choices;
}

function bb_panels_get_theme_palette() {
    $colours = bb_get_theme_mod(ns_.'colours', BB_DEFAULT_COLOUR_COUNT);
    $palette_options = array(
            'transparent' => 'None (i.e. transparent)',
    );
    for ($i = 1; $i <= $colours; $i++) {
        $palette_options[$i] = 'Colour '.$i.' ('.bb_get_theme_mod(ns_.'colour'.$i).')';
    }
    return $palette_options;
}

function bb_panels_get_post_categories($taxonomy = 'category') {
    $args = array(
            'hide_empty' => false,
    );
    $terms = get_terms($taxonomy, $args);
    $categories = array(
            '' => 'All',
    );
    foreach ($terms as $term) {
        $categories[$term->term_id] = $term->name;
    }
    return $categories;
}

function bb_panels_get_post_types() {
    $args = array(
            'public' => true,
            '_builtin' => false,
    );
    $post_types = get_post_types($args, 'objects');
    $types = array(
            'post' => 'Posts',
    );
    foreach ($post_types as $post_type) {
        if (!in_array($post_type->name, $ignore)) {
            $types[$post_type->name] = $post_type->label;
        }
    }
    return $types;
}
