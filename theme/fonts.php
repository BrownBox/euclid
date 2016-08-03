<?php
add_action('admin_menu', array( 'bb_fonts', 'setup' ) );
class bb_fonts {
    static function setup() {
        add_theme_page( 'Fonts', 'Fonts', 'manage_options' , ns_.'_fonts', array( 'bb_fonts','settings') );
    	add_action( 'admin_init', array( 'bb_fonts','register') );
    }

    static function register() {
        register_setting( 'theme_fonts_group', 'theme_fonts');
    }

    static function settings() {
        $theme_fonts = get_option('theme_fonts');
        if( !is_array( $theme_fonts ) ) $theme_fonts = array();

        echo '<div class="wrap">'."\n";
        echo '  <form method="post" action="options.php">'."\n";
        echo '      <h2>Google Fonts</h2>'."\n".'      <hr style="margin: 20px 0;border: 0; height: 1px; background:#ddd; "/>'."\n";
        echo '      <a style="font-size:0.8em;position: relative;top: -15px;" href="https://www.google.com/fonts">https://www.google.com/fonts</a>'."\n";
        bb_theme::field(array('title' => 'Primary Font', 'group' => 'theme_fonts', 'name' => ns_.'gf1', 'type' => 'text', 'size' => '100%', 'max_width' => '600px', 'placeholder' => 'http://...', 'default' => 'http://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,800,700,900'));
        bb_theme::field(array('title' => 'Secondary Font', 'group' => 'theme_fonts', 'name' => ns_.'gf2', 'type' => 'text', 'size' => '100%', 'max_width' => '600px', 'placeholder' => 'http://...', 'default' => 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic'));
        bb_theme::field(array('title' => 'Tertiary Font', 'group' => 'theme_fonts', 'name' => ns_.'gf3', 'type' => 'text', 'size' => '100%', 'max_width' => '600px', 'placeholder' => 'http://...'));

        echo '      <h2>Adobe TypeKit</h2>'."\n".'      <hr style="margin: 20px 0;border: 0; height: 1px; background:#ddd; "/>'."\n";
        echo '      <a style="font-size:0.8em;position: relative;top: -15px;" href="https://typekit.com/">https://typekit.com/</a>'."\n";
        bb_theme::field(array('title' => 'Kit ID', 'group' => 'theme_fonts', 'name' => ns_.'typekit', 'type' => 'text', 'size' => '100%', 'max_width' => '600px'));

        submit_button();
        settings_fields('theme_fonts_group');

        echo '  </form>'."\n";
        echo '</div>'."\n";
    }
}
