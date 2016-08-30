<?php
define('BB_DEFAULT_COLOUR_COUNT', 8);

add_action('customize_register', 'bb_theme_customizer');
function bb_theme_customizer(WP_Customize_Manager $wp_customize) {
    // Key Images (Desktop Logo, Mobile Logo and Favicon)
    $wp_customize->add_section(ns_.'theme_images_section', array(
            'title' => __('Logos', ns_),
            'priority' => 30,
    ));
    // large screen
    $wp_customize->add_setting(ns_.'logo_large', array(
            'default' => esc_url(get_template_directory_uri()).'/images/logo_large.png',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'logo_large', array(
            'label' => ns_.'logo_large',
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
            'label' => ns_.'logo_medium',
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
            'label' => ns_.'logo_small',
            'section' => ns_.'theme_images_section',
            'priority' => 30,
    )));
    // favicon
    $wp_customize->add_setting(ns_.'favicon', array(
            'default' => esc_url(get_template_directory_uri()).'/images/favicon.png',
            'sanitize_callback' => 'esc_url_raw',
            'type' => 'option',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'favicon', array(
            'label' => ns_.'favicon',
            'section' => ns_.'theme_images_section',
            'priority' => 40,
    )));

    // Fonts
    $wp_customize->add_section(ns_.'fonts', array(
            'title' => __('Fonts', ns_),
            'priority' => 45,
    ));
    $wp_customize->add_setting(ns_.'font', array(
            'default' => 'Raleway',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'font', array(
            'label' => __('Primary Font', ns_),
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
            'type' => 'text',
            'priority' => 10,
    ));
    $wp_customize->add_setting(ns_.'typekit', array(
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'typekit', array(
            'label' => __('Adobe TypeKit ID', ns_),
            'section' => ns_.'fonts',
            'type' => 'text',
            'priority' => 15,
    ));

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
                'label' => __(ns_.'colour', ns_).$i,
                'section' => ns_.'palette',
                'priority' => 10 + $i,
        )));
    }

    // Colour Scheme
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

    // Key Dimensions
    $wp_customize->add_section(ns_.'key_dimensions', array(
            'title' => __('Key Dimensions', ns_),
            'priority' => 52,
    ));
    $wp_customize->add_setting(ns_.'row_max_width', array(
            'default' => '100rem',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'row_max_width', array(
            'description' => 'Maximum width for content rows. Highly recommended to be entered in rem.',
            'label' => ns_.'row_max_width',
            'section' => ns_.'key_dimensions',
            'type' => 'text',
            'priority' => 10,
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
            'label' => ns_.'contact_email',
            'section' => ns_.'contacts_section',
            'type' => 'text',
            'priority' => 10,
    ));
    $wp_customize->add_setting(ns_.'contact_phone', array(
            'sanitize_callback' => 'sanitize_text_field', // This will do for now
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'contact_phone', array(
            'label' => ns_.'contact_phone',
            'section' => ns_.'contacts_section',
            'type' => 'text',
            'priority' => 20,
    ));

    // Copyright
    $wp_customize->add_section(ns_.'copyright_section', array(
            'title' => __('Copyright Statement', ns_),
            'priority' => 61,
    ));
    $wp_customize->add_setting(ns_.'copyright', array(
            'default' => 'Default copyright text',
            'sanitize_callback' => 'sanitize_text_field',
            'type' => 'option',
    ));
    $wp_customize->add_control(ns_.'copyright', array(
            'label' => ns_.'copyright',
            'section' => ns_.'copyright_section',
            'type' => 'text',
            'priority' => 30,
    ));
}
add_action('customize_save_after', 'bb_update_dynamic_styles');

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

function bb_update_dynamic_styles() {
    $styles = bb_generate_dynamic_styles();
    file_put_contents(get_stylesheet_directory().'/css/'.bb_get_dynamic_styles_filename(), $styles);
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
            'heading_text' => 'h1, h2, h3, h4, h5, h6',
            'body_text' => 'body, *',
            'link_text' => 'a:link, a:link:hover, a:visited, a:link:focus',
            'main_menu_background' => 'nav.title-bar, nav.top-bar, nav.top-bar ul',
            'main_menu_text' => 'nav.title-bar .fa-bars, nav .menu > li > a, nav .menu > li > a:hover',
            'footer_background' => 'body, footer',
            'footer_text' => 'footer, footer *',
            'button_background' => 'button, .button, a.button:link, a.button:visited, input[type=submit]',
            'button_text' => 'button, .button, a.button:link, a.button:visited, input[type=submit]',
            'button_hover_background' => 'button:hover, button:focus, .button:hover, .button:focus, a.button:link:hover, a.button:link:focus, a.button:visited:hover, a.button:visited:focus, input[type=submit]:hover, input[type=submit]:focus, .button.disabled:focus, .button.disabled:hover, .button[disabled]:focus, .button[disabled]:hover',
            'button_hover_text' => 'button:hover, button:focus, .button:hover, .button:focus, a.button:link:hover, a.button:link:focus, a.button:visited:hover, a.button:visited:focus, input[type=submit]:hover, input[type=submit]:focus, .button.disabled:focus, .button.disabled:hover, .button[disabled]:focus, .button[disabled]:hover',
            'call_to_action_background' => '.cta, button.cta, .button.cta, a.button.cta',
            'call_to_action_text' => '.cta, button.cta, .button.cta, a.button.cta',
            'call_to_action_hover_background' => '.cta:hover, .cta:focus, button.cta:hover, button.cta:focus, .button.cta:hover, .button.cta:focus, a.button.cta:hover, a.button.cta:focus',
            'call_to_action_hover_text' => '.cta:hover, .cta:focus, button.cta:hover, button.cta:focus, .button.cta:hover, .button.cta:focus, a.button.cta:hover, a.button.cta:focus',
            'panel_background' => '.panel-wrapper',
            'panel_text' => '.panel-wrapper h1, .panel-wrapper h2, .panel-wrapper h3, .panel-wrapper h4, .panel-wrapper h5, .panel-wrapper h6',
            'hero_background' => '.hero',
            'hero_text' => '.hero h1',
            'click_array_background' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper',
            'click_array_text' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper',
            'click_array_active_background' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-passive:hover, body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-active',
            'click_array_active_text' => 'body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-passive:hover div.s-html-value, body .gform_wrapper .gform_bb.gfield_click_array div.s-html-wrapper.s-active',
    );
}

