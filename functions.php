<?php
define('ns_', 'bb_');
define('THEME_TEXTDOMAIN', ns_.'theme');

$theme_files = array(
    // Theme elements
    array('file' => 'customizer.php',           'dir' => 'theme'), // <-- our customizer fields & settings
    array('file' => 'functions.php',            'dir' => 'theme'), // <-- our theme functions
    array('file' => 'menus.php',                'dir' => 'theme'), // <-- registers our menus
    array('file' => 'scripts.php',              'dir' => 'theme'), // <-- enqueues our styles and scripts

    // Helper functions
    array('file' => 'children.php',             'dir' => 'fx'),
    array('file' => 'columns.php',              'dir' => 'fx'),
    array('file' => 'convert_colour.php',       'dir' => 'fx'),
    array('file' => 'debug.php',                'dir' => 'fx'),
    array('file' => 'extract.php',              'dir' => 'fx'),
    array('file' => 'featured_image.php',       'dir' => 'fx'),
    array('file' => 'hero.php',                 'dir' => 'fx'),
    array('file' => 'hierarchy_walker.php',     'dir' => 'fx'),
    array('file' => 'map.php',                  'dir' => 'fx'),
    array('file' => 'pagination.php',           'dir' => 'fx'),
    array('file' => 'panels.php',               'dir' => 'fx'),
    array('file' => 'slug.php',                 'dir' => 'fx'),
    array('file' => 'time.php',                 'dir' => 'fx'),

    // Miscellaneous utilities
    array('file' => 'cookies.php',              'dir' => 'utils'),

    // Information architecture
    array('file' => 'cpt_.php',                 'dir' => 'ia'),
    array('file' => 'cpt_tax_.php',             'dir' => 'ia'),
//     array('file' => 'meta_.php',                'dir' => 'ia'), // Meta fields functionality provided by ACF
    array('file' => 'tax_.php',                 'dir' => 'ia'),
    array('file' => 'tax_meta_.php',            'dir' => 'ia'),
    array('file' => 'fields.php',               'dir' => 'ia'),
    array('file' => 'hero.php',                 'dir' => 'ia'),
    array('file' => 'panels.php',               'dir' => 'ia'),

    // Custom Gravity Forms pieces
    array('file' => 'australian_states.php',    'dir' => 'gf'), // <-- adds Australia address type
    array('file' => 'columns.php',              'dir' => 'gf'), // <-- adds support for multi-column Gravity Forms
    array('file' => 'enable_fields.php',        'dir' => 'gf'), // <-- enables Credit Card and Password field types
);

foreach ($theme_files as $theme_file) {
    bb_init::include_file($theme_file);
}

class bb_init {
    static function include_file($args) {
        is_array($args) ? extract($args) : parse_str($args);

        // check for required variables
        if (!$dir && !$file) {
            return;
        }

        // include required theme part
        $dir == '' ? locate_template(array($file), true) : locate_template(array($dir. '/' . $file), true);
    }
}
