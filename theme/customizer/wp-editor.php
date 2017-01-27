<?php
/**
 * WP Editor customize control class.
 * @access public
 */
class BB_Customize_Control_Wp_Editor extends WP_Customize_Control {
    /**
     * The type of customize control being rendered.
     * @access public
     * @var string
     */
    public $type = 'textarea';

    /**
     * Displays the control content.
     * @access public
     * @return void
     */
    public function render_content() {
?>
	    <label>
	    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
<?php
	    $settings = array('media_buttons' => true, 'drag_drop_upload' => true);
	    $this->filter_editor_setting_link();
	    wp_editor($this->value(), $this->id, $settings);
?>
	    </label>
<?php
        do_action('admin_footer');
        do_action('admin_print_footer_scripts');
    }

    private function filter_editor_setting_link() {
        add_filter('the_editor', function($output) {
            return preg_replace('/<textarea/', '<textarea '.$this->get_link(), $output, 1);
        });
    }
}
