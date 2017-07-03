<?php
define('BB_DEFAULT_COLOUR_COUNT', 8);

add_action('customize_register', 'bb_theme_customizer');
function bb_theme_customizer(WP_Customize_Manager $wp_customize) {
    // Key Images (Desktop Logo, Mobile Logo and Favicon)
    $wp_customize->add_section(ns_.'theme_images_section', array(
            'title' => __('Images', ns_),
            'priority' => 30,
    ));
    // large screen
    $wp_customize->add_setting(ns_.'logo_large', array(
            'default' => esc_url(get_template_directory_uri()).'/images/logo_large.png',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'logo_large', array(
            'label' => 'Large Logo',
            'section' => ns_.'theme_images_section',
            'priority' => 10,
    )));
    // medium screen
    $wp_customize->add_setting(ns_.'logo_medium', array(
            'default' => esc_url(get_template_directory_uri()).'/images/logo_medium.png',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'logo_medium', array(
            'label' => 'Medium Logo',
            'section' => ns_.'theme_images_section',
            'priority' => 20,
    )));
    // small screen
    $wp_customize->add_setting(ns_.'logo_small', array(
            'default' => esc_url(get_template_directory_uri()).'/images/logo_small.png',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'logo_small', array(
            'label' => 'Small Logo',
            'section' => ns_.'theme_images_section',
            'priority' => 30,
    )));
    // footer logo
    $wp_customize->add_setting(ns_.'logo_footer', array(
            'default' => esc_url(get_template_directory_uri()).'/images/logo_footer.png',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'logo_footer', array(
            'label' => 'Footer Logo',
            'section' => ns_.'theme_images_section',
            'priority' => 35,
    )));
    // favicon
    $wp_customize->add_setting(ns_.'favicon', array(
            'default' => esc_url(get_template_directory_uri()).'/images/favicon.png',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'favicon', array(
            'label' => 'Favicon',
            'section' => ns_.'theme_images_section',
            'priority' => 40,
    )));
    // default featured image
    $wp_customize->add_setting(ns_.'default_featured_image', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'default_featured_image', array(
            'label' => 'Default Featured Image',
            'section' => ns_.'theme_images_section',
            'priority' => 50,
    )));

    // Patterns
    $wp_customize->add_section(ns_.'pattern', array(
            'title' => __('Theme Patterns', ns_),
            'description' => 'Enter number of patterns. Click save and reload the page.',
            'priority' => 40,
    ));
    $wp_customize->add_setting(ns_.'patterns', array(
            'default' => 2,
            'sanitize_callback' => 'absint',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'patterns', array(
            'label' => __('Number of Patterns used in the theme', ns_),
            'section' => ns_.'pattern',
            'type' => 'text',
            'priority' => 10,
    ));
    $patterns = bb_get_theme_mod(ns_.'patterns',2);
    for ($i = 1; $i <= $patterns; $i++) {
        $wp_customize->add_setting(ns_.'pattern'.$i, array(
                'default' => '',
                'sanitize_callback' => 'esc_url_raw',
                'type' => 'option',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'pattern'.$i, array(
                'label' => ns_.'pattern'.$i,
                'section' => ns_.'pattern',
                'priority' => 50,
        )));
    }

    // Fonts
    $wp_customize->add_section(ns_.'fonts', array(
            'title' => __('Fonts', ns_),
            'priority' => 45,
    ));
    $wp_customize->add_setting(ns_.'font', array(
            'default' => 'Raleway,Open Sans',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'font', array(
            'label' => __('Fonts', ns_),
            'section' => ns_.'fonts',
            'type' => 'text',
            'priority' => 5,
    ));
    $wp_customize->add_setting(ns_.'gf', array(
            'default' => esc_url('//fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i|Raleway:400,400i,700,700i'),
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'gf', array(
            'label' => __('Google Fonts URL', ns_),
            'section' => ns_.'fonts',
            'type' => 'textarea',
            'priority' => 10,
    ));
//     $wp_customize->add_setting(ns_.'typekit', array(
//             'sanitize_callback' => 'sanitize_text_field',
//             'type' => 'option',
//     ));
//     $wp_customize->add_control(ns_.'typekit', array(
//             'label' => __('Adobe TypeKit ID', ns_),
//             'section' => ns_.'fonts',
//             'type' => 'text',
//             'priority' => 15,
//     ));

    // Palette
    $wp_customize->add_section(ns_.'palette', array(
            'title' => __('Theme Palette', ns_),
            'description' => 'Enter number of colours. Click save and reload the page.',
            'priority' => 50,
    ));
    $wp_customize->add_setting(ns_.'colours', array(
            'default' => BB_DEFAULT_COLOUR_COUNT,
            'sanitize_callback' => 'absint',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'colours', array(
            'label' => __('Number of colours in the palette', ns_),
            'section' => ns_.'palette',
            'type' => 'text',
            'priority' => 10,
    ));
    $colours = bb_get_theme_mod(ns_.'colours', BB_DEFAULT_COLOUR_COUNT);
    for ($i = 1; $i <= $colours; $i++) {
        $wp_customize->add_setting(ns_.'colour'.$i, array(
                'default' => '#FFFFFF',
                'sanitize_callback' => 'sanitize_hex_color',
                'type' => 'option',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, ns_.'colour'.$i, array(
                'label' => __('Colour ', ns_).$i,
                'description' => 'bg'.$i.', hbg'.$i.', text'.$i.', htext'.$i.', border'.$i.', hborder'.$i.'<br>bb_get_theme_mod(\''.ns_.'colour'.$i.'\');',
                'section' => ns_.'palette',
                'priority' => 10 + $i,
        )));
    }
    $wp_customize->add_setting(ns_.'gradient_start', array(
            'default' => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, ns_.'gradient_start', array(
            'label' => 'Gradient Start',
            'section' => ns_.'palette',
            'priority' => 20 + $i++,
    )));
    $wp_customize->add_setting(ns_.'gradient_end', array(
            'default' => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, ns_.'gradient_end', array(
            'label' => 'Gradient End',
            'section' => ns_.'palette',
            'priority' => 20 + $i++,
    )));
    $wp_customize->add_setting(ns_.'gradient_start_percent', array(
            'default' => '0%',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'gradient_start_percent', array(
            'label' => 'Gradient Start %',
            'section' => ns_.'palette',
            'priority' => 20 + $i++,
            'type' => 'text',
    ));
    $wp_customize->add_setting(ns_.'gradient_end_percent', array(
            'default' => '100%',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'gradient_end_percent', array(
            'label' => 'Gradient End %',
            'section' => ns_.'palette',
            'priority' => 20 + $i++,
            'type' => 'text',
    ));
    if (current_user_can('administrator')) {
        $wp_customize->add_setting(ns_.'colour_scheme_check', array(
                'type' => 'option',
                'capability' => 'administrator',
        ));
        $wp_customize->add_control(ns_.'colour_scheme_check', array(
                'label' => 'Enable Color Scheme?',
                'section' => ns_.'palette',
                'type' => 'checkbox',
                'priority' => 90,
        ));
    }

    // Colour Scheme
    if (true == bb_get_theme_mod(ns_.'colour_scheme_check')) {
        $wp_customize->add_section(ns_.'colour_scheme', array(
                'title' => __('Colour Scheme', ns_),
                'description' => 'Select which colour from the palette to use for each page element. You should generally configure the theme palette first.',
                'priority' => 51,
        ));
        $palette_options = array();
        for ($i = 1; $i <= $colours; $i++) {
            $palette_options[$i] = 'Colour '.$i.' ('.bb_get_theme_mod(ns_.'colour'.$i).')';
        }

        $elements = bb_get_page_elements();
        $e = 1;
        foreach ($elements as $element => $css_selectors) {
            $wp_customize->add_setting(ns_.'element_'.$element, array(
                    'default' => '1',
                    'sanitize_callback' => 'absint',
                    'type' => 'option',
            ));
            $wp_customize->add_control(ns_.'element_'.$element, array(
                    'label' => __(ucwords(str_replace('_', ' ', $element)), ns_),
                    'section' => ns_.'colour_scheme',
                    'type' => 'select',
                    'choices' => $palette_options,
                    'priority' => $e++,
            ));
        }
    }

    // Key Dimensions
    $wp_customize->add_section(ns_.'key_dimensions', array(
            'title' => __('Key Dimensions', ns_),
            'priority' => 52,
    ));
    $wp_customize->add_setting(ns_.'site_max_width', array(
            'default' => '130rem',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'site_max_width', array(
            'description' => 'Maximum width for the entire site. Highly recommended to be entered in rem.',
            'label' => 'Max Site Width',
            'section' => ns_.'key_dimensions',
            'type' => 'text',
            'priority' => 10,
    ));
    $wp_customize->add_setting(ns_.'row_max_width', array(
            'default' => '100rem',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'row_max_width', array(
            'description' => 'Maximum width for content rows. Highly recommended to be entered in rem.',
            'label' => 'Max Row Width',
            'section' => ns_.'key_dimensions',
            'type' => 'text',
            'priority' => 15,
    ));

    $pages = array('Home', 'Other');
    $sizes = array('Small', 'Medium', 'Large');
    $p = 20;
    foreach ($pages as $page) {
        foreach ($sizes as $size) {
            $setting_name = 'hero_height_'.strtolower($page.'_'.$size);
            $wp_customize->add_setting(ns_.$setting_name, array(
                    'default' => '500px',
                    'sanitize_callback' => 'sanitize_text_field',
                    'type' => 'option',
            ));
            $wp_customize->add_control(ns_.$setting_name, array(
                    'label' => __('Hero Height - '.$page.' ('.$size.')', ns_),
                    'section' => ns_.'key_dimensions',
                    'type' => 'text',
                    'priority' => $p++,
            ));
        }
    }

    // Heading Styles
    $wp_customize->add_section(ns_.'heading_styles', array(
            'title' => __('Custom Heading Styles', ns_),
            'priority' => 55,
    ));
    $wp_customize->add_setting(ns_.'h_base', array(
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'h_base', array(
            'label' => 'All Headings',
            'section' => ns_.'heading_styles',
            'type' => 'textarea',
            'priority' => 10,
    ));

    for ($i=1; $i <= 6 ; $i++) {
        $wp_customize->add_setting(ns_.'h'.$i, array(
                'type' => 'option',
        ));
        $wp_customize->add_control(ns_.'h'.$i, array(
                'label' => 'h'.$i,
                'section' => ns_.'heading_styles',
                'type' => 'textarea',
                'priority' => 10+$i,
        ));
    }

    // Contact Details
    $wp_customize->add_section(ns_.'contacts_section', array(
            'title' => __('Contact Details', ns_),
            'priority' => 60,
    ));
    $wp_customize->add_setting(ns_.'contact_email', array(
            'sanitize_callback' => 'sanitize_email',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'contact_email', array(
            'label' => 'Email',
            'section' => ns_.'contacts_section',
            'type' => 'text',
            'priority' => 10,
    ));
    $wp_customize->add_setting(ns_.'contact_phone', array(
            'sanitize_callback' => 'sanitize_text_field', // This will do for now
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'contact_phone', array(
            'label' => 'Phone Number',
            'section' => ns_.'contacts_section',
            'type' => 'text',
            'priority' => 20,
    ));

    // Announcement
    $wp_customize->add_section(ns_.'announcement_section', array(
            'title' => __('Announcement Details', ns_),
            'priority' => 60,
    ));
    $wp_customize->add_setting(ns_.'announcement_text', array(
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'announcement_text', array(
            'label' => 'Announcement Text',
            'section' => ns_.'announcement_section',
            'type' => 'text',
            'priority' => 10,
    ));
    $wp_customize->add_setting(ns_.'announcement_link', array(
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'announcement_link', array(
            'label' => 'Announcement Link',
            'section' => ns_.'announcement_section',
            'type' => 'text',
            'priority' => 20,
    ));

    // Copyright
    $wp_customize->add_section(ns_.'copyright_section', array(
            'title' => __('Copyright Statement', ns_),
            'priority' => 61,
    ));
    $wp_customize->add_setting(ns_.'copyright', array(
            'default' => '&copy; Copyright',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'copyright', array(
            'label' => 'Copyright Text',
            'section' => ns_.'copyright_section',
            'type' => 'text',
            'priority' => 30,
    ));
}

function bb_get_theme_mod($key, $default = '') {
    if (strpos($key, ns_) !== 0) {
        $key = ns_.$key;
    }
    $value = get_option($key);
    if (empty($value)) {
        $value = get_theme_mod($value);
    }
    if (empty($value)) {
        $value = $default;
    }
    return $value;
}

add_action('customize_save_after', 'bb_save_default_customizer_values');
function bb_save_default_customizer_values(WP_Customize_Manager $wp_customize) {
    $settings = $wp_customize->settings();
    $mods = get_theme_mods();
    foreach ($settings as $setting) {
        /** @var WP_Customize_Setting $setting */
        if ($setting->type == 'option') {
            add_option($setting->id, $setting->default);
        } elseif (!isset($mods[$setting->id])) {
            set_theme_mod($setting->id, $setting->default);
        }
    }
}

add_action('customize_save_after', 'bb_update_dynamic_styles');
function bb_update_dynamic_styles() {
    $result = false;
    $styles = bb_generate_dynamic_styles();
    require_once(ABSPATH.'wp-admin/includes/file.php');
    $access_type = get_filesystem_method();
    if ($access_type === 'direct') {
        $creds = request_filesystem_credentials(site_url().'/wp-admin/', '', false, get_stylesheet_directory().'/css/');

        if (WP_Filesystem($creds)) {
            /**
             * @var WP_Filesystem_Base $wp_filesystem
             */
            global $wp_filesystem;
            $result = $wp_filesystem->put_contents(get_stylesheet_directory().'/css/'.bb_get_dynamic_styles_filename(), $styles);
        }
    }

    // WP couldn't write to file automatically - we'll just do it directly rather than asking user for FTP details
    if (!$result) {
        $result = file_put_contents(get_stylesheet_directory().'/css/'.bb_get_dynamic_styles_filename(), $styles);
    }

    if ($result !== false) {
        BB_Transients::delete('css');
    }
}

function bb_get_dynamic_styles_filename() {
    $filename = 'dynamic.css';
    if (is_multisite()) {
        global $blog_id;
        $filename = 'dynamic.'.$blog_id.'.css';
    }
    return $filename;
}

function bb_get_page_elements() {
    return array(
            'content_background' => 'section.main-section, .callout-wrapper:nth-of-type(2n)',
            'heading_text' => 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6',
            'body_text' => 'body, *',
            'link_text' => 'a:link, a:link:hover, a:visited, a:link:focus',
            'main_menu_background' => 'nav.title-bar, nav.top-bar, nav.top-bar ul',
            'main_menu_text' => 'nav.title-bar .fa-bars, nav .menu > li > a, nav .menu > li > a:hover',
            'footer_background' => 'footer',
            'footer_text' => 'footer, footer *',
            'copyright_background' => 'body, footer#row-copyright',
            'copyright_text' => 'footer#row-copyright, footer#row-copyright *',
            'button_background' => 'button:not(.close-button), .button, a.button:link, a.button:visited, input[type=submit]',
            'button_text' => 'button:not(.close-button), .button, a.button:link, a.button:visited, input[type=submit]',
            'button_border' => 'button:not(.close-button), .button, a.button:link, a.button:visited, input[type=submit]',
            'button_hover_background' => 'button:not(.close-button):hover, button:not(.close-button):focus, .button:hover, .button:focus, a.button:link:hover, a.button:link:focus, a.button:visited:hover, a.button:visited:focus, input[type=submit]:hover, input[type=submit]:focus, .button.disabled:focus, .button.disabled:hover, .button[disabled]:focus, .button[disabled]:hover',
            'button_hover_text' => 'button:not(.close-button):hover, button:not(.close-button):focus, .button:hover, .button:focus, a.button:link:hover, a.button:link:focus, a.button:visited:hover, a.button:visited:focus, input[type=submit]:hover, input[type=submit]:focus, .button.disabled:focus, .button.disabled:hover, .button[disabled]:focus, .button[disabled]:hover',
            'button_hover_border' => 'button:not(.close-button):hover, button:not(.close-button):focus, .button:hover, .button:focus, a.button:link:hover, a.button:link:focus, a.button:visited:hover, a.button:visited:focus, input[type=submit]:hover, input[type=submit]:focus, .button.disabled:focus, .button.disabled:hover, .button[disabled]:focus, .button[disabled]:hover',
            'call_to_action_background' => '.cta, button.cta, .button.cta, a.button.cta, a.button.cta::after, .panel-wrapper .action-button a.button',
            'call_to_action_text' => '.cta, button.cta, .button.cta, a.button.cta, .panel-wrapper .action-button a.button',
            'call_to_action_border' => '.cta, button.cta, .button.cta, a.button.cta, .panel-wrapper .action-button a.button',
            'call_to_action_hover_background' => '.cta:hover, .cta:focus, button.cta:hover, button.cta:focus, .button.cta:hover, .button.cta:focus, a.button.cta:hover, a.button.cta:hover::after, a.button.cta:focus, a.button.cta:focus::after, .panel-wrapper .action-button a.button:hover, .panel-wrapper .action-button a.button:focus',
            'call_to_action_hover_text' => '.cta:hover, .cta:focus, button.cta:hover, button.cta:focus, .button.cta:hover, .button.cta:focus, a.button.cta:hover, a.button.cta:focus, .panel-wrapper .action-button a.button:hover, .panel-wrapper .action-button a.button:focus',
            'call_to_action_hover_border' => '.cta:hover, .cta:focus, button.cta:hover, button.cta:focus, .button.cta:hover, .button.cta:focus, a.button.cta:hover, a.button.cta:focus, .panel-wrapper .action-button a.button:hover, .panel-wrapper .action-button a.button:focus',
            'panel_background' => '.panel-wrapper',
            'panel_text' => '.panel-wrapper h1, .panel-wrapper h2, .panel-wrapper h3, .panel-wrapper h4, .panel-wrapper h5, .panel-wrapper h6',
            'hero_background' => '.hero',
            'hero_text' => '.hero h1, .hero .h1',
            'click_array_background' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper',
            'click_array_text' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper',
            'click_array_border' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper',
            'click_array_active_background' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-passive:hover, body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-active',
            'click_array_active_text' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-passive:hover div.s-html-value, body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-active',
            'click_array_active_border' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-passive:hover, body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-active',
    );
}

function bb_generate_dynamic_styles() {
    $styles = '';

    // Font styles
    // Depending on number of fonts, will produce something like ... body, *, h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .gf1 {font-family: "Raleway", sans-serif;}
    $font = bb_get_theme_mod(ns_.'font');
    if (!empty($font)) {
        $fonts = explode(',', $font);
        for ($i=1; $i <=6; $i++) {
            $heading_selectors .= 'body h'.$i.', body .h'.$i.', ';
        }
        for ($i = 0; $i < count($fonts); $i++) {
            if ($i == 0) {
                $styles .= 'body, *, ';
                if (count($fonts) == 1) {
                    $styles .= $heading_selectors;
                }
            } elseif ($i == 1) {
                $styles .= $heading_selectors;
            }
            $styles .= '.gf'.($i+1).' {font-family: "'.$fonts[$i].'", sans-serif;}'."\n";
        }
    }

    // Custom Heading Styles
    $h_base = bb_get_theme_mod('h_base');
    $styles .= substr($heading_selectors, 0, -2).' {'.$h_base.'}'."\n";
    for ($i=1; $i <= 6; $i++) {
        ${'h'.$i} = bb_get_theme_mod(ns_.'h'.$i);
        if (!empty(${'h'.$i})) {
            $styles .= 'body h'.$i.', body .h'.$i.' {'.${'h'.$i}.'}'."\n";
        }
    }

    // Set up theme palette variables
    $colour0 = 'transparent';
    $colour_count = bb_get_theme_mod(ns_.'colours', BB_DEFAULT_COLOUR_COUNT);
    for ($i = 1; $i <= $colour_count; $i++) {
        ${'colour'.$i} = bb_get_theme_mod(ns_.'colour'.$i);
    }

    // Apply theme palette to colour scheme
    if (true == bb_get_theme_mod(ns_.'colour_scheme_check')) {
        $elements = bb_get_page_elements();
        foreach ($elements as $element => $config) {
            if (is_array($config)) {
                $css_selectors = $config['selectors'];
            } else {
                $css_selectors = $config;
            }
            if (strpos($element, 'background') !== false) {
                $rule = 'background-color';
            } elseif (strpos($element, 'border') !== false) {
                $rule = 'border-color';
            } else {
                $rule = 'color';
            }
            $palette_colour = bb_get_theme_mod(ns_.'element_'.$element);
            $element_colour = ${'colour'.$palette_colour};
            $styles .= $css_selectors.' {'.$rule.': '.$element_colour.';}'."\n";
        }
    }

    // Helper classes for text, background and border colours
    for ($i = 0; $i <= $colour_count; $i++) {
        $styles .= '.text'.$i.', .panel-wrapper.text'.$i.' * {color: '.${'colour'.$i}.';}'."\n";
        $styles .= '.bg'.$i.' {background-color: '.${'colour'.$i}.';}'."\n";
        $styles .= '.border'.$i.' {border-color: '.${'colour'.$i}.';}'."\n";
        $styles .= '.htext'.$i.':hover, .panel-wrapper.text'.$i.':hover * {color: '.${'colour'.$i}.';}'."\n";
        $styles .= '.hbg'.$i.':hover {background-color: '.${'colour'.$i}.';} '."\n";
        $styles .= '.hborder'.$i.':hover {border-color: '.${'colour'.$i}.';}'."\n";
    }

    // Gradients by http://www.colorzilla.com/gradient-editor/
    $gradient = array();
    $gradient['start'] = 'rgba('.bb_convert_colour( bb_get_theme_mod( ns_.'gradient_start' ) ).',1)';
    $gradient['end'] = 'rgba('.bb_convert_colour( bb_get_theme_mod( ns_.'gradient_end' ) ).',1)';
    $gradient['start_percent'] = bb_get_theme_mod( ns_.'gradient_start_percent' );
    $gradient['end_percent'] = bb_get_theme_mod( ns_.'gradient_end_percent' );

    // pick your direction
    // $direction = 'top'; $direction2 = 'to bottom';
    // $direction = '-45deg'; $direction2 = '135deg';
    // $direction = '45deg'; $direction2 = '45deg';
    $direction = 'left'; $direction2 = 'to right';

    $styles .= '.gradient {
background: -moz-linear-gradient('.$direction.', '.$gradient['start'].' '.$gradient['start_percent'].' '.$gradient['end'].' '.$gradient['end_percent'].'); /* FF3.6-15 */
background: -webkit-linear-gradient('.$direction.', '.$gradient['start'].' '.$gradient['start_percent'].', '.$gradient['end'].' '.$gradient['end_percent'].'); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient('.$direction2.', '.$gradient['start'].' '.$gradient['start_percent'].', '.$gradient['end'].' '.$gradient['end_percent'].'); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$colour1.'", endColorstr="'.$colour1.'",GradientType=1 ); /* IE6-9 */
}'."\n";

    // Key dimensions
    $row_max_width = bb_get_theme_mod(ns_.'row_max_width');
    $site_max_width = bb_get_theme_mod(ns_.'site_max_width');
    $pages = array('Home', 'Other');
    $sizes = array('Small', 'Medium', 'Large');
    foreach ($pages as $page) {
        foreach ($sizes as $size) {
            $setting_name = 'hero_height_'.strtolower($page.'_'.$size);
            $$setting_name = bb_get_theme_mod($setting_name);
        }
    }

    // Custom styles including key dimensions
    $styles .= <<<EOS
.row {max-width: $row_max_width;}
.everything {max-width: $site_max_width;}
.hero-height {min-height: $hero_height_other_small; overflow: hidden;}
.home .hero-height {min-height: $hero_height_home_small;}

@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    .hero-height {min-height: $hero_height_other_medium; overflow: hidden;}
    .home .hero-height {min-height: $hero_height_home_medium;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    .hero-height {min-height: $hero_height_other_large; overflow: hidden;}
    .home .hero-height {min-height: $hero_height_home_large;}
}
@media only screen and (min-width: $row_max_width) {
}
@media only screen and (min-width: $site_max_width) {
}
EOS;
    return $styles;
}

// Hack to load dynamic styles in head while in Customizer, so that changes show up on save without having to reload the page.
global $wp_customize;
if (isset($wp_customize)) {
    function load_customizer_css() {
        $styles = bb_generate_dynamic_styles();
?>
<style type="text/css">
    <?php echo $styles; ?>
</style>
<?php
    }
    add_action('wp_head', 'load_customizer_css');
}
