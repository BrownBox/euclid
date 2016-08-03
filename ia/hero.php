<?php
if (function_exists("register_field_group")) {
    register_field_group(array(
            'id' => 'acf_hero',
            'title' => 'Hero',
            'fields' => array(
                    array(
                            'key' => 'field_57959955405f1',
                            'label' => 'Background Colour',
                            'name' => 'hero_bgcolour',
                            'type' => 'color_picker',
                            'default_value' => '',
                    ),
                    array(
                            'key' => 'field_57959990405f2',
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
                            'key' => 'field_579599d5405f3',
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
                    'position' => 'side',
                    'layout' => 'default',
                    'hide_on_screen' => array(),
            ),
            'menu_order' => 0,
    ));
}
