(function ($, elementor) {

    "use strict";

    let Xpro = {

        init: function () {

            let widgets = {
                'xpro-simple-gallery.default': Xpro.SimpleGallery,
                'xpro-simple-portfolio.default': Xpro.SimplePortfolio,
                'xpro-progress-bar.default': Xpro.ProgressBar,
                'xpro-pie-chart.default': Xpro.PieChart,
                'xpro-counter.default': Xpro.Counter,
                'xpro-horizontal-menu.default': Xpro.HorizontalMenu,
                'xpro-team.default': Xpro.Team,
                'xpro-before-after.default': Xpro.BeforeAfter,
                'xpro-slide-anything.default': Xpro.SlideAnything,
                'xpro-content-toggle.default': Xpro.ContentToggle,
                'xpro-news-ticker.default': Xpro.NewsTicker,
                'xpro-table.default': Xpro.Table,
                'xpro-icon-box.default': Xpro.IconBox,
                'xpro-post-grid.default': Xpro.PostGrid,
                'xpro-hot-spot.default': Xpro.HotSpot,
                'xpro-image-scroller.default': Xpro.ImageScroller,
                'xpro-horizontal-timeline.default': Xpro.HorizontalTimeline,
                'xpro-contact-form.default': Xpro.ContactForm,
                'xpro-scroll-to-top.default': Xpro.ScrollToTop,
            };

            $.each(widgets, function (widget, callback) {
                elementorFrontend.hooks.addAction('frontend/element_ready/' + widget, callback);
            });

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

                    jQuery.each(settings.controls, function (name, control) {
                        if (control.frontend_available) {
                            settingsKeys.push(name);
                        }
                    });
                }

                jQuery.each(settings.getActiveControls(), function (controlKey) {
                    if (-1 !== settingsKeys.indexOf(controlKey)) {
                        elementSettings[controlKey] = settings.attributes[controlKey];
                    }
                });
            } else {
                elementSettings = $element.data('settings') || {};
            }

            return Xpro.getItems(elementSettings, setting);

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

        SimpleGallery: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                gallery = $scope.find(".xpro-elementor-gallery-wrapper"),
                filter = $scope.find(".xpro-elementor-gallery-filter > ul"),
                filterDefault = $scope.find(".xpro-elementor-gallery-filter > ul").attr('data-default-filter');

            // init cubeportfolio
            setTimeout(function () {
                gallery.cubeportfolio({
                    filters: filter,
                    layoutMode: 'grid',
                    defaultFilter: filterDefault,
                    animationType: elementSettings.filter_animation,
                    gridAdjustment: 'responsive',
                    mediaQueries: [
                        {
                            width: elementorFrontend.config.breakpoints.md + 1,
                            cols: elementSettings.item_per_row || 3,
                            options: {
                                gapHorizontal: elementSettings.margin.size || 0,
                                gapVertical: elementSettings.margin.size || 0,
                            }
                        }, {
                            width: elementorFrontend.config.breakpoints.sm + 1,
                            cols: elementSettings.item_per_row_tablet || 2,
                            options: {
                                gapHorizontal: elementSettings.margin_tablet.size || 0,
                                gapVertical: elementSettings.margin_tablet.size || 0,
                            }
                        }, {
                            width: 0,
                            cols: elementSettings.item_per_row_mobile || 1,
                            options: {
                                gapHorizontal: elementSettings.margin_mobile.size || 0,
                                gapVertical: elementSettings.margin_mobile.size || 0,
                            }
                        }],
                    caption: elementSettings.hover_effect || 'zoom',
                    displayType: 'sequentially',
                    displayTypeSpeed: 80,
                });
            },500);

            function galleryPopup() {
                if (elementSettings.popup !== 'none') {
                    gallery.lightGallery({
                        pager: true,
                        addClass: 'xpro-gallery-popup-style-' + elementSettings.popup,
                        selector: '[data-xpro-lightbox]',
                        thumbnail: (elementSettings.thumbnail === 'yes'),
                        exThumbImage: 'data-src',
                        thumbWidth: 130,
                        thumbMargin: 15,
                        closable: false,
                        showThumbByDefault: (elementSettings.thumbnail_by_default === 'yes'),
                        thumbContHeight: 150,
                        subHtmlSelectorRelative: true,
                        hideBarsDelay: 99999999,
                        share: (elementSettings.share === 'yes'),
                        download: (elementSettings.download === 'yes'),
                    });
                }
            }

            galleryPopup();

            let dropdown = $scope.find(".xpro-filter-dropdown-tablet,.xpro-filter-dropdown-mobile"),
                content = dropdown.find('li.cbp-filter-item-active').text();

            dropdown.find('.xpro-select-content').text(content);

            dropdown.on('click', function () {
                $(this).toggleClass('active');
            });

            dropdown.find('.cbp-l-filters-button > li').on('click', function () {
                dropdown.find('.xpro-select-content').text($(this).text());
            });

        },

        SimplePortfolio: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                gallery = $scope.find(".xpro-elementor-gallery-wrapper"),
                filter = $scope.find(".xpro-elementor-gallery-filter > ul"),
                filterDefault = $scope.find(".xpro-elementor-gallery-filter > ul").attr('data-default-filter');

            previewClose();

            setTimeout(function () {
                // init cubeportfolio
                gallery.cubeportfolio({
                    filters: filter,
                    layoutMode: 'grid',
                    defaultFilter: filterDefault,
                    animationType: elementSettings.filter_animation,
                    gridAdjustment: 'responsive',
                    mediaQueries: [
                        {
                            width: elementorFrontend.config.breakpoints.md + 1,
                            cols: elementSettings.item_per_row || 3,
                            options: {
                                gapHorizontal: elementSettings.margin.size || 0,
                                gapVertical: elementSettings.margin.size || 0,
                            }
                        }, {
                            width: elementorFrontend.config.breakpoints.sm + 1,
                            cols: elementSettings.item_per_row_tablet || 2,
                            options: {
                                gapHorizontal: elementSettings.margin_tablet.size || 0,
                                gapVertical: elementSettings.margin_tablet.size || 0,
                            }
                        }, {
                            width: 0,
                            cols: elementSettings.item_per_row_mobile || 1,
                            options: {
                                gapHorizontal: elementSettings.margin_mobile.size || 0,
                                gapVertical: elementSettings.margin_mobile.size || 0,
                            }
                        }],
                    caption: elementSettings.hover_effect || 'zoom',
                    displayType: 'sequentially',
                    displayTypeSpeed: 80,
                });
            },500);

            //DropDown
            let dropdown = $scope.find(".xpro-filter-dropdown-tablet,.xpro-filter-dropdown-mobile"),
                content = dropdown.find('li.cbp-filter-item-active').text();

            dropdown.find('.xpro-select-content').text(content);

            dropdown.on('click', function () {
                $(this).toggleClass('active');
            });

            dropdown.find('.cbp-l-filters-button > li').on('click', function () {
                dropdown.find('.xpro-select-content').text($(this).text());
            });

            //Preview
            var preview = null;
            let tl = new TimelineLite();
            tl.seek(0).clear();
            tl = new TimelineLite();

            $scope.find('.xpro-preview-iframe').on('load', function () {
                $(this).addClass('loaded');
                $(this).contents().find('html').attr('id', 'xpro-portfolio-html-main');
            });

            //Open Preview
            function OpenPreview($this, e) {
                let name = $($this).data('title');
                preview = $($this).data('src-preview');

                if ('false' === preview) {
                    return;
                }

                // Remove current class from siblings items.
                $($this).siblings().removeClass('xpro-preview-demo-item-open');

                // Current item.
                $($this).addClass('xpro-preview-demo-item-open');

                // Prev Next Buttons.
                $scope.find('.xpro-preview .xpro-preview-prev-demo,.xpro-preview .xpro-preview-next-demo').removeClass('xpro-preview-inactive');

                let prev = $($this).prev('[data-src-preview]');

                if (prev.length <= 0) {
                    $scope.find('.xpro-preview .xpro-preview-prev-demo').addClass('xpro-preview-inactive');
                }

                let next = $($this).next('[data-src-preview]');
                if (next.length <= 0) {
                    $scope.find('.xpro-preview .xpro-preview-next-demo').addClass('xpro-preview-inactive');
                }

                // Reset header info.
                $scope.find('.xpro-preview .xpro-preview-header-info').html('');

                // Add name to info.
                if (name) {
                    $scope.find('.xpro-preview .xpro-preview-header-info').append(`<div class="xpro-preview-demo-name">${name}</div>`);
                }

                // Set url in iframe.
                $scope.find('.xpro-preview .xpro-preview-iframe').attr('src', preview);

                // Body preview.
                $('body').addClass('xpro-preview-active');
                $scope.find('.xpro-preview').addClass('active');
            }

            //Open On Click
            $scope.on('click', '.xpro-preview-type-popup', function (e) {

                if (!$(e.target).is('.xpro-preview-demo-import-open')) {
                    popupOpenAnimation();
                    OpenPreview(this, e);
                }

            });

            $scope.on('click', '.xpro-preview-type-link', function (e) {

                let preview_url = '';
                preview_url = $(this).data('src-preview');

                if (preview_url !== "") {
                    window.open(preview_url, elementSettings.preview_target);
                }

                return false;

            });

            $scope.on('click', '.xpro-preview-type-none', function (e) {

                return false;

            });

            //Prev Preview
            $scope.on('click', '.xpro-preview-prev-demo', function (e) {

                var prev = $scope.find('.xpro-preview-demo-item-open').prev('[data-src-preview]');

                if (prev.length > 0) {
                    popupOpenAnimation();
                    OpenPreview(prev, e);
                }

                e.preventDefault();
            });

            //Next Preview
            $scope.on('click', '.xpro-preview-next-demo', function (e) {

                var next = $scope.find('.xpro-preview-demo-item-open').next('[data-src-preview]');

                if (next.length > 0) {
                    popupOpenAnimation();
                    OpenPreview(next, e);
                }

                e.preventDefault();
            });

            //Close Preview
            $scope.on('click', '.xpro-preview-close', function (e) {

                e.preventDefault();
                e.stopPropagation();

                popupOpenAnimation();

                setTimeout(function () {
                    previewClose();
                }, 2000);

            });

            function previewClose() {
                // Remove current class from items.
                $scope.find('.xpro-preview-demo-item').removeClass('xpro-preview-demo-item-open');

                // Remove url from iframe.
                $scope.find('.xpro-preview .xpro-preview-iframe').removeAttr('src');

                // Remove preview from body.
                $('body').removeClass('xpro-preview-active');
                $scope.find('.xpro-preview').removeClass('active');
            }

            //Preview Open Animation
            function popupOpenAnimation() {

                //Slice
                if (elementSettings.popup_animation === '1') {
                    tl.to($scope.find('.xpro-portfolio-loader-style-1 li'), {
                        duration: 0.4,
                        scaleX: 1,
                        transformOrigin: "bottom right"
                    });
                }

                if (elementSettings.popup_animation === '2') {
                    tl.to($scope.find('.xpro-portfolio-loader-style-2 li'), {
                        duration: 0.4,
                        scaleX: 1,
                        transformOrigin: "bottom left"
                    });
                }

                setTimeout(function () {
                    popupCloseAnimation();
                }, 2500);

            }

            //Preview Close Animation
            function popupCloseAnimation() {

                //Slice
                if (elementSettings.popup_animation === '1') {
                    tl.to($scope.find('.xpro-portfolio-loader-style-1 li'), {
                        duration: 0.4,
                        scaleX: 0,
                        transformOrigin: "bottom left"
                    });
                }

                if (elementSettings.popup_animation === '2') {
                    tl.to($scope.find('.xpro-portfolio-loader-style-2 li'), {
                        duration: 0.4,
                        scaleX: 0,
                        transformOrigin: "bottom right"
                    });
                }
            }

        },

        ProgressBar: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                count = $scope.find('.xpro-progress-count'),
                progressbar = $scope.find('.xpro-progress-track');

            $scope.find('.xpro-progress-bar').elementorWaypoint((function () {

                if (elementSettings.show_count === 'yes') {
                    count.animate({
                        Counter: elementSettings.value
                    }, {
                        duration: elementSettings.duration.size * 1000 || 3000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                            $(this).parents('.xpro-progress-counter').find('.xpro-progress-count-less').text(100 - Math.ceil(now));
                        }
                    });
                }

                progressbar.animate({width: elementSettings.value + "%"}, (elementSettings.duration.size * 1000 || 3000));

            }), {offset: "100%"});

        },

        PieChart: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope);


            $scope.find('.xpro-pie-chart').easyPieChart({
                scaleColor: "transparent",
                lineWidth: elementSettings.chart_bar_size.size,
                lineCap: elementSettings.layout, //Can be butt
                barColor: elementSettings.bar_color || '#6ec1e4',
                trackColor: elementSettings.track_color || '#f5f5f5',
                size: elementSettings.chart_size.size,
                rotate: 0,
                animate: (elementSettings.duration.size * 1000) || 2000,
            });

            $scope.find('.xpro-pie-chart').elementorWaypoint((function () {

                $scope.find('.xpro-pie-chart-count').animate({
                    Counter: elementSettings.value
                }, {
                    duration: (elementSettings.duration.size * 1000) || 2000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now) + '%');
                    }
                });

                $scope.find('.xpro-pie-chart').data('easyPieChart').update(elementSettings.value);

            }), {offset: "100%"});

        },

        Counter: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope);

            if (elementSettings.animate_counter === 'yes') {
                $scope.find('.xpro-counter-wrapper').elementorWaypoint((function () {

                    $scope.find('.xpro-counter-item').animate({
                        Counter: elementSettings.value
                    }, {
                        duration: (elementSettings.duration.size * 1000) || 2000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now) + (elementSettings.symbol || ''));
                        }
                    });

                }), {offset: "100%"});
            }

        },

        HorizontalMenu: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                toggler = $scope.find('.xpro-elementor-horizontal-menu-toggler'),
                close = $scope.find('.xpro-elementor-horizontal-menu-close'),
                overlay = $scope.find('.xpro-elementor-horizontal-menu-overlay'),
                dropdown = $scope.find('.dropdown > a');

            let deviceWidth = (elementSettings.responsive_show === 'tablet') ? 1025 : 768;

            toggler.on('click', function (e) {
                e.preventDefault();
                $scope.find('.xpro-elementor-horizontal-navbar-wrapper').toggleClass('active');
                $scope.find('.xpro-elementor-horizontal-menu-overlay').toggleClass('active');
            });

            close.on('click', function (e) {
                e.preventDefault();
                $scope.find('.xpro-elementor-horizontal-navbar-wrapper').removeClass('active');
                $scope.find('.xpro-elementor-horizontal-menu-overlay').removeClass('active');
            });

            overlay.on('click', function (e) {
                e.preventDefault();
                $scope.find('.xpro-elementor-horizontal-navbar-wrapper').removeClass('active');
                $(this).removeClass('active');
            });

            dropdown.on('click', function (e) {
                if ($(window).width() < deviceWidth) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).toggleClass('active');
                    $(this).next('.xpro-elementor-dropdown-menu').slideToggle();
                }
            });

            $scope.find(".dropdown li").each(function (e) {
                if ($('ul', this).length) {
                    let elm = $('ul:first', this);
                    let off = elm.offset();
                    let l = off.left;
                    let w = elm.width();
                    let docW = $(window).width();

                    var isEntirelyVisible = (l + w <= docW);

                    if (!isEntirelyVisible) {
                        $(this).addClass('xpro-edge');
                    } else {
                        $(this).removeClass('xpro-edge');
                    }
                }
            });

            $(window).resize(function () {
                if ($(window).width() > deviceWidth) {
                    $scope.find('.xpro-elementor-dropdown-menu').show();
                } else {
                    $scope.find('.xpro-elementor-dropdown-menu').hide();
                }
                $scope.find('.xpro-elementor-horizontal-navbar-wrapper').removeClass('active');
                $scope.find('.xpro-elementor-horizontal-menu-overlay').removeClass('active');
                $scope.find('.dropdown > a').removeClass('active');
            });

            if(elementSettings.one_page_navigation === 'yes'){

                one_page_navigation(true);
                $(document).on("scroll", function() {
                    one_page_navigation();
                });

                function one_page_navigation(remove = false) {
                    var scrollPos = $(document).scrollTop();
                    var offset = elementSettings.scroll_offset.size || 100;
                    $scope.find('#menu-one-page-menu li').each(function() {
                        let currLink = $(this);
                        let refElement = currLink.find('.xpro-elementor-nav-link').attr("href");
                        let index = refElement.indexOf("#");
                        if (index !== -1) {
                            let target = $(refElement.substring(index));
                            if(remove){
                                $('#menu-one-page-menu li:not(:first-child)').removeClass("current_page_item");
                            }
                            if (target.length && target.position().top - offset <= scrollPos && target.position().top + target.height() > scrollPos) {
                                $('#menu-one-page-menu li').removeClass("current_page_item");
                                currLink.addClass("current_page_item");
                            }
                        }
                    });

                }
            }

        },

        Team: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope);

            if (elementSettings.layout === '3') {
                $scope.find('.xpro-team-layout-3').hover(function () {
                    $(this).find('.xpro-team-description').slideDown(200);
                }, function () {
                    $(this).find('.xpro-team-description').slideUp(200);
                });
            }

            if (elementSettings.layout === '7') {
                $scope.find('.xpro-team-layout-7').hover(function () {
                    $(this).find('.xpro-team-description').slideDown(200);
                    $(this).find('.xpro-team-social-list').slideDown(250);
                }, function () {
                    $(this).find('.xpro-team-description').slideUp(200);
                    $(this).find('.xpro-team-social-list').slideUp(250);
                });
            }

            if (elementSettings.layout === '8') {
                $scope.find('.xpro-team-layout-8').hover(function () {
                    $(this).find('.xpro-team-content').slideDown(200);
                }, function () {
                    $(this).find('.xpro-team-content').slideUp(200);
                });
            }

            if (elementSettings.layout === '9') {
                let height = $scope.find('.xpro-team-image > img').height();
                let width = $scope.find('.xpro-team-inner-content').height();
                $scope.find('.xpro-team-inner-content').width(height);
                $scope.find('.xpro-team-inner-content').css('left', width + 'px');
            }

            if (elementSettings.layout === '14') {
                $scope.find('.xpro-team-layout-14').hover(function () {
                    $(this).find('.xpro-team-description').slideDown(200);
                    $(this).find('.xpro-team-social-list').slideDown(250);
                }, function () {
                    $(this).find('.xpro-team-description').slideUp(200);
                    $(this).find('.xpro-team-social-list').slideUp(250);
                });
            }

        },

        BeforeAfter: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                item = $scope.find('.xpro-compare-item');

            item.XproCompare({
                default_offset_pct: elementSettings.visible_ratio.size / 100 || 0.5,
                orientation: elementSettings.orientation,
                is_wiggle: elementSettings.wiggle === 'yes',
                wiggle_timeout: (elementSettings.wiggle) ? elementSettings.wiggle_timeout.size * 1000 : 1000,
                move_on_hover: elementSettings.mouse_move === 'yes',
            });

        },

        SlideAnything: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                slider = $scope.find(".xpro-slide-anything");

            let b;

            if ($('.e-route-panel-editor-content').length) {
                b = '.elementor-preview-responsive-wrapper'
            }

            slider.owlCarousel({
                loop: (elementSettings.loop === 'yes'),
                center: (elementSettings.center === 'yes'),
                nav: (elementSettings.nav === 'yes'),
                navText: ['<i aria-hidden="true" class="fas fa-chevron-left"></i>', '<i aria-hidden="true" class="fas fa-chevron-right"></i>'],
                dots: (elementSettings.dots === 'yes'),
                mouseDrag: (elementSettings.mouse_drag === 'yes'),
                touchDrag: true,
                autoHeight: false,
                autoWidth: (elementSettings.custom_width === 'yes'),
                responsiveBaseElement: b,
                autoplay: (elementSettings.autoplay === 'yes'),
                autoplayTimeout: (elementSettings.autoplay === 'yes') ? elementSettings.autoplay_timeout.size * 1000 : 3000,
                autoplayHoverPause: true,
                smartSpeed: 500,
                responsive: {
                    0: {
                        items: elementSettings.item_per_row_mobile || 1,
                        margin: elementSettings.margin_mobile.size,
                    },
                    768: {
                        items: elementSettings.item_per_row_tablet || 1,
                        margin: elementSettings.margin_tablet.size,
                    },
                    1024: {
                        items: elementSettings.item_per_row || 2,
                        margin: elementSettings.margin.size,
                    }
                }
            });

        },

        ContentToggle: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                toggle = $scope.find(".xpro-content-toggle-button");

            toggle.on('click', function (e) {
                e.preventDefault();
                $(this).parents('.xpro-content-toggle-button-wrapper').toggleClass('active');
            });

        },

        NewsTicker: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                slider = $scope.find(".xpro-news-ticker");

            let b;
            if ($('.e-route-panel-editor-content').length) {
                b = '.elementor-preview-responsive-wrapper'
            }

            slider.owlCarousel({
                items: 1,
                smartSpeed: 1000,
                loop: true,
                nav: false,
                dots: false,
                mouseDrag: false,
                touchDrag: false,
                rtl: (elementSettings.direction === 'rtl'),
                responsiveBaseElement: b,
                autoplay: (elementSettings.autoplay === 'yes'),
                autoplayTimeout: (elementSettings.autoplay === 'yes') ? elementSettings.autoplay_timeout.size * 1000 : 3000,
                animateOut: (elementSettings.effect === 'fade') ? 'fadeOut' : false,
                animateIn: (elementSettings.effect === 'fade') ? 'fadeIn' : false,
            });

            $scope.find(".xpro-news-ticker-next").click(function () {
                slider.trigger('next.owl.carousel', [1000]);
                $scope.find('.xpro-news-ticker-pause').find('i').removeClass('fa-pause');
                $scope.find('.xpro-news-ticker-pause').find('i').addClass('fa-play');
                $scope.find('.xpro-news-ticker-pause').addClass('active');
                slider.trigger('stop.owl.autoplay');
            });

            $scope.find(".xpro-news-ticker-prev").click(function () {
                slider.trigger('prev.owl.carousel', [1000]);
                $scope.find('.xpro-news-ticker-pause').find('i').removeClass('fa-pause');
                $scope.find('.xpro-news-ticker-pause').find('i').addClass('fa-play');
                $scope.find('.xpro-news-ticker-pause').addClass('active');
                slider.trigger('stop.owl.autoplay');
            });

            $scope.find(".xpro-news-ticker-close").click(function () {
                $scope.find(".xpro-news-ticker-wrapper").fadeOut('slow');
            });
        },

        Table: function ($scope) {

            let columnHead = $scope.find('.xpro-table-head-column-cell'),
                row = $scope.find('.xpro-table-body-row');

            row.each(function (i, tr) {
                var td = $(tr).find('.xpro-table-body-row-cell');
                td.each(function (index, td) {
                    if ($(td).prev().prop("colspan") > 1 && $(td).prop("colspan") !== 0) {
                        index = index + ($(td).prev().prop("colspan") - 1);
                    }
                    $(td).prepend('<div class="xpro-table-head-column-cell">' + columnHead.eq(index).html() + '</div>');
                });
            });

        },

        IconBox: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                container = $scope.find('.elementor-widget-container'),
                icon = $scope.find('.xpro-box-icon-item');

            if (elementSettings.hover_animation !== '') {
                container.hover(function () {
                    icon.addClass('animated ' + elementSettings.icon_hover_animation);
                }, function () {
                    icon.removeClass('animated ' + elementSettings.icon_hover_animation);
                });
            }
        },

        PostGrid: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                container = $scope.find('.xpro-post-grid-main'),
                item = $scope.find('.xpro-post-grid-item');

            container.cubeportfolio({
                layoutMode: 'grid',
                gridAdjustment: 'responsive',
                mediaQueries: [
                    {
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

        },

        HotSpot: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                item = $scope.find('.xpro-post-grid-main');

            $scope.find(".xpro-hotspot-type-click").on('click', function (e) {
                e.preventDefault();
                $(this).find(".xpro-hotspot-tooltip-text").toggleClass("active");
            });
        },

        ImageScroller: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                item = $scope.find('.xpro-scroll-image-inner'),
                image = $scope.find('.xpro-image-scroll-img > img'),
                transformOffset = 0;


            if (elementSettings.trigger_type !== 'mouse-hover') {
                return;
            }

            item.imagesLoaded(function () {

                if (elementSettings.direction_type === "vertical") {
                    transformOffset = image.height() - item.height();
                } else {
                    transformOffset = image.width() - item.width();
                }

                if (elementSettings.reverse === 'yes') {

                    image.css("transform", (elementSettings.direction_type === "vertical" ? "translateY" : "translateX") + "( -" + transformOffset + "px)");

                    item.hover(function () {
                        image.css("transform", (elementSettings.direction_type === "vertical" ? "translateY" : "translateX") + "(0px)");
                    }, function () {
                        image.css("transform", (elementSettings.direction_type === "vertical" ? "translateY" : "translateX") + "( -" + transformOffset + "px)");
                    });

                } else {

                    item.hover(function () {
                        image.css("transform", (elementSettings.direction_type === "vertical" ? "translateY" : "translateX") + "( -" + transformOffset + "px)");
                    }, function () {
                        image.css("transform", (elementSettings.direction_type === "vertical" ? "translateY" : "translateX") + "(0px)");
                    });

                }

            });

        },

        HorizontalTimeline: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                slider = $scope.find('.xpro-horizontal-timeline');

            let b;
            let resize = 767;

            if ($('.e-route-panel-editor-content').length) {
                b = '.elementor-preview-responsive-wrapper';
            }

            if ($('.elementor-editor-active').length) {
                resize = 750;
            }

            slider.owlCarousel({
                loop: (elementSettings.loop === 'yes'),
                center: (elementSettings.center === 'yes'),
                nav: false,
                dots: (elementSettings.dots === 'yes'),
                mouseDrag: (elementSettings.mouse_drag === 'yes'),
                touchDrag: true,
                autoHeight: false,
                autoWidth: (elementSettings.custom_width === 'yes'),
                responsiveBaseElement: b,
                autoplay: (elementSettings.autoplay === 'yes'),
                autoplayTimeout: (elementSettings.autoplay === 'yes') ? elementSettings.autoplay_timeout.size * 1000 : 3000,
                autoplayHoverPause: true,
                smartSpeed: 500,
                responsive: {
                    0: {
                        items: elementSettings.item_per_row_mobile || 1,
                        margin: elementSettings.margin_mobile.size,
                    },
                    768: {
                        items: elementSettings.item_per_row_tablet || 1,
                        margin: elementSettings.margin_tablet.size,
                    },
                    1024: {
                        items: elementSettings.item_per_row || 2,
                        margin: elementSettings.margin.size,
                    }
                }
            });
            $scope.find(".xpro-horizontal-timeline-next").click(function () {
                slider.trigger('next.owl.carousel', [1000]);
            });

            $scope.find(".xpro-horizontal-timeline-prev").click(function () {
                slider.trigger('prev.owl.carousel', [1000]);
            });

            equal_horizontal_timeline();

            setTimeout(function () {
                equal_horizontal_timeline();
            },500);

            $(window).on('resize', function () {
                setTimeout(function () {
                    equal_horizontal_timeline();
                }, 500);
            });

            // Equal Height
            function equal_horizontal_timeline() {
                if (elementSettings.reverse === 'yes' && $(window).width() >= resize) {
                    let maxDiv = $scope.find('.xpro-horiz-equal-height > div');
                    let maxHeight = 0;
                    maxDiv.each(function () {
                        maxHeight = Math.max(maxHeight, $(this).outerHeight());
                    });
                    maxDiv.parent().css({minHeight: maxHeight + 'px'});
                } else {
                    let contentDiv = $scope.find('.xpro-horizontal-timeline-content > div');
                    let contentMaxHeight = 0;
                    contentDiv.each(function () {
                        contentMaxHeight = Math.max(contentMaxHeight, $(this).outerHeight());
                    });
                    contentDiv.parent().css({minHeight: contentMaxHeight + 'px'});

                    let dateDiv = $scope.find('.xpro-horizontal-timeline-date > div');
                    let dateMaxHeight = 0;
                    dateDiv.each(function () {
                        dateMaxHeight = Math.max(dateMaxHeight, $(this).outerHeight());
                    });
                    dateDiv.parent().css({minHeight: dateMaxHeight + 'px'});
                }
            }

        },

        ContactForm: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                form = $scope.find('.xpro-contact-form'),
                btn = $scope.find('.xpro-contact-form-submit-button'),
                loader = $scope.find('.xpro-contact-form-submit-button > i'),
                captcha = false,
                url = form.attr('action');

            form.on('submit', function (e) {
                e.preventDefault();
                loader.show();

                let data = form.serializeArray(),
                    dataArr = [],
                    proceed = true,
                    output = null,
                    arrStr = '',
                    count = 0;


                if (elementSettings.recaptcha) {
                    captcha = $scope.find('.g-recaptcha-response').val();
                }

                $.each(data, function (i, field) {
                    if (field.value !== '' && field.name !== 'g-recaptcha-response') {
                        dataArr[count] = {
                            name: $scope.find('label[for=' + field.name.replaceAll('[]', '') + ']').text() || '',
                            value: field.value
                        }
                        count++;
                    }
                });

                //Check Array Length
                if (dataArr.length) {
                    arrStr = dataArr.map(obj => {
                        return Object.entries(obj).reduce((key, val, i) => `${key}${i > 0 ? '&&' : ''}${val[0]}=${val[1]}`, "");
                    });
                } else {
                    proceed = false;
                }

                //Check Require Field
                $scope.find('.xpro-contact-form-require [required="required"]').each(function (i) {
                    if ($(this).val() === '') {
                        proceed = false;
                    }
                });


                if (!proceed) {
                    output = '<div class="xpro-alert xpro-alert-danger">' + elementSettings.required_field_message + '</div>';
                    $scope.find(".xpro-contact-form-message").hide().html(output).slideDown();
                    loader.hide();
                    return;
                }

                if (elementSettings.recaptcha && !captcha) {
                    output = '<div class="xpro-alert xpro-alert-danger">' + elementSettings.captcha_message + '</div>';
                    $scope.find(".xpro-contact-form-message").hide().html(output).slideDown();
                    loader.hide();
                    return;
                }

                $scope.find(".xpro-contact-form-message").slideUp();

                $.ajax({
                    method: "POST",
                    url: url,
                    data: {
                        postData: JSON.stringify(arrStr),
                        formName: elementSettings.form_name,
                        formSubject: elementSettings.form_subject,
                        captcha: captcha
                    }
                }).done(function (response) {
                    if (response.success) {
                        output = '<div class="xpro-alert xpro-alert-success">' + elementSettings.success_message + '</div>';
                        $scope.find(".xpro-contact-form-message").hide().html(output).slideDown();
                    } else {
                        output = '<div class="xpro-alert xpro-alert-danger">' + elementSettings.error_message + '</div>';
                        $scope.find(".xpro-contact-form-message").hide().html(output).slideDown();
                    }
                    loader.hide();
                    if (elementSettings.recaptcha) {
                        grecaptcha.reset();
                    }
                });

            });

        },

        ScrollToTop: function ($scope) {

            let elementSettings = Xpro.getElementSettings($scope),
                item = $scope.find('.xpro-elementor-scroll-top-btn'),
                ratio = elementSettings.layout === 'fixed' ? elementSettings.scroll_top_offset.size || 150 : 150,
                speed = elementSettings.animated_duration.size * 100 || 100;

            $(window).scroll(function () {
                if ($(this).scrollTop() > ratio) {
                    $scope.find('.xpro-elementor-scroll-top-btn-fixed').show();
                } else {
                    $scope.find('.xpro-elementor-scroll-top-btn-fixed').hide();
                }
            });

            $scope.find('.xpro-elementor-scroll-top-btn').click(function () {
                $("html, body").animate({scrollTop: "0"}, speed);
            });

        },

    };

    $(window).on('elementor/frontend/init', Xpro.init);

}(jQuery, window.elementorFrontend));


