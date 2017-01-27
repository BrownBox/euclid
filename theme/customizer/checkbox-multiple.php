<?php
/**
 * Multiple checkbox customize control class.
 * @since 1.0.0
 * @access public
 */
class BB_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {
    /**
     * The type of customize control being rendered.
     * @since 1.0.0
     * @access public
     * @var string
     */
    public $type = 'checkbox-multiple';

    /**
     * Enqueue scripts/styles.
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function enqueue() {
        wp_enqueue_script('bb-customize-controls', trailingslashit(get_template_directory_uri()).'js/customizer/checkbox-multiple.js', array('jquery'));
    }

    /**
     * Displays the control content.
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function render_content() {
        if (empty($this->choices))
            return;

        if (!empty($this->label)) {
?>
<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
<?php
        }
        if (!empty($this->description)) {
?>
<span class="description customize-control-description"><?php echo $this->description; ?></span>
<?php
        }
        $multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();
?>
<ul>
<?php
        foreach ($this->choices as $value => $label) {
?>
    <li><label><input type="checkbox" name="<?php echo $this->id ?>[]" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?>><?php echo esc_html($label); ?></label></li>
<?php
        }
?>
</ul>
<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $multi_values)); ?>">
<?php

    }
}
