<?php
new bb_theme\cptClass('Panel','Panels', array(
        'public' => false,
        'has_archive' => false,
        'query_var' => false,
        'show_ui' => true,
));

new bb_theme\taxClass('Page as Category', 'Pages as Categories', array('panel'));

/*
 * Page as category
*/
function bb_panels_page_as_category($post_id) {
    // We don't want to do anything when autosaving a draft
    $post = get_post($post_id);
    if (wp_is_post_autosave($post_id) || $post->post_status == 'auto-draft')
        return;

    // Now let's make sure we have the right ID
    $revision = wp_is_post_revision($post_id);
    if ($revision) {
        $post_id = $revision;
        $post = get_post($post_id);
    }

    // Need to mirror the page hierarchy
    $parent_id = $post->post_parent;
    $parent_cat_id = 0;
    if ($parent_id > 0) {
        $parent_category = get_term_by('slug', $parent_id, 'pageascategory');
        if ($parent_category)
            $parent_cat_id = (int)$parent_category->term_id;
    }

    $category = get_term_by('slug', $post_id, 'pageascategory');
    if ($category) { // Update
        wp_update_term((int)$category->term_id, 'pageascategory', array(
        'name' => $post->post_title,
        'slug' => $post_id,
        'parent'=> $parent_cat_id
        )
        );
    } else { // Create
        wp_insert_term($post->post_title, 'pageascategory', array(
        'slug' => $post_id,
        'parent'=> $parent_cat_id
        )
        );
    }
}
add_action('save_post_page', 'bb_panels_page_as_category');

function bb_panels_refresh_page_hierarchy($post_id) {
    // Update child pages (which will in turn update their terms)
    $args = array(
            'post_parent' => $post_id,
            'post_type' => 'page',
    );
    $children = get_children($args);
    foreach (array_keys($children) as $child_id) {
        wp_update_post(array('ID' => $child_id));
    }

    return true;
}
add_action('before_delete_post', 'bb_panels_refresh_page_hierarchy');

function bb_panels_delete_page_as_category($post_id) {
    // If it's only a revision, ignore
    if (wp_is_post_revision($post_id))
        return true;

    $category = get_term_by('slug', $post_id, 'pageascategory');
    if ($category) {
        // Delete term relationships
        global $wpdb;
        $wpdb->query($wpdb->prepare( 'DELETE FROM '.$wpdb->term_relationships.' WHERE term_taxonomy_id = %d', $category->term_id));

        // Delete from users
        $users = get_users();
        foreach ($users as $user) {
            $pages = get_user_meta($user->ID, 'pageascategory', true);
            $pageArr = explode(',',$pages);
            $idx = array_search($category->term_id, $pageArr);
            if ($idx !== false) {
                unset($pageArr[$idx]);
                update_user_meta($user->ID, 'pageascategory', implode(',',$pageArr));
            }
        }

        // Delete term
        wp_delete_term($category->term_id, 'pageascategory');
    }

    return true;
}
add_action('deleted_post', 'bb_panels_delete_page_as_category');

/*
 * End page as category
*/

/**
 * Panel meta fields (uses ACF)
 */
