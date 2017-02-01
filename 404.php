<?php
if (defined('BB_SUPER_SEARCH') && BB_SUPER_SEARCH) {
    // Build the redirect URL
    $url = '/?s='.strtolower(substr($_SERVER['REQUEST_URI'],1)).'&msg=404'."\n";

    // Log the 404 path
    $logfile = get_template_directory() . '/logs/404.log';
    if(file_exists($logfile)) {
        file_put_contents($logfile, date("Y-m-d H:i:s") . " | " . strtolower($_SERVER['REQUEST_URI']) . "\n", FILE_APPEND);
    }

    // Redirect to search results
    wp_redirect($url);
    exit;
} else {
    get_header();
    bb_theme::section('name=content&file=404.php&inner_class=row');
    get_footer();
}
