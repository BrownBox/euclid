<?php
$panel_meta = get_post_meta($panel->ID);
$menu = $panel_meta['menu'][0];
$small_count = $panel_meta['num_per_row_small'][0];
$medium_count = $panel_meta['num_per_row_medium'][0];
$large_count = $panel_meta['num_per_row_large'][0];
$menu_obj = wp_get_nav_menu_object($menu);
$menu_items = wp_get_nav_menu_items($menu_obj->term_id);
?>
<div class="row column small-24 small-up-<?php echo $small_count; ?> medium-up-<?php echo $medium_count; ?> large-up-<?php echo $large_count; ?> text-center">
<?php
foreach ((array)$menu_items as $key => $menu_item) {
    if ($menu_item->menu_item_parent == 0) {
        $image_style = '';
        if (!empty($menu_item->description)) {
            $image_style = 'background-image: url('.$menu_item->description.')';
        }
?>
    <div class="column menu-item menu-item-<?php echo $menu_item->ID.' '.$menu_item->classes[0]; ?>">
        <div class="image" style="<?php echo $image_style; ?>">
            <a class="link" href="<?php echo $menu_item->url; ?>"><span class="h2"><?php echo $menu_item->title; ?></span></a>
        </div>
    </div>
<?php
    }
}
?>
</div>
