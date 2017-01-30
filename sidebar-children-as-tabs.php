<?php

/**
 * based on @version 1.0.1
 *
 * Sidebar for children-as-tabs
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
    'namespace' => 'sidebar-children-as-tabs', // remember to use keywords like 'section' or 'nav' where practical.
    'filename'  => str_replace(get_stylesheet_directory(), "", __FILE__ ), // relative path from the theme folder
    'get_meta'  => false,
    );

$transients = false; // change this to false to force all transients to refresh
// reset_transients( $section_args['namespace'] ); // force a reset of all transients for this namespace.


// -------------
// get_post_meta
// -------------
if( $section_args['get_meta'] === true ){
    $transient = ns_.'meta_'.$post->ID;
    if( false === $transients) delete_transient( $transient );
    if ( false === ( $meta = unserialize( get_transient( $transient ) ) ) ){

        $meta = get_post_meta( $post->ID );
        set_transient( $transient, serialize( $meta ), LONG_TERM );
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
    set_transient( $transient, $ob, LONG_TERM );
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
    set_transient( $transient, $ob, LONG_TERM );
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

    set_transient( $transient, $children, LONG_TERM );
    if( false === $transients) delete_transient( $transient );
}
unset( $transient );

// ------------------------
// setup output transient/s
// ------------------------
$transient = ns_.$section_args['namespace'].'_markup_'.$post->ID.'_'.md5( $section_args['filename'] );
if( false === $transients) delete_transient( $transient );
if ( false === ( $ob = get_transient( $transient ) ) ) {

    ob_start();

    // section content - start
    echo '<!-- START: '.$section_args['filename'].' -->'."\n";

    // section content

    $has_children = true;
    $menu_items = $children;
    if (empty($menu_items) && $post->post_parent > 0) { // No children, get siblings
        $has_children = false;
        $menu_items = bb_get_children($post->post_parent);
    }

    $menu = '';
    $is_active = false;
    if(!empty($post->post_content)){
        $menu .= ' <li class="menu-item tabs-title is-active bg">'."\n";
        $menu .= '      <a href="#'.get_the_slug($post->ID).'">'.$post->post_title.'</a>'."\n";
        $menu .= ' </li>'."\n";
        $is_active = true;
    }

    foreach ($menu_items as $item) {
        if( $is_active == false ) {
            $class = 'is-active';
            $is_active = true;
        }
        $menu .= '        <li class="menu-item tabs-title '.$class.'">'."\n";
        unset($class);
        if (bb_has_children($item->ID) || !empty($item->post_excerpt)) { // If page has children or excerpt, link to the actual page
            $menu .= '            <a class="text3 htext9" href="#'.get_the_slug($item->ID).'">'.$item->post_title.'</a>'."\n";
        } elseif (!$has_children) { // If we're showing siblings (and not linking to the full page), link to the anchor on the parent page
            $menu .= '            <a class="text3 htext9" href="'.get_the_permalink($post->post_parent).'#'.get_the_slug($item->ID).'">'.$item->post_title.'</a>'."\n";
        } else { // Otherwise just link to the anchor on the current page
            $menu .= '            <a class="text3 htext9" href="#'.get_the_slug($item->ID).'">'.$item->post_title.'</a>'."\n";
        }
        $menu .= '        </li>'."\n";
    }


    if (!empty($menu)) {
    ?>
    <div class="hide-for-medium row" data-sticky-container>
        <div class="sticky small-24 column" data-sticky data-sticky-on="small" data-anchor="row-children-as-tabs">
            <ul class="menu tabs vertical" data-accordion-menu>
                <li class="selection">
                    <a href="#">Please select a section</a>
                    <ul class="menu tabs vertical nested" id="about-us-tabs" data-tabs>
    					<?php echo $menu; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <ul class="show-for-medium menu tabs vertical" id="about-us-tabs" data-tabs>
    	<?php echo $menu; ?>
    </ul>
    <?php
    }


    // section content - end
    echo '<!-- END:'.$section_args['filename'].' -->'."\n";

    $ob = ob_get_clean();
    set_transient( $transient, $ob, LONG_TERM );
    if( false === $transients) delete_transient( $transient );

}
unset( $transient );
echo $ob; unset( $ob );