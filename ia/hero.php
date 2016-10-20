<?php
if (function_exists("register_field_group")) {
    register_field_group(array(
            'id' => 'acf_hero',
            'title' => 'Hero',
            'fields' => array(
                    array(
                            'key' => 'bb_hero_tab_defaults',
                            'label' => 'Defaults',
                            'name' => 'hero_tab_defaults',
                            'type' => 'tab',
                    ),
                    array(
                            'key' => 'bb_hero_bgcolour',
                            'label' => 'Background Colour',
                            'name' => 'hero_bgcolour',
                            'type' => 'select',
                            'choices' => bb_panels_get_theme_palette(),
                            'default_value' => 'transparent',
                    ),
                    array(
                            'key' => 'bb_hero_image',
                            'label' => 'Hero Image',
                            'name' => 'hero_image',
                            'type' => 'image',
                            'instructions' => 'If no image is specified, the Featured Image will be used instead',
                            'save_format' => 'url',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                    ),
                    array(
                            'key' => 'bb_hero_bgpos_y',
                            'label' => 'Vertical Image Position',
                            'name' => 'hero_bgpos_y',
                            'type' => 'radio',
                            'choices' => array(
                                    'top' => 'Top',
                                    'center' => 'Centre',
                                    'bottom' => 'Bottom',
                            ),
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'default_value' => 'center',
                            'layout' => 'vertical',
                    ),
                    array(
                            'key' => 'bb_hero_bgpos_x',
                            'label' => 'Horizontal Image Position',
                            'name' => 'hero_bgpos_x',
                            'type' => 'radio',
                            'choices' => array(
                                    'left' => 'Left',
                                    'center' => 'Centre',
                                    'right' => 'Right',
                            ),
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'default_value' => 'center',
                            'layout' => 'vertical',
                    ),
                    array(
                            'key' => 'bb_hero_tab_medium',
                            'label' => 'Medium Screens',
                            'name' => 'hero_tab_medium',
                            'type' => 'tab',
                    ),
                    array(
                            'key' => 'bb_hero_image_medium',
                            'label' => 'Hero Image',
                            'name' => 'hero_image_medium',
                            'type' => 'image',
                            'save_format' => 'url',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                    ),
                    array(
                            'key' => 'bb_hero_bgpos_y_medium',
                            'label' => 'Vertical Image Position',
                            'name' => 'hero_bgpos_y_medium',
                            'type' => 'radio',
                            'choices' => array(
                                    '' => 'Use Default',
                                    'top' => 'Top',
                                    'center' => 'Centre',
                                    'bottom' => 'Bottom',
                            ),
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'default_value' => '',
                            'layout' => 'vertical',
                    ),
                    array(
                            'key' => 'bb_hero_bgpos_x_medium',
                            'label' => 'Horizontal Image Position',
                            'name' => 'hero_bgpos_x_medium',
                            'type' => 'radio',
                            'choices' => array(
                                    '' => 'Use Default',
                                    'left' => 'Left',
                                    'center' => 'Centre',
                                    'right' => 'Right',
                            ),
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'default_value' => '',
                            'layout' => 'vertical',
                    ),
                    array(
                            'key' => 'bb_hero_tab_small',
                            'label' => 'Small Screens',
                            'name' => 'hero_tab_small',
                            'type' => 'tab',
                    ),
                    array(
                            'key' => 'bb_hero_image_small',
                            'label' => 'Hero Image',
                            'name' => 'hero_image_small',
                            'type' => 'image',
                            'save_format' => 'url',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                    ),
                    array(
                            'key' => 'bb_hero_bgpos_y_small',
                            'label' => 'Vertical Image Position',
                            'name' => 'hero_bgpos_y_small',
                            'type' => 'radio',
                            'choices' => array(
                                    '' => 'Use Default',
                                    'top' => 'Top',
                                    'center' => 'Centre',
                                    'bottom' => 'Bottom',
                            ),
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'default_value' => '',
                            'layout' => 'vertical',
                    ),
                    array(
                            'key' => 'bb_hero_bgpos_x_small',
                            'label' => 'Horizontal Image Position',
                            'name' => 'hero_bgpos_x_small',
                            'type' => 'radio',
                            'choices' => array(
                                    '' => 'Use Default',
                                    'left' => 'Left',
                                    'center' => 'Centre',
                                    'right' => 'Right',
                            ),
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'default_value' => '',
                            'layout' => 'vertical',
                    ),
            ),
            'location' => array(
                    array(
                            array(
                                    'param' => 'post_type',
                                    'operator' => '==',
                                    'value' => 'page',
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
            'menu_order' => 0,
    ));
}
