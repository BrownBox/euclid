<?php
register_nav_menus(array(
        'top' => 'Top',
        'main' => 'Main',
        'footer' => 'Footer',
));

function bb_menu($args, $echo = true) {
    is_array($args) ? extract($args) : parse_str($args);

    //set defaults
    if (!isset($menu) && strpos($args, '=') == false)
        $menu = $args;
    if (!isset($menu))
        return;
    if (!isset($modal))
        $modal = 'tnz_modal';
    if (!isset($li_class))
        $li_class = 's-no-class';
    if (!isset($class))
        $class = 's-no-class';
    if (!isset($display_children))
        $display_children = false;
    unset($ourmenu);

    global $post;
    $menu_name = $menu;
    $transients = false; // change this to false to force all transients to refresh
    if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
        $transient = 'bb_menu_'.$menu_name.'_'.$display_children.'_'.$post->ID;
        if(false === $transients) {
            delete_transient($transient);
        }

        if(false === ($ourmenu = unserialize(get_transient($transient)))) {
            $menu = wp_get_nav_menu_object($locations[$menu_name]);
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            if ($menu_items) {
                if (isset($columns) && (int)$columns > 1) {
                    $menu_items = bb_sort_array_for_columns($menu_items, $columns);
                    $li_class .= ' column';
                }
                foreach ((array)$menu_items as $key => $menu_item) {
                    // Get direct children menu items
                    $direct_children = get_direct_children($menu_item, $menu_items);
                    $ancestors = get_ancestors($post->ID, 'page');
                    $this_li_class = $li_class;

                    if (in_array($menu_item->object_id, $ancestors) || $post->ID == $menu_item->object_id || (is_home() && get_option('page_for_posts') == $menu_item->object_id) || get_the_slug($menu_item->object_id) == $post->post_type) {
                        $this_li_class .= ' active';
                        if ($display_children && !empty($direct_children)) {
                            $this_li_class .= ' uncollapsed has-children';
                        }
                    }

                    if ($menu_item->menu_item_parent == 0) {
                        $ourmenu .= '<li class="'.$this_li_class.' menu-item menu-item-'.$menu_item->ID.' '.$menu_item->classes[0].'">';
                        if (strpos($menu_item->url, $modal) > 0) {
                            $ourmenu .= '<a data-reveal-id="'.$modal.'_'.$menu_item->object_id.'" href="#">'.$menu_item->title.'</a>';
                        } else {
                            $target = empty($menu_item->target) ? '' : ' target="'.$menu_item->target.'"';
                            $ourmenu .= '<a class="'.$class.'" href="'.$menu_item->url.'"'.$target.'>'.$menu_item->title.'</a>';
                        }

                        // Add child menu items if they exist
                        if ($display_children && !empty($direct_children)) {
                            $ourmenu .= '<ul class="sub-menu">';
                            foreach ($direct_children as $direct_child) {
                                // Build classes for sub menu item container
                                $li_class_child = $li_class;
                                if (in_array($direct_child->object_id, $ancestors) || $post->ID == $direct_child->object_id || (is_home() && get_option('page_for_posts') == $direct_child->object_id) || get_the_slug($direct_child->object_id) == $post->post_type) {
                                    $li_class_child .= ' active';
                                }
                                $ourmenu .= '<li class="'.$li_class_child.' menu-item menu-item-'.$direct_child->ID.' '.$direct_child->classes[0].'">';

                                if (strpos( $direct_child->url, $modal ) > 0) {
                                    $ourmenu .= '<a data-reveal-id="'.$modal.'_'.$direct_child->object_id.'" href="#">'.$direct_child->title.'</a>';
                                } else {
                                    $target = empty($direct_child->target) ? '' : ' target="'.$direct_child->target.'"';
                                    $ourmenu .= '<a class="'.$class.'" href="'.$direct_child->url.'"'.$target.'>'.$direct_child->title.'</a>';
                                }
                                $ourmenu .= '</li>';
                            }
                            $ourmenu .= '</ul>';
                        }
                        $ourmenu .= '</li>';
                    }
                }
            }
        }

        if ($display_children) {
            $ourmenu .= <<<EOJS
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(".off-canvas .menu > .menu-item.has-children > a").click(function() {
            var t = jQuery(this).parent();
            var tsm = t.find(".sub-menu");

            if ( t.hasClass("uncollapsed") ) {
                t.removeClass("uncollapsed");
            } else {
                t.addClass("uncollapsed");
            }
            tsm.toggle();
            return false;
        });
        jQuery(".uncollapsed").find("ul").show();
    });
</script>
EOJS;
        }
    }

    if (isset($ourmenu)) {
        if ($echo) {
            echo $ourmenu;
        } else {
            return $ourmenu;
        }
    }
    return;
}

/**
 * Get direct children
 *
 * @param mixed $item
 * @param array $menu_items
 * @return array
 */
function get_direct_children($item, $menu_items) {
    $sub_items = array();

    foreach ($menu_items as $menu_item) {
        if ($menu_item->menu_item_parent == $item->ID) {
            $sub_items[] = $menu_item;
        }
    }

    return $sub_items;
}
