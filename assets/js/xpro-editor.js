(function ($, window, document, undefined) {

    $(window).on(
        'elementor:init',
        function () {

            // Make our custom css visible in the panel's front-end
            if (typeof elementorPro == 'undefined') {
                elementor.hooks.addFilter(
                    'editor/style/styleText',
                    function (css, context) {
                        if (!context) {
                            return;
                        }

                        var model = context.model,
                            customCSS = model.get('settings').get('custom_css');
                        var selector = '.elementor-element.elementor-element-' + model.get('id');

                        if ('document' === model.get('elType')) {
                            selector = elementor.config.document.settings.cssWrapperSelector;
                        }

                        if (customCSS) {
                            css += customCSS.replace(/selector/g, selector);
                        }

                        return css;
                    }
                );
            }
            // End of Custom CSS

        }
    );

})(jQuery, window, document);