add_action('admin_init', 'bb_panel_meta'); // Needs to run after init to get CPTs in post type list
function bb_panel_meta() {
    if (function_exists("register_field_group")) {
        register_field_group(array(
                'id' => 'acf_panel-settings',
                'title' => 'Panel Settings',
                'fields' => array(
                        array(
                                'key' => 'bb_panels_field_panel_name',
                                'label' => 'Panel Name',
                                'name' => 'panel_name',
                                'type' => 'text',
                                'instructions' => 'Class name (used for styling). Multiple classes can be separated with spaces.',
                                'formatting' => 'text',
                        ),
                        array(
                                'key' => 'bb_panels_field_children',
                                'label' => 'Display Children As',
                                'name' => 'children',
                                'type' => 'radio',
                                'instructions' => 'If this panel has child panels, they can either be displayed as a slider or a series of tiles. Note that if this panel has children most of the following options are ignored.',
                                'choices' => array(
                                        'slider' => 'Slider',
                                        'tiles' => 'Tiles',
                                ),
                                'other_choice' => 0,
                                'save_other_choice' => 0,
                                'default_value' => 'slider',
                                'layout' => 'horizontal',
                        ),
                        array(
                                'key' => 'bb_panels_field_recipe',
                                'label' => 'Recipe',
                                'name' => 'recipe',
                                'type' => 'select',
                                'required' => 1,
                                'choices' => bb_panels_get_recipe_options(),
                                'default_value' => 'default',
                                'allow_null' => 0,
                                'multiple' => 0,
                        ),
                        array(
                                'key' => 'bb_panels_field_flavour',
                                'label' => 'Display Style',
                                'name' => 'flavour',
                                'type' => 'radio',
                                'instructions' => 'How do you want this panel displayed?',
                                'choices' => array(
                                        'full_bleed' => 'Full width',
                                        'partial_bleed' => 'Full width background image, contained content',
                                        'fully_contained' => 'Contained background image and content',
                                ),
                                'other_choice' => 0,
                                'save_other_choice' => 0,
                                'default_value' => 'partial_bleed',
                                'layout' => 'horizontal',
                        ),
                        array(
                                'key' => 'bb_panels_field_hide_title',
                                'label' => 'Hide Panel Title?',
                                'name' => 'hide_title',
                                'type' => 'checkbox',
                                'choices' => array(
                                        'true' => 'Hide Title',
                                ),
                                'default_value' => '',
                                'layout' => 'horizontal',
                        ),
                        array(
                                'key' => 'bb_panels_field_bg_colour',
                                'label' => 'Background Colour',
                                'name' => 'bg_colour',
                                'type' => 'select',
                                'choices' => bb_panels_get_theme_palette(),
                                'default_value' => 'transparent',
                                'multiple' => 0,
                        ),
                        array(
                                'key' => 'bb_panels_field_bg_pos_x',
                                'label' => 'Background Image Anchor (Horizontal)',
                                'name' => 'bg_pos_x',
                                'type' => 'radio',
                                'choices' => array(
                                        'left' => 'Left',
                                        '25%' => '25%',
                                        'center' => 'Centre',
                                        '75%' => '75%',
                                        'right' => 'Right',
                                ),
                                'other_choice' => 0,
                                'save_other_choice' => 0,
                                'default_value' => 'center',
                                'layout' => 'horizontal',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '!=',
                                                        'value' => 'recent_posts',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '!=',
                                                        'value' => 'tile_menu',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '!=',
                                                        'value' => 'tiles',
                                                ),
                                        ),
                                        'allorany' => 'all',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_bg_pos_y',
                                'label' => 'Background Image Anchor (Vertical)',
                                'name' => 'bg_pos_y',
                                'type' => 'radio',
                                'choices' => array(
                                        'top' => 'Top',
                                        '25%' => '25%',
                                        'center' => 'Centre',
                                        '75%' => '75%',
                                        'bottom' => 'Bottom',
                                ),
                                'other_choice' => 0,
                                'save_other_choice' => 0,
                                'default_value' => 'center',
                                'layout' => 'horizontal',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '!=',
                                                        'value' => 'recent_posts',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '!=',
                                                        'value' => 'tile_menu',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '!=',
                                                        'value' => 'tiles',
                                                ),
                                        ),
                                        'allorany' => 'all',
                                ),
                        ),
                        // Recipe-specific options
                        array(
                                'key' => 'bb_panels_field_image',
                                'label' => 'Additional Image',
                                'name' => 'image',
                                'type' => 'image',
                                'instructions' => 'Some recipes will display an additional image alongside the content',
                                'save_format' => 'url',
                                'preview_size' => 'thumbnail',
                                'library' => 'all',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'half_image',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'with_image',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_image_pos',
                                'label' => 'Image Position',
                                'name' => 'image_pos',
                                'type' => 'radio',
                                'instructions' => 'Position of the additional image',
                                'choices' => array(
                                        'left' => 'Left',
                                        'right' => 'Right',
                                ),
                                'other_choice' => 0,
                                'save_other_choice' => 0,
                                'default_value' => 'left',
                                'layout' => 'horizontal',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'half_image',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'with_image',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_menu',
                                'label' => 'Menu',
                                'name' => 'menu',
                                'type' => 'select',
                                'instructions' => 'Each menu item in the selected menu will become a tile, with the description being used as the URL for the background image.',
                                'choices' => bb_panels_get_menus(),
                                'other_choice' => 0,
                                'save_other_choice' => 0,
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'tile_menu',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_post_category',
                                'label' => 'Category',
                                'name' => 'post_category',
                                'type' => 'select',
                                'choices' => bb_panels_get_post_categories(),
                                'default_value' => '',
                                'multiple' => 0,
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'recent_posts',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_num_items',
                                'label' => 'Maximum Number of Items',
                                'name' => 'num_items',
                                'type' => 'number',
                                'default_value' => '6',
                                'formatting' => 'text',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'recent_posts',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_num_per_row_large',
                                'label' => 'Items Per Row (Large Screen)',
                                'name' => 'num_per_row_large',
                                'type' => 'number',
                                'default_value' => '3',
                                'formatting' => 'text',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'recent_posts',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'tile_menu',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_children',
                                                        'operator' => '==',
                                                        'value' => 'tiles',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_num_per_row_medium',
                                'label' => 'Items Per Row (Medium Screen)',
                                'name' => 'num_per_row_medium',
                                'type' => 'number',
                                'default_value' => '3',
                                'formatting' => 'text',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'recent_posts',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'tile_menu',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_children',
                                                        'operator' => '==',
                                                        'value' => 'tiles',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_num_per_row_small',
                                'label' => 'Items Per Row (Small Screen)',
                                'name' => 'num_per_row_small',
                                'type' => 'number',
                                'default_value' => '1',
                                'formatting' => 'text',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'recent_posts',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'tile_menu',
                                                ),
                                                array(
                                                        'field' => 'bb_panels_field_children',
                                                        'operator' => '==',
                                                        'value' => 'tiles',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                        array(
                                'key' => 'bb_panels_field_video',
                                'label' => 'Video URL',
                                'name' => 'video',
                                'type' => 'text',
                                'placeholder' => 'http://',
                                'formatting' => 'text',
                                'conditional_logic' => array(
                                        'status' => 1,
                                        'rules' => array(
                                                array(
                                                        'field' => 'bb_panels_field_recipe',
                                                        'operator' => '==',
                                                        'value' => 'video',
                                                ),
                                        ),
                                        'allorany' => 'any',
                                ),
                        ),
                ),
                'location' => array(
                        array(
                                array(
                                        'param' => 'post_type',
                                        'operator' => '==',
                                        'value' => 'panel',
                                        'order_no' => 0,
                                        'group_no' => 0,
                                ),
                        ),
                ),
                'options' => array(
                        'position' => 'normal',
                        'layout' => 'default',
                        'hide_on_screen' => array(
                                0 => 'excerpt',
                                1 => 'custom_fields',
                                2 => 'discussion',
                                3 => 'comments',
                                4 => 'categories',
                                5 => 'tags',
                                6 => 'send-trackbacks',
                        ),
                ),
                'menu_order' => 0,
        ));

        register_field_group(array(
                'id' => 'acf_call-to-action',
                'title' => 'Call to Action',
                'fields' => array(
                        array(
                                'key' => 'bb_panels_cta_field_action_text',
                                'label' => 'Action Text',
                                'name' => 'action_text',
                                'type' => 'text',
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'formatting' => 'html',
                                'maxlength' => '',
                        ),
                        array(
                                'key' => 'bb_panels_cta_field_destination',
                                'label' => 'Destination URL',
                                'name' => 'destination',
                                'type' => 'text',
                                'default_value' => '',
                                'placeholder' => 'http://',
                                'prepend' => '',
                                'append' => '',
                                'formatting' => 'html',
                                'maxlength' => '',
                        ),
                ),
                'location' => array(
                        array(
                                array(
                                        'param' => 'post_type',
                                        'operator' => '==',
                                        'value' => 'panel',
                                        'order_no' => 0,
                                        'group_no' => 0,
                                ),
                        ),
                ),
                'options' => array(
                        'position' => 'normal',
                        'layout' => 'default',
                        'hide_on_screen' => array(),
                ),
                'menu_order' => 1,
        ));

        register_field_group(array(
                'id' => 'acf_panel_post_types',
                'title' => 'Post Types',
                'fields' => array(
                        array(
                                'key' => 'bb_panels_post_types',
                                'label' => 'Post Types',
                                'description' => 'This panel will be displayed on all posts of the selected types',
                                'name' => 'post_types',
                                'type' => 'checkbox',
                                'choices' => bb_panels_get_post_types(),
                                'default_value' => '',
                                'layout' => 'vertical',
                        ),
                ),
                'location' => array(
                        array(
                                array(
                                        'param' => 'post_type',
                                        'operator' => '==',
                                        'value' => 'panel',
                                        'order_no' => 0,
                                        'group_no' => 0,
                                ),
                        ),
                ),
                'options' => array(
                        'position' => 'side',
                        'layout' => 'default',
                        'hide_on_screen' => array(),
                ),
                'menu_order' => 0,
        ));
    }
}
