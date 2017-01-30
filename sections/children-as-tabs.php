<?php
/**
 * based on @version 1.0.1
 *
 * Section for children as tabs
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
            'namespace' => 'section_tabs', // remember to use keywords like 'section' or 'nav' where practical.
            'filename'  => str_replace(get_stylesheet_directory(), "", __FILE__ ), // relative path from the theme folder
            'get_meta'  => true,
        );

$transients = false; // change this to false to force all transients to refresh
// reset_transients( $section_args['namespace'] ); // force a reset of all transients for this namespace.


// -------------
// get_post_meta
// -------------
if( $section_args['get_meta'] === true ){
        $transient = ns_.'meta_'.$post->ID.'_'.md5( $section_args['filename'] );
        if( false === $transients) delete_transient( $transient );
        if ( false === ( $meta = unserialize( get_transient( $transient ) ) ) ){

                $meta = get_post_meta( $post->ID );
                set_transient( $transient, serialize( $meta ), LONG_TERM * HOUR_IN_SECONDS );
                if( false === $transients) delete_transient( $transient );

            }
            unset( $transient );
        }

        // ---------------------------------------
        // setup local css transient for this file
        // ---------------------------------------
        $transient = ns_.$section_args['namespace'].'_css_'.$section_args['filename'].'_'.md5( $section_args['filename'] );
        if( false === $transients) delete_transient( $transient );
        if ( false === ( $ob = get_transient( $transient ) ) ) {

                ob_start(); ?>
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
    set_transient( $transient, $ob, LONG_TERM * HOUR_IN_SECONDS );
    if( false === $transients) delete_transient( $transient );
    echo $ob; unset( $ob );
}
unset( $transient );

// ---------------------------------------
// setup local css transient for this post
// ---------------------------------------
$transient = ns_.$section_args['namespace'].'_css_'.$post->ID.'_'.md5( $section_args['filename'] );
if( false === $transients) delete_transient( $transient );
if ( false === ( $ob = get_transient( $transient ) ) ) {

    ob_start(); ?>
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
    set_transient( $transient, $ob, LONG_TERM * HOUR_IN_SECONDS );
    if( false === $transients) delete_transient( $transient );
    echo $ob; unset( $ob );
}
unset( $transient );

// ------------------------
// get the children
// ------------------------
$transient = ns_.'children_'.$post->ID;
if( false === $transients) delete_transient( $transient );
if ( false === ( $children = get_transient( $transient ) ) ) {

    $children = bb_get_children($post);

    set_transient( $transient, $children, LONG_TERM * HOUR_IN_SECONDS );
    if( false === $transients) delete_transient( $transient );
}
unset( $transient );

// ------------------------
// setup output transient/s
// ------------------------
$transient = ns_.$section_args['namespace'].'_markup_'.$post->ID.'_'.md5($section_args['filename']);
if (false === $transients) delete_transient($transient);
if (false === ($ob = get_transient($transient))) {

    ob_start();

    // section content - start
    echo '<!-- START: '.$section_args['filename'].' -->'."\n";

    // section content
?>
    <div class="children-as-tabs row collapse" data-equalizer data-equalize-on="medium">
        <aside class="medium-8 large-5 column" data-equalizer-watch>
            <?php get_sidebar('children-as-tabs'); ?>
        </aside>
        <div class="small-22 medium-14 large-17 float-left column">
            <h1 class="text3"><?php echo $post->post_title; ?></h1>
            <div class="tabs-content vertical" data-tabs-content="about-us-tabs">
<?php
    $is_active = false;
    if (!empty($post->post_content)) {
        echo '<div class="tabs-panel is-active" id="'. $post->post_name .'" data-equalizer-watch>'."\n";
        echo apply_filters('the_content', $post->post_content);
        echo '</div>'."\n";
        $is_active = true;
    }

    foreach ($children as $child) {
        if ($is_active == false) {
            $class = 'is-active';
            $is_active = true;
        } else {
            $class = '';
        }
        $slug = get_the_slug($child->ID);
        echo '<div class="tabs-panel '.$class.'" id="'. $slug .'" data-equalizer-watch>'."\n";
        echo '<h2 class="text3">'.$child->post_title.'</h2>'."\n";
        echo apply_filters('the_content', $child->post_content);
        echo '</div>'."\n";
        unset($class);
    }
?>
            </div>
        </div>

    </div>
<?php
    // section content - end
    echo '<!-- END:'.$section_args['filename'].' -->'."\n";

    $ob = ob_get_clean();
    set_transient( $transient, $ob, LONG_TERM * HOUR_IN_SECONDS );
    if( false === $transients) delete_transient( $transient );

}
unset( $transient );
echo $ob; unset( $ob );
