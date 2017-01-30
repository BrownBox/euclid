<?php
/**
 * Based on @version 1.0.2
 *
 * STEP 1: SETUP
 * @todo describe the purpose of this file
 * @todo define $sections_args & @todo set transients to false
 * @todo define local css $transient & @todo set burn time as SHORT_TERM, MEDIUM_TERM or LONG_TERM
 * @todo define the output $transient & @todo set burn time as SHORT_TERM, MEDIUM_TERM or LONG_TERM
 *
 * STEP 2: CODE
 * @todo code the output markup. focus on grids and layouts for Small, Medium and Large Devices.
 * @todo code the local css. Mobile 1st, then medium and large.
 *
 * STEP 3: SIGN_OFF
 * @todo review code quality (& rework as required)
 * @todo review and promote css (as required)
 * @todo reset transitents and retest
 * @todo set transients for production.
 * @todo leave sign-off name and date
 *
 */
global $post;

$section_args = array(
        'namespace' => basename(__FILE__, '.php').'_', // Remember to use keywords like 'section' or 'nav' where logical
        'filename'  => str_replace(get_stylesheet_directory(), "", __FILE__), // Relative path from the theme folder
        'get_meta'  => true,
);

$transients = false; // Set this to false to force all transients to refresh

// -------------
// get_post_meta
// -------------
if ($section_args['get_meta'] === true) {
    $transient = ns_.'meta_'.$post->ID.'_'.md5($section_args['filename']);
    if (false === $transients) {
        delete_transient($transient);
    }
    if (false === ($meta = unserialize(get_transient($transient)))) {
        $meta = get_post_meta($post->ID);
        if (true === $transients) {
            set_transient($transient, serialize($meta), LONG_TERM * HOUR_IN_SECONDS);
        }
    }
    unset($transient);
}

// ---------------------------------------
// setup local css transient for this file
// ---------------------------------------
$transient = ns_ . $section_args['namespace'].'_css_'.$section_args['filename'].'_'.md5($section_args['filename']);
if (false === $transients) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
?>
<style>
/* START: <?php echo $section_args['filename'].' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */ }
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */ }
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $section_args['filename']; ?> */
</style>
<?php
    $ob = ob_get_clean();
    if (true === $transients) {
        set_transient($transient, $ob, LONG_TERM * HOUR_IN_SECONDS);
    }
}
echo $ob;
unset($ob);
unset($transient);

// ---------------------------------------
// setup local css transient for this post
// ---------------------------------------
$transient = ns_.$section_args['namespace'].'_css_'. $post->ID . '_' . md5($section_args['filename']);
if (false === $transients) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
?>
<style>
/* START: <?php echo $section_args['filename'].' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {}

@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */ }

@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */ }

@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}

@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $section_args['filename']; ?> */
</style>
<?php
    $ob = ob_get_clean();
    if (true === $transients) {
        set_transient($transient, $ob, LONG_TERM * HOUR_IN_SECONDS);
    }
}
echo $ob;
unset($ob);
unset($transient);

// ------------------------
// setup output transient/s
// ------------------------
$transient = ns_.$section_args['namespace'].'_markup_'.$post->ID.'_'.md5( $section_args['filename'] );
if (false === $transients) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();

    // section content - start
    echo '<!-- START: '.$section_args['filename'].' -->'."\n";

    // section content
    echo 'tba';

    // section content - end
    echo '<!-- END:'.$section_args['filename'].' -->'."\n";

    $ob = ob_get_clean();
    if (true === $transients) {
        set_transient($transient, $ob, LONG_TERM * HOUR_IN_SECONDS);
    }
}
echo $ob;
unset($ob);
unset($transient);
