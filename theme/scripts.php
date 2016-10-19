<?php
add_editor_style('css/foundation-min.css');
add_editor_style('css/dynamic.css');
add_editor_style('css/style.css');
add_editor_style('css/editor.css');
$google_fonts = bb_get_theme_mod('gf');
if (!empty($google_fonts)) {
    add_editor_style($google_fonts);
}

add_action('admin_enqueue_scripts', array('bb_enqueue', 'admin_scripts'));
add_action('wp_enqueue_scripts', array('bb_enqueue', 'frontend_scripts'));
class bb_enqueue {
    public static function admin_scripts() {
        wp_enqueue_style('admin', get_stylesheet_directory_uri().'/css/admin.css', array(), filemtime(get_stylesheet_directory().'/css/admin.css'));
    }

    public static function frontend_scripts() {
        // Core styles
        wp_enqueue_style('foundation', get_stylesheet_directory_uri().'/css/foundation.min.css');

        // Google fonts (configured through Customizer)
        $google_fonts = bb_get_theme_mod('gf');
        if (!empty($google_fonts)) {
            wp_enqueue_style(ns_.'gf', $google_fonts);
        }

        // Theme styles
        wp_enqueue_style('theme_style', get_stylesheet_directory_uri().'/css/style.css', array(), filemtime(get_stylesheet_directory().'/css/style.css'));
        wp_enqueue_style('theme_dynamic', get_stylesheet_directory_uri().'/css/'.bb_get_dynamic_styles_filename(), array(), filemtime(get_stylesheet_directory().'/css/'.bb_get_dynamic_styles_filename()));
    	wp_enqueue_style('slick', get_stylesheet_directory_uri().'/css/vendor/slick.css', array(), '1.5.8');

        // Header scripts

        // Footer sripts
        wp_enqueue_script('what-input', get_template_directory_uri().'/js/vendor/what-input.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('zurb', get_template_directory_uri().'/js/foundation.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('slick', get_template_directory_uri().'/js/vendor/slick.min.js', array('jquery'), '1.5.8', true);
        wp_enqueue_script('panels', get_template_directory_uri().'/js/panels.js', array('jquery', 'slick'), '0.0.1', true);

        // Remove admin-only styles for front end
        if (!is_admin()) {
            wp_deregister_style('thickbox');
            wp_deregister_style('tiptipCSS');
            wp_deregister_style('chosenCSS');
            wp_deregister_style('jqueryuiCSS');
            wp_deregister_style('wpclef-main');
        }

        // Remove Simple Site Speed script/CSS if not needed
        if (!current_user_can('manage_options') && !array_key_exists('debug', $_GET)) {
            wp_deregister_script('mainscript');
            wp_deregister_style('mainstyle');
        }
    }
}
