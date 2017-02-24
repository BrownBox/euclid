<?php
/**
 * Based on @version 1.0.3
 *
 * Hero
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

if (is_archive()) {
    $archive_page = get_page_by_path(get_post_type($post));
    if (is_post_type_archive()) {
        $title = $archive_page->post_title;
    } else {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $title = $term->name;
    }
    $meta = bb_get_post_meta($archive_page->ID);
    $images = bb_get_hero_images($archive_page);
    $transient_suffix = '_'.get_post_type($post);
} elseif (is_home() && !is_front_page()) {
    $blog_page = get_option('page_for_posts', true);
    $title = get_the_title($blog_page);
    $meta = bb_get_post_meta($blog_page);
    $images = bb_get_hero_images($blog_page);
    $transient_suffix = '_'.get_post_type($post);
} else {
    $ancestors = get_ancestors($post->ID, get_post_type($post));
    $ancestor_string = '';
    if (!empty($ancestors)) {
        $ancestor_string = '_'.implode('_', $ancestors);
    }
    $transient_suffix = $ancestor_string.'_'.$post->ID;
    $title = get_the_title();
    $meta = bb_get_post_meta($post->ID);
    $images = bb_get_hero_images();
}

$filename = str_replace(get_stylesheet_directory(), "", __FILE__); // Relative path from the theme folder
$transient_suffix .= '_'.md5($filename);

$section_args = array(
        'namespace' => basename(__FILE__, '.php').'_', // Remember to use keywords like 'section' or 'nav' where logical
        'filename'  => $filename,
        'transients' => defined(WP_BB_ENV) && WP_BB_ENV == 'PRODUCTION', // Set this to false to force all transients to refresh
        'transient_suffix' => $transient_suffix,
        'meta' => $meta,
        'title' => $title,
        'images' => $images,
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
    if (!empty($section_args['images']['large'])) {
        $bgpos_x_large = $section_args['meta']['hero_bgpos_x'];
        $bgpos_y_large = $section_args['meta']['hero_bgpos_y'];
        $bgpos_x_medium = !empty($section_args['meta']['hero_bgpos_x_medium']) ? $section_args['meta']['hero_bgpos_x_medium'] : $bgpos_x_large;
        $bgpos_y_medium = !empty($section_args['meta']['hero_bgpos_y_medium']) ? $section_args['meta']['hero_bgpos_y_medium'] : $bgpos_y_large;
        $bgpos_x_small = !empty($section_args['meta']['hero_bgpos_x_small']) ? $section_args['meta']['hero_bgpos_x_small'] : $bgpos_x_large;
        $bgpos_y_small = !empty($section_args['meta']['hero_bgpos_y_small']) ? $section_args['meta']['hero_bgpos_y_small'] : $bgpos_y_large;
?>
<style>
/* START: <?php echo $section_args['filename'].' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
    .hero {background-position: <?php echo $bgpos_x_small.' '.$bgpos_y_small; ?>; background-color: <?php echo $section_args['meta']['hero_bgcolour']; ?>;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    .hero {background-position: <?php echo $bgpos_x_medium.' '.$bgpos_y_medium; ?>;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    .hero {background-position: <?php echo $bgpos_x_large.' '.$bgpos_y_large; ?>;}
}
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $section_args['filename']; ?> */
</style>
<?php
    }
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
    if (!empty($section_args['images']['large'])) {
?>
<div class="hero hero-height small-24 medium-24 large-24 column" bg-srcset="<?php echo $section_args['images']['small']; ?> 639w, <?php echo $section_args['images']['medium']; ?> 1023w, <?php echo $section_args['images']['large']; ?>">
    <div class="row">
        <div class="small-24 medium-24 large-24 column">
   	        <h1><?php echo $section_args['title']; ?></h1>
   	    </div>
    </div>
</div>
<script>
jQuery(document).ready(function() {
    var bgss = new bgsrcset();
    bgss.init('.hero');
});
</script>
<?php
    }

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

