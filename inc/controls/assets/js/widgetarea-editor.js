 ! function (e, t) {
	"use strict";
	if ("undefined" == typeof window.parent) {
		return;
	}
	let n = window.parent;
	var d = function () {
		t.config.environmentMode.edit && (n.jQuery( "#elementor-editor-dark-mode-css" ).length > 0 && n.jQuery( "body" ).addClass( "elementor-editor-dark-mode" ), t.hooks.addAction(
			"frontend/element_ready/global",
			(function (t) {
				(t.find( ".widgetarea_wrapper_edit" ).on(
					"click",
					(function () {
						var t = n.jQuery( ".widgetarea_iframe_modal" ), d = t.find( "#widgetarea-control-iframe" ),
						i     = t.find( ".dialog-lightbox-loading" ), o = t.find( ".dialog-type-lightbox" ),
						a     = e( this ).parent().attr( "data-xpro-widgetarea-key" ),
						r     = e( this ).parent().attr( "data-xpro-widgetarea-index" ),
						l     = window.xpro_elementor_control_params.rest_api_url + "xpro/v1/dynamic-content/content_editor/widget/" + a + "-" + r;
						n.jQuery( "body" ).attr( "data-xpro-widgetarea-key", a ), n.jQuery( "body" ).attr( "data-xpro-widgetarea-load", "false" ), o.show(), t.show(), i.show(), d.contents().find( "#elementor-loading" ).show(), d.css( "z-index", "-1" ), d.attr( "src", l ), d.on(
							"load",
							(function () {
								i.hide(), d.show(), d.contents().find( "#elementor-loading" ).hide(), d.css( "z-index", "1" )
							})
						)
					})
				), "undefined" != typeof n.jQuery) && n.jQuery( ".widgetarea_iframe_modal" ).find( ".eicon-close" ).on(
					"click",
					(function () {
						n.jQuery( "body" ).attr( "data-xpro-widgetarea-load", "true" )
					})
				)
			})
		))
	};
	e( window ).on( "elementor/frontend/init", d )
}(jQuery, window.elementorFrontend);
