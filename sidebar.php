<?php
global $post;

// Get current page's children
$menu_items = get_posts(
        array(
                'posts_per_page'   => 20,
                'orderby'          => 'menu_order, title',
                'order'            => 'ASC',
                'post_type'        => 'page',
                'post_parent'      => $post->ID,
        )
);

if (empty($menu_items)) { // No children, get siblings
    $menu_items = get_posts(
            array(
                    'posts_per_page'   => 20,
                    'orderby'          => 'menu_order, title',
                    'order'            => 'ASC',
                    'post_type'        => 'page',
                    'post_parent'      => $post->post_parent,
            )
    );
}

echo '<ul>'."\n";
foreach ($menu_items as $item) {
    echo '<li>'."\n";
    echo '<a href="'.get_permalink($item->ID).'">'.$item->post_title.'</a>'."\n";
    echo '</li>'."\n";
}
echo '</ul>'."\n";
