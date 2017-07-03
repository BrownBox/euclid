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
@media only screen {
    #row-hero a:hover { opacity:1;}
    #row-hero {color: <?php echo bb_get_theme_mod('bb_colour1'); ?>; text-shadow: 0.125rem 0.125rem 0.125rem #555;}
    #row-hero .hero-content {bottom: 0.5rem; left: 0; position: absolute; margin: 0 0.9375rem;}

    #row-hero .logo { position:absolute; z-index:999; top:-15px; background-color: <?php echo bb_get_theme_mod('bb_colour6');?>;}
    #row-hero .navigation { position:relative; z-index:99; top:30px; padding:0.5rem 1rem; background-color:<?php echo bb_get_theme_mod('bb_colour1');?>;}
    #row-hero .announcement { position:absolute; z-index:99; right:0; top:94px; background-color: <?php echo bb_get_theme_mod('bb_colour6');?>; right:15px;}
    #row-hero .announcement p { margin-bottom:0;}
    #row-hero .announcement a { padding:0.5rem;}
    #row-hero .cta { position:relative; z-index:99; margin-top:6rem;}
    #row-hero .cta-wrapper { padding:0;}
    #row-hero .cta h1 { font-weight:700; }
    #row-hero .cta p { border:2px solid <?php echo bb_get_theme_mod('bb_colour1');?>; border-bottom:none; padding:0.5rem; margin-bottom:0;}

    #row-hero .announcement-on-small { position:relative; z-index:99; background-color: <?php echo bb_get_theme_mod('bb_colour6');?>;}
    #row-hero .announcement-on-small p { margin-bottom:0;}
    #row-hero .announcement-on-small a { padding:0.5rem;}

    #row-hero .off-canvas-menu a.button { padding:0.5rem 1.5rem; margin-bottom:0.8rem;}
     #row-hero .off-canvas-menu a {padding: 0 1rem;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    #row-hero .navigation { top:40px;}
    #row-hero .logo { top:-25px; }
    #row-hero .cta { margin-top:8rem;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    #row-hero .logo { top:-35px; }
}
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
    #row-hero {background-color: <?php echo bb_get_theme_mod('colour'.$section_args['meta']['hero_bgcolour']); ?>;}
    #row-hero:before {background-image: url(<?php echo $section_args['images']['small']; ?>); background-position: <?php echo $bgpos_x_small.' '.$bgpos_y_small; ?>; opacity: <?php echo $section_args['meta']['bg_opacity']; ?>;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    #row-hero:before {background-image: url(<?php echo $section_args['images']['medium']; ?>); background-position: <?php echo $bgpos_x_medium.' '.$bgpos_y_medium; ?>;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    #row-hero:before {background-image: url(<?php echo $section_args['images']['large']; ?>); background-position: <?php echo $bgpos_x_large.' '.$bgpos_y_large; ?>;}
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
    $logo = bb_get_theme_mod(ns_.'logo_small');

    echo '<div class="announcement-on-small text-center small-24 show-for-small-only column">'."\n";
        echo '<p><a class="text1" href="'.bb_get_theme_mod(ns_.'announcement_link').'">'.bb_get_theme_mod(ns_.'announcement_text').'</a></p>'."\n";
    echo '</div>'."\n";

    echo '<div class="navigation small-24 column">'."\n";
    echo '  <div class="logo small-12 medium-8 large-6 column">'."\n";
    echo '      <a href="/"><img src="'.$logo.'"></a>'."\n";
    echo '  </div>'."\n";
    echo '  <ul class="menu show-for-large clearfix float-right">'."\n";
                bb_menu('main');
    echo '  </ul>'."\n";
    echo '  <div class="off-canvas-menu hide-for-large text-right">'."\n";
    echo '      <a class="search" href=""><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>'."\n";
    echo '      <a href="/donate" class="button">Donate</a>'."\n";
    echo '      <a class="hamburger" data-open="offCanvasRight">'."\n";
    echo '          <i class="fa fa-bars fa-2x" aria-hidden="true"></i>'."\n";
    echo '      </a>'."\n";
    echo '  </div>'."\n";
    echo '</div>'."\n";

    echo '<div class="announcement show-for-medium">'."\n";
        echo '<p><a class="text1" href="'.bb_get_theme_mod(ns_.'announcement_link').'">'.bb_get_theme_mod(ns_.'announcement_text').'</a></p>'."\n";
    echo '</div>'."\n";

    if(!empty($meta['hero_title']) && !empty($meta['hero_tagline_desc'])){
        echo '<div class="cta small-24 column">'."\n";
        echo '  <div class="cta-wrapper small-24 medium-10 large-8 column">'."\n";
            echo '  <a href="'.$meta['hero_tagline_link'].'">'."\n";
            echo '      <h1 class="text1">'.strtoupper($meta['hero_title']).'</h1>'."\n";
            echo '      <p class="text1">'.$meta['hero_tagline_desc'].'</p>'."\n";
            echo '  </a>'."\n";
        echo '  </div>'."\n";
        echo '</div>'."\n";
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

