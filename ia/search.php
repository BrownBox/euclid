<?php
if (function_exists("register_field_group")) {
    register_field_group(array(
            'id' => 'search_meta',
            'title' => 'Search Meta',
            'fields' => array(
                    array(
                            'key' => 'bb_search_meta',
                            'label' => 'Internal Keywords',
                            'instructions' => 'Used to refine internal search results. Comma seperate between keywords/phrases',
                            'name' => 'keywords',
                            'type' => 'textarea',
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'min' => '',
                            'max' => '',
                            'step' => '',
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
