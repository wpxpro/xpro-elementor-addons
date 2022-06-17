(function ($) {
	"use strict";

	$( '.xpro-dashboard-tabs > li > a:not(.xpro-dashboard-pro-link)' ).on(
		'click',
		function (e) {

			e.preventDefault();

			$( this ).parent().siblings().removeClass( 'active' );
			$( this ).parent().addClass( 'active' );

			var tabID = $( this ).attr( 'href' );
			$( '.xpro-dashboard-tab-content' ).removeClass( 'active' );
			$( tabID ).addClass( 'active' );

		}
	)

	$( document ).ready(
		function (e) {
			if ($( ".xpro-dashboard-tab-widgets .xpro-dashboard-item-wrapper .xpro-dashboard-widget-item input" ).not( ':checked' ).length > 0) {
				$( '.xpro-dashboard-widget-control-input' ).prop( 'checked', false );
			} else {
				$( '.xpro-dashboard-widget-control-input' ).prop( 'checked', true );
			}
		}
	);

	$( '#xpro-dashboard-widget-control-input' ).change(
		function () {
			if ($( this ).is( ':checked' )) {
				$( ".xpro-dashboard-tab-widgets input:not(:disabled)" ).prop( 'checked', true );
			} else {
				$( ".xpro-dashboard-tab-widgets input:not(:disabled)" ).prop( 'checked', false );
			}
		}
	);

	$( ".xpro-dashboard-tab-widgets .xpro-dashboard-item-wrapper input" ).change(
		function () {

			$( '.xpro-dashboard-tab-widgets .xpro-dashboard-save-button' ).addClass( 'xpro-before-update' );

			if ($( ".xpro-dashboard-tab-widgets .xpro-dashboard-item-wrapper input" ).not( ':checked' ).length > 0) {
				$( '#xpro-dashboard-widget-control-input' ).prop( 'checked', false );
			} else {
				$( '#xpro-dashboard-widget-control-input' ).prop( 'checked', true );
			}
		}
	);

	$( document ).ready(
		function (e) {
			if ($( ".xpro-dashboard-tab-modules .xpro-dashboard-item-wrapper .xpro-dashboard-widget-item input" ).not( ':checked' ).length > 0) {
				$( '.xpro-dashboard-module-control-input' ).prop( 'checked', false );
			} else {
				$( '.xpro-dashboard-module-control-input' ).prop( 'checked', true );
			}
		}
	);

	$( '#xpro-dashboard-module-control-input' ).change(
		function () {
			if ($( this ).is( ':checked' )) {
				$( ".xpro-dashboard-tab-modules input:not(:disabled)" ).prop( 'checked', true );
			} else {
				$( ".xpro-dashboard-tab-modules input:not(:disabled)" ).prop( 'checked', false );
			}
		}
	);

	$( ".xpro-dashboard-tab-modules .xpro-dashboard-item-wrapper input" ).change(
		function () {

			$( '.xpro-dashboard-tab-modules .xpro-dashboard-save-button' ).addClass( 'xpro-before-update' );

			if ($( ".xpro-dashboard-tab-modules .xpro-dashboard-item-wrapper input" ).not( ':checked' ).length > 0) {
				$( '#xpro-dashboard-module-control-input' ).prop( 'checked', false );
			} else {
				$( '#xpro-dashboard-module-control-input' ).prop( 'checked', true );
			}
		}
	);

	$( "#xpro-dashboard-settings-form" ).on(
		"submit",
		function (e) {
			e.preventDefault();

			var data = $( this ).serialize();
			$( '.xpro-dashboard-save-button > i' ).addClass( 'xpro-spin' );

			$.post(
				$( this ).attr( 'action' ),
				data,
				function (e) {
					$( '.xpro-dashboard-tab-widgets .xpro-dashboard-save-button' ).removeClass( 'xpro-before-update' );
					$( '.xpro-dashboard-tab-widgets .xpro-dashboard-save-button > i' ).removeClass( 'xpro-spin' );

					$( '.xpro-dashboard-tab-modules .xpro-dashboard-save-button' ).removeClass( 'xpro-before-update' );
					$( '.xpro-dashboard-tab-modules .xpro-dashboard-save-button > i' ).removeClass( 'xpro-spin' );

					location.reload();

				}
			);

		}
	);

	$( '.xpro-dashboard-featured-carousel' ).owlCarousel(
		{
			items: 3,
			loop: true,
			margin: 30,
			nav: true,
			autoWidth: true,
			center: true,
			autoplay: true,
			autoplayTimeout: 3000,
			autoplayHoverPause: true,
		}
	);

	$( '#xpro-dashboard-settings-form-license' ).on(
		'submit',
		function (e) {
			e.preventDefault();

			var id      = $( this ).data( 'id' ),
				name    = $( this ).data( 'name' ),
				type    = $( this ).data( 'action' ),
				nonce   = $( this ).data( 'nonce' ),
				url     = $( this ).data( 'url' ),
				key     = $( this ).find( '#xpro-dashboard-license-label-input' ).val(),
				post_data,
				output,
				proceed = true;

			var btn = $( this ).find( '.xpro-dashboard-license-submit' );

			if (id === "" || name === "" || key === "" || nonce === "") {
				proceed = false;
				$( this ).find( '#xpro-dashboard-license-label-input' ).addClass( 'xpro-insert' );
				return false;
			}

			//everything looks good! proceed...
			if (proceed) {

				btn.attr( "disabled", "disabled" );
				btn.find( "i" ).removeClass( 'd-none' );

				//Ajax post data to server
				jQuery.ajax(
					{
						url: url,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'xpro_elementor_license_data',
							id: id,
							type: type,
							name: name,
							key: key,
							nonce: nonce,
						},
						success: function (data) {

							let status = (data.hasOwnProperty( 'license' )) ? data.success : '';

							btn.removeAttr( "disabled" );
							btn.find( "i" ).addClass( 'd-none' );

							if (status) {
								location.reload();
							} else {
								$( ".xpro-license-result-error" ).hide().slideDown();
							}

						}

					}
				);

			}

		}
	);

	$( '.xpro-content-type-pro-disabled,.xpro-dashboard-text-form-wrapper.pro-disabled' ).on(
		'click',
		function (e) {
			e.preventDefault();
			$( '.xpro-dashboard-popup-wrapper' ).addClass( 'active' );
		}
	);

	$( '.xpro-dashboard-popup-close-btn' ).on(
		'click',
		function (e) {
			e.preventDefault();
			$( '.xpro-dashboard-popup-wrapper' ).removeClass( 'active' );
		}
	);

})( jQuery );
