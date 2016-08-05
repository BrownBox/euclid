<?php
// Enable featured images
add_theme_support('post-thumbnails');

// Enable RSS support
add_theme_support('automatic-feed-links');

// Automatically include title tag
add_theme_support('title-tag');

// Add filters
add_filter('wp_title', array('bb_theme', 'title'), 10, 2);
add_filter('template_include', array('bb_theme', 'template_name'), 9999);

// Add actions
add_action('admin_bar_menu', array('bb_theme', 'custom_adminbar'), 999);
add_action('widgets_init', array('bb_theme', 'register_widgets'));
add_action('admin_init', array('bb_theme', 'imagelink_default'));

// Shortcodes
add_shortcode('list_posts', array('bb_theme', 'list_posts'));

// The master class
class bb_theme {
    static function section($args) {
        is_array($args) ? extract($args) : parse_str($args);

        // check for required variables
        if (!isset($name) || !$file) {
            return;
        }
        if (!isset($inner_class)) {
            $inner_class = 'full-row'; // other options include 'row'
        }
        if (!isset($type)) {
            $type = 'div'; // other options include 'section', 'footer', etc...
        }
        if (!isset($class)) {
            $class = 'no-class'; // other options include 'section', 'footer', etc...
        }
        if (!isset($dir)) {
            $dir = 'sections';
        }

        // setup the wrapper
        echo "\n" . '<!-- ' . $name . ' -->' . "\n";
        echo "\n" . '<!-- ' . $file . ' -->' . "\n";
        echo '<' . $type . ' id="row-' . $name . '" class="row-wrapper ' . $class . '">' . "\n";
        echo ' <div id="row-inner-' . $name . '" class="row-inner-wrapper ' . $inner_class . '">' . "\n";

        locate_template(array($dir . '/' . $file), true);

        echo ' </div>' . "\n";
        echo '</' . $type . '>' . "\n";
        echo '<!-- end ' . $name . ' -->' . "\n";
    }

