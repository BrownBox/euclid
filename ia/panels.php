<?php
new bb_theme\cptClass('Panel','Panels', array(
        'public' => true,
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
if (function_exists("register_field_group")) {
    register_field_group(array(
            'id' => 'acf_panel-settings',
            'title' => 'Panel Settings',
            'fields' => array(
                    array(
                            'key' => 'field_56dcb408746ba',
                            'label' => 'Panel Name',
                            'name' => 'panel_name',
                            'type' => 'text',
                            'instructions' => 'Class name (used for styling)',
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'formatting' => 'html',
                            'maxlength' => '',
                    ),
                    array(
                            'key' => 'field_56dcb603746bb',
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
                            'key' => 'field_56dcb7a7746bf',
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
                            'key' => 'field_56dcb6b4746bc',
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
                            'key' => 'field_56dcb725746bd',
                            'label' => 'Additional Image',
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => 'Some recipes will display an additional image alongside the content',
                            'conditional_logic' => array(
                                    'status' => 1,
                                    'rules' => array(
                                            array(
                                                    'field' => 'field_56dcb603746bb',
                                                    'operator' => '==',
                                                    'value' => 'with_image',
                                            ),
                                    ),
                                    'allorany' => 'any',
                            ),
                            'save_format' => 'url',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                    ),
                    array(
                            'key' => 'field_56dcb7a7746be',
                            'label' => 'Image Position',
                            'name' => 'image_pos',
                            'type' => 'radio',
                            'instructions' => 'Position of the additional image',
                            'conditional_logic' => array(
                                    'status' => 1,
                                    'rules' => array(
                                            array(
                                                    'field' => 'field_56dcb603746bb',
                                                    'operator' => '==',
                                                    'value' => 'with_image',
                                            ),
                                    ),
                                    'allorany' => 'any',
                            ),
                            'choices' => array(
                                    'left' => 'Left',
                                    'right' => 'Right',
                            ),
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'default_value' => 'left',
                            'layout' => 'horizontal',
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
                            'key' => 'field_56dcb33f9007b',
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
                            'key' => 'field_56dcb3929007c',
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
}
