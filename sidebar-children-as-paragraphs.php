<?php
/**
 * Children as paragraph sidebar
 */
global $post;

if (!isset($children)) { // In theory section that includes the sidebar should be getting list of children before including it, but just in case...
    $children = bb_get_children($post);
}

$has_children = true;
$menu_items = $children;
if (empty($menu_items) && $post->post_parent > 0) { // No children, get siblings
    $has_children = false;
    $menu_items = bb_get_children($post->post_parent);
}

$menu = '';
foreach ($menu_items as $item) {
    $menu .= '        <li>'."\n";
    if (bb_has_children($item->ID) || !empty($item->post_excerpt)) { // If page has children or excerpt, link to the actual page
        $menu .= '            <a href="'.get_permalink($item->ID).'">'.$item->post_title.'</a><hr>'."\n";
    } elseif (!$has_children) { // If we're showing siblings (and not linking to the full page), link to the anchor on the parent page
        $menu .= '            <a href="'.get_the_permalink($post->post_parent).'#'.get_the_slug($item->ID).'">'.$item->post_title.'</a><hr>'."\n";
    } else { // Otherwise just link to the anchor on the current page
        $menu .= '            <a href="#'.get_the_slug($item->ID).'">'.$item->post_title.'</a><hr>'."\n";
    }
    $menu .= '        </li>'."\n";
}

if (!empty($menu)) {
?>
<div class="hide-for-medium row" data-sticky-container>
    <div class="sticky small-24 column" data-sticky data-sticky-on="small" data-anchor="row-content">
        <ul class="menu vertical" data-accordion-menu>
            <li>
                <a href="#">Please select a section</a>
                <ul class="menu vertical nested">
<?php echo $menu; ?>
                </ul>
            </li>
        </ul>
    </div>
</div>
<ul class="show-for-medium menu vertical">
<?php echo $menu; ?>
</ul>
<?php
}