    static function list_posts($args) {
        is_array($args) ? extract($args) : parse_str($args);

//         if (!isset($layout)) {
            $layout = 'default';
//         }
        if (!isset($outer_element)) {
            $outer_element = 'ul';
        }
        if (!isset($inner_element)) {
            $inner_element = 'li';
        }
        if (!isset($type)) {
            $type = 'post'; // any valid post type
        }

        if (post_type_exists($type)) {
            $args = array(
                    'post_type' => $type,
                    'posts_per_page' => -1,
            );
            $posts = get_posts($args);
            if (count($posts) > 0) {
                switch ($layout) {
                    case 'accordion': // @todo
                        break;
                    case 'tabs': // @todo
                        break;
                    default:
                        $no_images = array();

                        // Primary items (ones with a featured image)
                        $final_content .= '<'.$outer_element.' class="bb_posts_wrapper row small-up-1 medium-up-2 large-up-3">'."\n";
                        foreach ($posts as $item) {
                            if (!has_post_thumbnail($item->ID)) {
                                $no_images[] = $item;
                                continue;
                            }
                            $final_content .= '  <'.$inner_element.' class="bb_posts_item column">'."\n";
                            if (!empty($item->post_content)) {
                                $final_content .= '    <a href="'.get_the_permalink($item).'">'."\n";
                            }
                            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID), 'full');
                            $final_content .= '      <img src="'.$image_data[0].'">'."\n";
                            $final_content .= $item->post_title."\n";
                            if (!empty($item->post_content)) {
                                $final_content .= '    </a>';
                            }
                            $final_content .= '  </'.$inner_element.'>'."\n";
                        }
                        $final_content .= '</'.$outer_element.'>'."\n";

                        // Secondary items (no image)
                        if (count($no_images) > 0) {
                            $final_content .= '<'.$outer_element.' class="bb_posts_subwrapper">'."\n";
                            foreach ($no_images as $item) {
                                $final_content .= '  <'.$inner_element.' class="bb_posts_subitem">'."\n";
                                if (!empty($item->post_content)) {
                                    $final_content .= '    <a href="'.get_the_permalink($item).'">'."\n";
                                }
                                $final_content .= $item->post_title."\n";
                                if (!empty($item->post_content)) {
                                    $final_content .= '    </a>';
                                }
                                $final_content .= '  </'.$inner_element.'>'."\n";
                            }
                            $final_content .= '</'.$outer_element.'>'."\n";
                        }
                        break;
                }
            }
        }
        return $final_content;
    }

    /**
     * Generate series of helper CSS classes for the page wrapper based on the page content
     * @param string $classes Custom classes to include
     * @param boolean $post_atts Whether to include post slug and ID classes
     * @return string
     * @deprecated Use body_class() or post_class() instead
     */
    static function classes($classes, $post_atts = true) {
        global $post;
        $class = array();

        $class[] = $args;
        $class[] = (is_archive())       ? 'archive' : 'not-archvie';
        $class[] = (is_attachment())    ? 'attachment' : 'not-attachment';
        $class[] = (is_front_page())    ? 'frontpage' : 'not-frontpage';
        $class[] = (is_home())          ? 'home' : 'not-home';
        $class[] = (is_page())          ? 'page' : 'not-page';
        $class[] = (is_search())        ? 'search' : 'not-search';
        $class[] = (is_single())        ? 'single' : 'not-single';
        $class[] = (is_sticky())        ? 'sticky' : 'not-sticky';
        $class[] = (is_tax())           ? 'tax' : 'not-tax';
        if ($post_atts == true) {
            $class[] = $post->post_name;
            $class[] = 'post-'.$post->ID;
        }

        $class = implode(' ', $class);
        return $class;
    }

    /**
     * Generates a Zurb Interchange HTML element
     * @see http://foundation.zurb.com/sites/docs/interchange.html
     * @param array|string $args
     * @param boolean $echo
     * @return void|string
     */
    static function interchange($args, $echo = true) {
        is_array($args) ? extract($args) : parse_str($args);
        if (!$small || !$medium || !$large ) {
            return;
        }
        if (empty($element)) {
            $element = 'img';
        }

        $html = '<'.$element.' data-interchange="['.$small.', small], ['.$medium.', medium], ['.$large.', large]" src="'.$large.'"';
        if (!empty($attrs)) {
            if (is_array($attrs)) {
                $attr_string = '';
                foreach ($attrs as $attr => $value) {
                    $attr_string .= ' '.$attr.'="'.$value.'"';
                }
            } else {
                $attr_string = $attrs;
            }
            $html .= ' '.$attr_string;
        }
        $html .= '>';
        if ($echo) {
            echo $html."\n";
        } else {
            return $html;
        }
    }

    /**
     * Generates an image element with srcset attribute
     * @param array|string $args {
     *     @type string     $src    Primary/fallback image source. Required.
     *     @type string     $s      Image source for small screens. Defaults to value of $src.
     *     @type string     $m      Image source for medium screens. Defaults to value of $src.
     *     @type string     $l      Image source for large screens. Defaults to value of $src.
     *     @type boolean    $echo   Whether to echo the result. Default true.
     *     @type string     $id     Value of id attribute. Default is a randomly generated string.
     *     @type string     $alt    Value of alt attribute. Default empty.
     * }
     * @return string HTML image element if $echo is false, else null.
     */
    static function srcset($args) {
        $defaults = array(
                'id' => wp_generate_password(8, false),
                'alt' => '',
                'echo' => true,
        );
        $args = wp_parse_args($args, $defaults);

        if (empty($args['s'])) {
            $args['s'] = $args['src'];
        }
        if (empty($args['m'])) {
            $args['m'] = $args['src'];
        }
        if (empty($args['l'])) {
            $args['l'] = $args['src'];
        }

        $img = '<img id='.$args['id'].' src="'.$args['src'].'" srcset="'.$args['s'].' 639w, '.$args['m'].' 1024w, '.$args['l'].' 1600w" alt="'.$args['alt'].'">';
        if ($echo) {
            echo $img;
        } else {
            return $img;
        }
    }

    static function onclick($args) {
        $defaults = array(
                'echo' => true,
        );
        extract(wp_parse_args($args, $defaults));

        $location = empty($target) ? "location.href='$url';" : "window.open('$url','$target');";
        if ($echo) {
            echo $location;
        } else {
            return $location;
        }
    }

    static function field($args) {
        // @todo replace with ACF
        is_array($args) ? extract($args) : parse_str($args);
        // $args example: "url="http://techn.com.au&target=_blank&output=echo"

        //set defaults
        if (!$title && !$field_name)
            return;
        if (!$title && $field_name)
            $title = $field_name;
        $title = ucfirst(strtolower($title));
        if (!$field_name && $title)
            $field_name = $title;
        $field_name = strtolower(str_replace(' ', '_', $field_name));
        if (!$size)
            $size = '100%'; // accepts valid css for all types expect textarea. Text array expects an array where [0] is width and [1] is height
        if (!$max_width)
            $max_width = '100%';
        if (!$type)
            $type = 'text'; // accepts 'text', 'textarea', 'checkox', 'select'

        $script = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1);
        $source = ($script == 'post.php' || $script == 'post-new.php') ? 'meta' : 'option';
        if (!$source == 'option' && !$group)
            return;
        $field_name = ($source == 'option') ? $group . "[" . $name . "]" : $field_name;

        echo ' <div style="display:block;width:100%;padding-bottom:5px;">' . "\n";
        switch ($type) {
            case 'checkbox':
                $checked = ($source == 'meta' && get_post_meta($_GET[post], $field_name, true) == 'true') ? 'checked="checked"' : '';
                if ($source == 'option') {
                    $option = get_option($group);
                    $value = $option[$name];
                    $checked = ($value == 'true') ? 'checked="checked"' : '';
                }
                echo '   <input type="checkbox" name="' . $field_name . '" value="true" ' . $checked . ' style="margin: 0 5px 0px 0;"/><label style="color:rgba(0,0,0,0.75);">' . $title . '</label>' . "\n";
                break;

            case 'textarea':
                if ($source == 'meta')
                    $value = get_post_meta($_GET[post], $field_name, true);
                if ($source == 'option') {
                    $option = get_option($group);
                    $value = $option[$name];
                }
                if ($default && !$value)
                    $value = $default;
                echo '    <label for="' . $field_name . '">' . "\n";
                echo '       <sub style="color:rgba(0,0,0,0.75);display:block;">' . $title . '</sub>' . "\n";
                echo '   </label>' . "\n";
                if (!is_array($size))
                    $size = explode(',', $size);
                $style = 'width:' . $size[0] . ';height:' . $size[1] . ';max-width:' . $max_width . ';';
                echo '   <textarea id="' . $field_name . '" name="' . $field_name . '" style="' . $style . ';" placeholder="' . $placeholder . '" >' . esc_attr($value) . '</textarea>' . "\n";
                break;

            case 'select':

                // expects an $options array of arrays as follows
                // $options = array (
                //        array ( 'label' => 'aaa', 'value' => '1' ),
                //        array ( 'label' => 'aaa', 'value' => '1' ),
                //        );
                $current = get_post_meta($_GET[post], $field_name, true);
                echo '    <sub style="color:rgba(0,0,0,0.75);display:block;width:100%;max-width:' . $max_width . ';">' . $title . '</sub>' . "\n";
                echo '      <select name="' . $field_name . '" id="' . $field_name . '">' . "\n";
                foreach ($options as $option)
                    echo '        <option value="' . $option['value'] . '" ' . selected($option['value'], $current, false) . '>' . $option['label'] . '</option>' . "\n";
                echo '    </select>' . "\n";
                break;

            case 'color-picker':
                echo '    <label for="meta-color" class="prfx-row-title" style="display:block;width:100%;max-width:' . $max_width . ';">' . $title . '</label>' . "\n";
                echo '    <input name="' . $field_name . '" type="text" value="' . get_post_meta($_GET[post], $field_name, true) . '" class="meta-color" />' . "\n";
                break;

            case 'wp-editor':
                if ($source == 'meta')
                    $value = get_post_meta($_GET[post], $field_name, true);
                if ($source == 'option') {
                    $option = get_option($group);
                    $value = $option[$name];
                }
                if ($default && !$value)
                    $value = $default;
                wp_editor($value, $field_name, $settings);
                break;

            case 'text':
            default:
                if ($source == 'meta')
                    $value = get_post_meta($_GET[post], $field_name, true);
                if ($source == 'option') {
                    $option = get_option($group);
                    $value = $option[$name];
                }
                if ($default && !$value)
                    $value = $default;
                echo '    <label for="' . $field_name . '">' . "\n";
                echo '        <sub style="color:rgba(0,0,0,0.75);display:block;">' . $title . '</sub>' . "\n";
                echo '    </label>' . "\n";
                echo '   <input type="' . $type . '" id="' . $field_name . '" name="' . $field_name . '" style="display:block;max-width:' . $max_width . ';width:' . $size . ';" placeholder="' . $placeholder . '" value="' . esc_attr($value) . '" />' . "\n";
                break;
        }
        if ($description)
            echo '   <div style="position:relative;top:-3px;display:block;width:100%;color:#ddd;font-size:0.8em;">' . $description . '</div>' . "\n";
        echo ' </div>' . "\n";
        return $field_name;
    }

    static function title($title, $sep) {
        global $paged, $page;
        if (is_feed()) {
            return $title;
        }

        // Add the site name.
        $title .= get_bloginfo('name');

        // Add the site description for the home/front page.
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            $title = "$title $sep $site_description";
        }
        // Add a page number if necessary.
        if ($paged >= 2 || $page >= 2) {
            $title = "$title $sep " . sprintf(__('Page %s', THEME_TEXTDOMAIN), max($paged, $page));
        }
        return $title;
    }

    static function custom_adminbar($wp_admin_bar) {
        // Environment indicator
        if (!defined('WP_ENV')) {
            define('WP_BB_ENV', 'DEVELOPMENT');
        }

        switch (strtoupper(WP_BB_ENV)) {
            case 'PRODUCTION':
                $class = 'prod';
                break;
            case 'STAGING':
                $class = 'stage';
                break;
            case 'DEVELOPMENT':
            default:
                $class = 'dev';
                break;
        }

        $args = array(
                'id' => 'bb-env',
                'title' => strtoupper(WP_BB_ENV),
                'meta' => array(
                        'class' => 'bb '.$class,
                ),
        );
        $wp_admin_bar->add_node($args);

        // State indicator
        if (!defined('WP_BB_STATE')) {
            define('WP_BB_STATE', 'WIP');
        }
        switch (strtoupper(WP_BB_STATE)) {
            case 'STABLE':
                $class = 'stable';
                break;
            case 'WIP':
                $class = 'wip';
                break;
            case 'BROKEN':
            default:
                $class = 'broken';
                break;
        }

        $args = array(
                'id' => 'bb-state',
                'title' => strtoupper(WP_BB_STATE),
                'meta' => array(
                        'class' => 'bb '.$class,
                ),
        );
        $wp_admin_bar->add_node($args);

        $args = array(
                'id' => 'bb-css',
                'title' => 'CSS',
                'href' => '?css=refresh',
                'meta' => array(
                        'class' => 'bb css',
                ),
        );
        $wp_admin_bar->add_node($args);
        if ($_GET['css'] == 'refresh') {
            bb_update_dynamic_styles();
        }
    }

    static function register_widgets() {
//         register_sidebar(array(
//                 'name' => 'Example Widget',
//                 'id' => 'example_widget',
//                 'before_widget' => '',
//                 'after_widget' => '',
//                 'before_title' => '<h1>',
//                 'after_title' => '</h1>',
//         ));
    }

    static function template_name($t) {
        if (current_user_can('manage_options')) {
            $template_name = get_page_template_slug(get_queried_object_id());
            if (empty($template_name)) {
                $template_name = '(default)';
            }
            $template_name = basename($t).' > '.$template_name;
            add_action('wp_footer', function($arg) use ($template_name) {echo '<div id="template-name">'.$template_name.'</div>'."\n";});
        }
        return $t;
    }

    static function imagelink_default() {
        if (get_option('image_default_link_type') !== 'none') {
            update_option('image_default_link_type', 'none');
        }
    }
}