function bb_generate_dynamic_styles() {
    $font = bb_get_theme_mod(ns_.'font');
    $styles = 'body, h1, h2, h3, h4, h5, h6 {font-family: "'.$font.'", sans-serif;}'."\n";
    $colour_count = bb_get_theme_mod(ns_.'colours', BB_DEFAULT_COLOUR_COUNT);
    for ($i = 1; $i <= $colour_count; $i++) {
        ${'colour'.$i} = bb_get_theme_mod(ns_.'colour'.$i);
        $styles .= '.text'.$i.' {color: '.${'colour'.$i}.';}'."\n";
        $styles .= '.bg'.$i.' {background-color: '.${'colour'.$i}.';}'."\n";
        $styles .= '.htext'.$i.':hover {color: '.${'colour'.$i}.';}'."\n";
        $styles .= '.hbg'.$i.':hover {background-color: '.${'colour'.$i}.';} '."\n";
    }
    $colour = $colour1; // Fallback default

    $elements = bb_get_page_elements();
    foreach ($elements as $element => $css_selectors) {
        $rule = strpos($element, 'background') !== false ? 'background-color' : 'color';
        $palette_colour = bb_get_theme_mod(ns_.'element_'.$element);
        $element_colour = ${'colour'.$palette_colour};
        $styles .= $css_selectors.' {'.$rule.': '.$element_colour.';}'."\n";
    }

    $row_max_width = bb_get_theme_mod(ns_.'row_max_width');
    $pages = array('Home', 'Other');
    $sizes = array('Small', 'Medium', 'Large');
    foreach ($pages as $page) {
        foreach ($sizes as $size) {
            $setting_name = 'hero_height_'.strtolower($page.'_'.$size);
            $$setting_name = bb_get_theme_mod($setting_name);
        }
    }

    $styles .= <<<EOS
.row {max-width: $row_max_width;}
div.hero {height: $hero_height_other_small;}
body.home div.hero {height: $hero_height_home_small;}

@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    div.hero {height: $hero_height_other_medium;}
    body.home div.hero {height: $hero_height_home_medium;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    div.hero {height: $hero_height_other_large;}
    body.home div.hero {height: $hero_height_home_large;}
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
