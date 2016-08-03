(function(api) {
    var cssTemplate = wp.template('induspress-color-scheme'),
        colorSettings = [
            'link_color',
            'button_background_color',
            'button_hover_background_color',
            'section_dark_background_color',
            'footer_background_color',
            'highlight_color'
        ];

    // Update list of colors when select a color scheme.
    function updateColors(scheme) {
        scheme = scheme || 'default';
        var colors = IndusPressColorScheme[scheme].colors;
        _.each(colorSettings, function(key, index) {
            var color = colors[index];
            api(key).set(color);
            api.control(key).container.find('.color-picker-hex')
                .data('data-default-color', color)
                .wpColorPicker('defaultColor', color);
        });
    }
    api.controlConstructor.select = api.Control.extend({
        ready: function() {
            if ('color_scheme' === this.id) {
                this.setting.bind('change', updateColors);
            }
        }
    });

    // Update the CSS whenever a color setting is changed.
    function updateCSS() {
        var scheme = api('color_scheme')(),
            css,
            colors = _.object(colorSettings, IndusPressColorScheme[scheme].colors);

        _.each(colorSettings, function(setting) {
            colors[setting] = api(setting)();
        });

        css = cssTemplate(colors);
        api.previewer.send('update-color-scheme-css', css);
    }
    _.each(colorSettings, function(setting) {
        api(setting, function(setting) {
            setting.bind(updateCSS);
        });
    });
})(wp.customize);