<?php
/**
 * Based on @version 1.0.3
 *
 * Section for displaying children as tiles
 *
 * STEP 2: CODE
 * @todo code the output markup. focus on grids and layouts for Small, Medium and Large Devices.
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
    $meta = bb_get_post_meta($post->ID);

    $ancestors = get_ancestors($post->ID, get_post_type($post));
    $ancestor_string = '';
    if (!empty($ancestors)) {
        $ancestor_string = '_'.implode('_', $ancestors);
    }
    $transient_suffix = $ancestor_string.'_'.$post->ID;
} else {
    $meta = array();
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
        'meta' => $meta,
        'children' => bb_get_children($post),
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
@media only screen {}
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

// ---------------------------------------
// setup local css transient for this post
// ---------------------------------------
$transient = ns_.$section_args['namespace'].'css'.$section_args['transient_suffix'];
if (false === $section_args['transients']) {
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
<div class="small-24 column">
    <h1><?php the_title(); ?></h1>
    <article <?php post_class() ?>>
        <?php echo apply_filters('the_content', $post->post_content); ?>
    </article>
    <div class="row small-up-1 medium-up-3 child-tiles text-center">
<?php
$tmp_post = $post;
foreach ($section_args['children'] as $post) {
    setup_postdata($post);
    $id = $post->ID;
    $slug = get_the_slug($post->ID);
    $title = get_the_title($post);
    if (!empty($post->post_excerpt)) {
        $content = apply_filters('the_content', get_the_excerpt());
    } else {
        $content = apply_filters('the_content', $post->post_content);
    }

    $image = get_value_from_hierarchy('featured_image', $post->ID);

    if (!empty($image)) {
        $image_style = 'background-image: url('.$image.'); background-position: center center;';
?>
        <article id="<?php echo $slug; ?>" <?php post_class('child column', $post->ID); ?>>
            <div class="image" style="<?php echo $image_style; ?>">
                <a class="link" href="<?php echo $slug; ?>"><span><h2 class="tile-menu-header text10"><?php echo $title; ?></h2></span></a>
            </div>
        </article>
<?php
    }
}
$post = $tmp_post;
?>
    </div>
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
