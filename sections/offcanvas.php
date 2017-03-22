<?php
/**
 * Based on @version 1.0.3
 *
 * This section defines the offcanvas menu space
 *
 * STEP 2: CODE
 * @todo code the local css. Mobile 1st, then medium and large.
 *
 * STEP 3: SIGN_OFF
 * @todo review code quality (& rework as required)
 * @todo review and promote css (as required)
 * @todo reset transients and retest
 * @todo set transients for production
 * @todo leave sign-off name and date
 *
 */

global $post;

if (is_page() || is_single()) {
    $ancestors = get_ancestors($post->ID, get_post_type($post));
    $ancestor_string = '';
    if (!empty($ancestors)) {
        $ancestor_string = '_'.implode('_', $ancestors);
    }
    $transient_suffix = $ancestor_string.'_'.$post->ID;
} else {
    if (is_archive()) {
        $transient_suffix = '_'.$post->post_type;
    }
}

$filename = str_replace(get_stylesheet_directory(), "", __FILE__); // Relative path from the theme folder
$transient_suffix .= '_'.md5($filename);

$section_args = array(
        'namespace' => basename(__FILE__, '.php').'_', // Remember to use keywords like 'section' or 'nav' where logical
        'filename'  => $filename,
        'transients' => defined(WP_BB_ENV) && WP_BB_ENV == 'PRODUCTION', // Set this to false to force all transients to refresh
        'transient_suffix' => $transient_suffix,
);

// ---------------------------------------
// setup local css transient for this file
// ---------------------------------------
$transient = ns_.$section_args['namespace'].'css_'.$section_args['filename'].'_'.md5($section_args['filename']);
if (false === $section_args['transients']) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
?>
<style>
/* START: <?php echo $section_args['filename'].' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
    .off-canvas .menu.vertical > li {background-color: transparent; display: block; margin: 0; max-width: 100%; padding-left: 1rem !important;}
    .off-canvas .menu a {background-color: transparent; color: <?php echo bb_get_theme_mod('bb_colour3'); ?>; border-left: 0.5rem solid rgba(0,0,0,0);}
    .off-canvas .menu a:hover {background-color: transparent; color: <?php echo bb_get_theme_mod('bb_colour9'); ?>; border-left: 0.5rem solid <?php echo bb_get_theme_mod('bb_colour9'); ?>; opacity: 1;}
    .off-canvas .menu .active > a {color: <?php echo bb_get_theme_mod('bb_colour9'); ?>; border-left: 0.5rem solid <?php echo bb_get_theme_mod('bb_colour9'); ?>;}
    .off-canvas {background-color: transparent; right: -250px; top: 0; width: 250px;}
    .off-canvas .menu {list-style-type: none; margin: 1rem 0; padding: 1rem;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */ }
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */ }
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $section_args['filename']; ?> */
</style>
<?php
    $ob = ob_get_clean();
    if (true === $section_args['transients']) {
        set_transient($transient, $ob, LONG_TERM);
    }
    echo $ob; // Intentionally inside transient check as if transient exists, will be output in header.php
    unset($ob);
}
unset($transient);

// ------------------------
// setup output transient/s
// ------------------------
$transient = ns_.$section_args['namespace'].'markup'.$section_args['transient_suffix'];
if (false === $section_args['transients']) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();

    // section content - start
    echo '<!-- START: '.$section_args['filename'].' -->'."\n";

    // section content
?>
<div class="off-canvas position-right" id="offCanvasRight" data-off-canvas data-position="right">
    <button class="close-button" aria-label="Close menu" type="button" data-close>
        <i class="fa fa-times-circle" aria-hidden="true"></i>
    </button>
    <ul class="vertical menu">
<?php bb_menu('main'); ?>
    </ul>
<?php get_search_form(); ?>
</div>
<?php

    // section content - end
    echo '<!-- END:'.$section_args['filename'].' -->'."\n";

    $ob = ob_get_clean();
    if (true === $section_args['transients']) {
        set_transient($transient, $ob, LONG_TERM);
    }
}
echo $ob;
unset($ob);
unset($transient);
