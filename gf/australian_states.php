<?php
// Gravity Forms Custom Addresses (Australia)
add_filter('gform_address_types', 'australian_address', 10, 2);

function australian_address( $address_types, $form_id ) {
    $address_types['australia'] = array(
    'label'       =>   'Australia', //labels the dropdown
//     'country'     =>   'Australia', //sets Australia as default country
    'zip_label'   =>   'Zip / Postal Code', //what it says
    'state_label' =>   'State', //as above
    'states' => array(
        '',
        'ACT' => 'ACT',
        'NSW' => 'NSW',
        'NT' => 'NT',
        'QLD' => 'QLD',
        'SA' => 'SA',
        'TAS' => 'TAS',
        'VIC' => 'VIC',
        'WA' => 'WA'
        )
    );
    return $address_types;
}