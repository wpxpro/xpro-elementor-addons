(function ($, elementor) {

    "use strict";

    let Xpro_Elementor_Woo = {

        init: function () {

            let widgets = {
                'xpro-woo-product-grid.default': Xpro_Elementor_Woo.ProductGrid,
            };

            $.each(
                widgets,
                function (widget, callback) {
                    elementorFrontend.hooks.addAction('frontend/element_ready/' + widget, callback);
                }
            );

        },

        getElementSettings: function ($element, setting) {

            var elementSettings = {},
                modelCID = $element.data('model-cid');

            if (elementorFrontend.isEditMode() && modelCID) {
                var settings = elementorFrontend.config.elements.data[modelCID],
                    type = settings.attributes.widgetType || settings.attributes.elType,
                    settingsKeys = elementorFrontend.config.elements.keys[type];

                if (!settingsKeys) {
                    settingsKeys = elementorFrontend.config.elements.keys[type] = [];

                    jQuery.each(
                        settings.controls,
                        function (name, control) {
                            if (control.frontend_available) {
                                settingsKeys.push(name);
                            }
                        }
                    );
                }

                jQuery.each(
                    settings.getActiveControls(),
                    function (controlKey) {
                        if (-1 !== settingsKeys.indexOf(controlKey)) {
                            elementSettings[controlKey] = settings.attributes[controlKey];
                        }
                    }
                );
            } else {
                elementSettings = $element.data('settings') || {};
            }

            return Xpro_Elementor_Woo.getItems(elementSettings, setting);

        },

        getItems: function (items, itemKey) {
            if (itemKey) {
                var keyStack = itemKey.split('.'),
                    currentKey = keyStack.splice(0, 1);

                if (!keyStack.length) {
                    return items[currentKey];
                }

                if (!items[currentKey]) {
                    return;
                }

                return this.getItems(items[currentKey], keyStack.join('.'));
            }

            return items;
        },

        ProductGrid: function ($scope) {

            let elementSettings = Xpro_Elementor_Woo.getElementSettings($scope),
                product_grid_container = $scope.find('.xpro-woo-product-grid-main'),
                item = $scope.find('.xpro-woo-product-grid-item');

            $scope.find(".xpro-hv-qv-btn").click(
                function (e) {
                    e.preventDefault();
                    let product_id = $(this).attr('id');
                    let data = {
                        action: "load_quick_view_product_data",
                        nonce: XproElementorAddonsWoo.nonce,
                        id: product_id,
                    };
                    $.ajax(
                        {
                            url: XproElementorAddonsWoo.ajax_url,
                            type: 'post',
                            data: data,
                            dataType: 'html',
                            cache: false,
                            beforeSend: function () {
                                $scope.find('.xpro-qv-loader-wrapper').css('display', 'unset');
                                $scope.find('.xpro-qv-popup-wrapper').css('display', 'none');
                            },
                            complete: function () {
                                $scope.find('.xpro-qv-loader-wrapper').css('display', 'none');
                                $scope.find('.xpro-qv-popup-wrapper').css('display', 'unset');
                                $scope.find('.xpro-qv-popup-overlay').css(
                                    {
                                        "opacity": "1",
                                        "visibility": "visible"
                                    }
                                );
                            },
                            success: function (data) {
                                $scope.find('#xpro_elementor_fetch_qv_data').html(data)
                            }
                        }
                    );
                }
            );

            //close button
            $scope.on('click', '.xpro-woo-qv-cross', function (e) {
                    e.preventDefault();
                    $scope.find('.xpro-qv-popup-wrapper').css('display', 'none');
                    $scope.find('.xpro-qv-popup-overlay').css(
                        {
                            "opacity": "0",
                            "visibility": "hidden"
                        }
                    );
                }
            );

            //ovarlay click to close quick view
            $scope.on('click', '.xpro-qv-popup-overlay', function (e) {
                    e.preventDefault();
                    $scope.find('.xpro-qv-popup-wrapper').css('display', 'none');
                    $scope.find('.xpro-qv-popup-overlay').css(
                        {
                            "opacity": "0",
                            "visibility": "hidden"
                        }
                    );
                }
            );

            //press esc to close quick view
            $(document).keyup(
                function (e) {
                    if (e.keyCode === 27) {
                        $scope.find('.xpro-qv-popup-wrapper').css('display', 'none');
                        $scope.find('.xpro-qv-popup-overlay').css(
                            {
                                "opacity": "0",
                                "visibility": "hidden"
                            }
                        );
                    }
                }
            );

            //quick view product ajax
            $scope.on('click', '#xpro_elementor_fetch_qv_data .single_add_to_cart_button',
                function (e) {
                    e.preventDefault();

                    let $form = $(this).closest('form');
                    if (!$form[0].checkValidity()) {
                        $form[0].reportValidity();
                        return false;
                    }

                    let $thisbutton = $(this),
                        product_id = $(this).val(),
                        variation_id = $('input[name="variation_id"]').val() || "",
                        quantity = $('input[name="quantity"]').val();

                    if ($scope.find('.woocommerce-grouped-product-list-item').length) {
                        let quantities = $('input.qty'),
                            quantity = [];
                        $.each(
                            quantities,
                            function (index, val) {

                                let name = $(this).attr('name');
                                name = name.replace('quantity[', '');
                                name = name.replace(']', '');
                                name = parseInt(name);

                                if ($(this).val()) {
                                    quantity[name] = $(this).val();
                                }
                            }
                        );
                    }

                    let cartFormData = $form.serialize();

                    if ($thisbutton.is(".single_add_to_cart_button")) {
                        $thisbutton.removeClass("added");
                        $thisbutton.addClass("loading");
                        let is_valid = true;

                        $('.xpro-woo-qv-content-sec select,.xpro-woo-qv-content-sec input').each(function(i){
                            if($(this).val() === ''){
                                let id = $(this).attr('id');
                                let attr_name = $("label[for='"+id+"']").text();
                                alert(attr_name+" is required!");
                                is_valid = false;
                                $thisbutton.removeClass("loading");
                            }
                        });

                        if(is_valid){
                            $.ajax(
                                {
                                    url: XproElementorAddonsWoo.ajax_url,
                                    type: "POST",
                                    data: "action=add_cart_single_product_ajax&product_id=" +
                                        product_id +
                                        "&nonce=" + XproElementorAddonsWoo.nonce +
                                        "&" + cartFormData,

                                    success: function (results) {
                                        $(document.body).trigger("wc_fragment_refresh");
                                        $thisbutton.removeClass("loading");
                                        $thisbutton.addClass("added");
                                    },
                                }
                            );
                        }
                    }
                }
            );

            setTimeout(function () {
                product_grid_container.cubeportfolio({
                    layoutMode: 'grid',
                    gridAdjustment: 'responsive',
                    mediaQueries: [{
                        width: elementorFrontend.config.breakpoints.md + 1,
                        cols: elementSettings.column_grid || 3,
                        options: {
                            gapHorizontal: elementSettings.space_between.size,
                            gapVertical: elementSettings.space_between.size,
                        }
                    }, {
                        width: elementorFrontend.config.breakpoints.sm + 1,
                        cols: elementSettings.column_grid_tablet || 2,
                        options: {
                            gapHorizontal: elementSettings.space_between_tablet.size || 15,
                            gapVertical: elementSettings.space_between_tablet.size || 15,
                        }
                    }, {
                        width: 0,
                        cols: elementSettings.column_grid_mobile || 1,
                        options: {
                            gapHorizontal: elementSettings.space_between_mobile.size || 15,
                            gapVertical: elementSettings.space_between_mobile.size || 15,
                        }
                    }],
                    displayType: 'sequentially',
                    displayTypeSpeed: 80,
                });
            },500);

        },

    };

    $(window).on('elementor/frontend/init', Xpro_Elementor_Woo.init);

}(jQuery, window.elementorFrontend));
