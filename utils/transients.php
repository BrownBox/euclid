<?php
/**
 * Simplifying working with multiple transients
 * @version 1.0.1
 * @author Chris Chatterton <chris@brownbox.net.au> - Base functionality
 * @author Mark Parnell <markparnell@brownbox.net.au> - Class wrapper and auto-refresh functionality
 */
class BB_Transients {
    /**
     * Constructor. Sets up necessary hooks etc.
     */
    public function __construct() {
        add_action('save_post', array($this, 'save_post'), 10, 3);
        add_action('delete_post', array($this, 'delete_post'), 10, 1);
        add_action('wp_update_nav_menu', array($this, 'wp_update_nav_menu'), 10, 2);
    }

    /**
     * @param array $args {
     *     Optional. Arguments used to find matching transients. If empty, will return all transients.
     *
     *     @type string     $string     Search term to match transients against.
     * }
     * @return array The matching transients.
     */
    public static function get($args) {
        is_array($args) ? extract($args) : parse_str($args);
        if (!isset($string)) {
            $string = (is_string($args)) ? $args : "";
        }

        // Test for required variables and set defaults
        $matching = array();

        global $wpdb;
        $sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
        FROM $wpdb->options
        WHERE `option_name` LIKE '%transient_%'
        ORDER BY `option_name`";

        $transients = $wpdb->get_results($sql);

        // filter by $string (if provided)
        foreach ($transients as $transient) {
            if (empty($string) || false !== strpos($transient->name, $string)) {
                $matching[] = $transient;
            }
        }
        $transients = $matching;

        // return results
        return $transients;
    }

    /**
     * @param array $args {
     *     Optional. Arguments used to define which transients to delete.
     *
     *     @type string     $string     Search term to match transients against. If both $string and $transients are empty, will delete all transients.
     *     @type array      $transients List of transients to delete. Each item must be an object with at least a name property pertaining to the name of the transient.
     *                                  If both $string and $transients are empty, will delete all transients.
     * }
     * @return array List of deleted transients
     */
    public static function delete($args) {
        is_array($args) ? extract($args) : parse_str($args);
        $matching = array();

        // Test for required variables and set defaults
        if (!isset($string)) {
            $string = (is_string($args)) ? $args : "";
        }
        if (!is_array($transients)) {
            $transients = self::get($args);
        }

        // loop through and delete matching transients
        foreach ($transients as $transient) {
            if (empty($string) || false !== strpos($transient->name, $string)) {
                $matching[] = str_replace('_transient_', '', $transient->name);
                delete_transient(str_replace('_transient_', '', $transient->name));
            }
        }

        // return results
        $matching = implode(', ', $matching);
        return $matching;
    }

    /**
     *
     * @param array $args {
     *     Optional. Arguments used to define cleaning behaviour.
     *
     *     @type string     $string     Search term to match transients against.
     *     @type array      $transients List of transients to clean. Each item must be an object with at least name and value properties pertaining to the name and value of the transient respectively.
     *                                  If both $string and $transients are empty, will clean all transients.
     *     @type string|array $clean    Values to remove from matching transients
     * }
     * @return array List of cleaned transient values
     */
    public static function clean($args) {
        is_array($args) ? extract($args) : parse_str($args);

        // Test for required variables and set defaults
        if (!isset($string)) {
            $string = "";
        }
        if (!is_array($transients)) {
            $transients = self::get($string);
        }
        echo '<!-- ' . count($transients) . ' transients cleaned -->' . "\n";

        if (!isset($clean)) {
            return $transients;
        } else {
            $clean = (is_string($clean)) ? array($clean) : $clean;
        }

        $results = array();

        foreach ($transients as $transient) {
            echo '<!-- ' . $transient->name . ' -->' . "\n";
            if (strpos($transient->name, $string) && false === strpos($transient->name, 'timeout')) {
                foreach ($clean as $value) {
                    $transient->value = str_replace($value, "  ", $transient->value);
                }
                $results[] = $transient->value;
            }
        }
        $results = implode("  ", $results);
        return $results;
    }

    /**
     * Fires on save_post hook to clear all transients associated with that post
     * @param integer $post_id
     * @param WP_Post $post
     * @param boolean $update
     */
    public function save_post($post_id, WP_Post $post, $update) {
        // No point in doing anything if it's new
        if ($update) {
            $this->clear_post_transients($post_id);
        }
    }

    /**
     * Fires on delete_post hook to clear all transients associated with that post
     * @param integer $post_id
     */
    public function delete_post($post_id) {
        $this->clear_post_transients($post_id);
    }

    /**
     * Fires on saving menu to clear all nav transients
     * @param integer $menu_id
     * @param array $menu_data
     */
    public function wp_update_nav_menu($menu_id, $menu_data) {
        self::delete('nav');
    }

    /**
     * Removes all transients associated with the specified post, including parent posts and archives
     * @param integer $post_id
     */
    private function clear_post_transients($post_id) {
        // Delete transients for current post
        self::delete('_'.$post_id.'_');

        // Delete transients for archives
        $post_type = get_post_type($post_id);
        self::delete('_'.$post_type.'_');

        // Have to also remove transients for ancestors or we may run into issues with "children as..." templates
        $ancestors = get_ancestors($post_id, $post_type);
        foreach ($ancestors as $ancestor_id) {
            self::delete('_'.$ancestor_id.'_');
        }
    }
}
new BB_Transients();
