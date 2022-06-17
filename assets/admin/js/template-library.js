"use strict";

function _typeof(e) {
	return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
		return typeof e
	} : function (e) {
		return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
	})( e )
}

! function (e, t, i) {
	var r                              = {Views: {}, Models: {}, Collections: {}, Behaviors: {}, Layout: null, Manager: null};
	r.Models.Template                  = Backbone.Model.extend(
		{
			defaults: {
				template_id: 0,
				title: "",
				type: "",
				thumbnail: "",
				url: "",
				tags: [],
				isPro: ! 1
			}
		}
	), r.Collections.Template          = Backbone.Collection.extend( {model: r.Models.Template} ), r.Behaviors.InsertTemplate = Marionette.Behavior.extend(
		{
			ui: {insertButton: ".xproTemplateLibrary__insert-button"},
			events: {"click @ui.insertButton": "onInsertButtonClick"},
			onInsertButtonClick: function () {
				i.library.insertTemplate( {model: this.view.model} )
			}
			}
	), r.Views.EmptyTemplateCollection = Marionette.ItemView.extend(
		{
			id: "elementor-template-library-templates-empty",
			template: "#tmpl-xproTemplateLibrary__empty",
			ui: {title: ".elementor-template-library-blank-title", message: ".elementor-template-library-blank-message"},
			modesStrings: {
				empty: {title: "Templates Not Found", message: "Something Went Wrong Please Check"},
				noResults: {title: "No Result Found", message: "Sorry, but nothing matched your selection"}
			},
			getCurrentMode: function () {
				return i.library.getFilter( "text" ) ? "noResults" : "empty"
			},
			onRender: function () {
				var e = this.modesStrings[this.getCurrentMode()];
				this.ui.title.html( e.title ), this.ui.message.html( e.message )
			}
			}
	), r.Views.Loading                 = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__loading",
			id: "xproTemplateLibrary__loading"
			}
	), r.Views.Logo                    = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__header-logo",
			className: "xproTemplateLibrary__header-logo",
			templateHelpers: function () {
				return {title: this.getOption( "title" )}
			}
			}
	), r.Views.BackButton              = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__header-back",
			id: "elementor-template-library-header-preview-back",
			className: "xproTemplateLibrary__header-back",
			events: function () {
				return {click: "onClick"}
			},
			onClick: function () {
				i.library.showTemplatesView()
			}
			}
	), r.Views.Menu                    = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__header-menu",
			id: "elementor-template-library-header-menu",
			className: "xproTemplateLibrary__header-menu",
			templateHelpers: function () {
				return i.library.getTabs()
			},
			ui: {menuItem: ".elementor-template-library-menu-item"},
			events: {"click @ui.menuItem": "onMenuItemClick"},
			onMenuItemClick: function (e) {
				i.library.setFilter( "tags", "" ), i.library.setFilter( "text", "" ), i.library.setFilter( "type", e.currentTarget.dataset.tab, ! 0 ), i.library.showTemplatesView()
			}
			}
	), r.Views.ResponsiveMenu          = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__header-menu-responsive",
			id: "elementor-template-library-header-menu-responsive",
			className: "xproTemplateLibrary__header-menu-responsive",
			ui: {items: "> .elementor-component-tab"},
			events: {"click @ui.items": "onTabItemClick"},
			onTabItemClick: function (t) {
				var r = e( t.currentTarget ), n = r.data( "tab" );
				i.library.channels.tabs.trigger( "change:device", n, r )
			}
			}
	), r.Views.Actions                 = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__header-actions",
			id: "elementor-template-library-header-actions",
			ui: {sync: "#xproTemplateLibrary__header-sync i"},
			events: {"click @ui.sync": "onSyncClick"},
			onSyncClick: function () {
				var e = this;
				e.ui.sync.addClass( "eicon-animation-spin" ), i.library.requestLibraryData(
					{
						onUpdate: function () {
							e.ui.sync.length && e.ui.sync.removeClass( "eicon-animation-spin" ), i.library.updateBlocksView()
						}, forceUpdate: ! 0, forceSync: ! 0
						}
				)
			}
			}
	), r.Views.InsertWrapper           = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__header-insert",
			id: "elementor-template-library-header-preview",
			behaviors: {insertTemplate: {behaviorClass: r.Behaviors.InsertTemplate}}
			}
	), r.Views.Preview                 = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__preview",
			className: "xproTemplateLibrary__preview",
			ui: function () {
				return {iframe: "> iframe"}
			},
			onRender: function () {
				this.ui.iframe.attr( "src", this.getOption( "url" ) ).hide();
				var e = this, t = (new r.Views.Loading()).render();
				this.$el.append( t.el ), this.ui.iframe.on(
					"load",
					function () {
						e.$el.find( "#xproTemplateLibrary__loading" ).remove(), e.ui.iframe.show()
					}
				)
			}
			}
	), r.Views.TemplateCollection      = Marionette.CompositeView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__templates",
			id: "xproTemplateLibrary__templates",
			className: function () {
				return "xproTemplateLibrary__templates xproTemplateLibrary__templates--" + i.library.getFilter( "type" )
			},
			childViewContainer: "#xproTemplateLibrary__templates-list",
			emptyView: function () {
				return new r.Views.EmptyTemplateCollection()
			},
			ui: {
				templatesWindow: ".xproTemplateLibrary__templates-window",
				textFilter: "#xproTemplateLibrary__search",
				tagsFilter: "#xproTemplateLibrary__filter-tags",
				filterBar: "#xproTemplateLibrary__toolbar-filter",
				counter: "#xproTemplateLibrary__toolbar-counter"
			},
			events: {"input @ui.textFilter": "onTextFilterInput", "click @ui.tagsFilter li": "onTagsFilterClick"},
			getChildView: function (e) {
				return r.Views.Template
			},
			initialize: function () {
				this.listenTo( i.library.channels.templates, "filter:change", this._renderChildren )
			},
			filter: function (e) {
				var t = i.library.getFilterTerms(), r = ! 0;
				return _.each(
					t,
					function (t, n) {
						var a = i.library.getFilter( n );
						if (a && t.callback) {
							var o = t.callback.call( e, a );
							return o || (r = ! 1), o
						}
					}
				), r
			},
			setMasonrySkin: function () {
				if ("section" === i.library.getFilter( "type" )) {
					var e = new elementorModules.utils.Masonry(
						{
							container: this.$childViewContainer,
							items: this.$childViewContainer.children()
							}
					);
					this.$childViewContainer.imagesLoaded( e.run.bind( e ) )
				}
			},
			onRenderCollection: function () {
				this.setMasonrySkin(), this.updatePerfectScrollbar(), this.setTemplatesFoundText()
			},
			setTemplatesFoundText: function () {
				var e = i.library.getFilter( "type" ), t = this.children.length, r = "<b>" + t + "</b>";
				r    += "section" === e ? " block" : " " + e, t > 1 && (r += "s"), r += " found", this.ui.counter.html( r )
			},
			onTextFilterInput: function () {
				var e = this;
				_.defer(
					function () {
						i.library.setFilter( "text", e.ui.textFilter.val() )
					}
				)
			},
			onTagsFilterClick: function (t) {
				var r = e( t.currentTarget ), n = r.data( "tag" );
				i.library.setFilter( "tags", n ), r.addClass( "active" ).siblings().removeClass( "active" ), n = n ? i.library.getTags()[n] : "Filter", this.ui.filterBar.find( ".xproTemplateLibrary__filter-btn" ).html( n + ' <i class="eicon-caret-down"></i>' )
			},
			updatePerfectScrollbar: function () {
				this.perfectScrollbar || (this.perfectScrollbar = new PerfectScrollbar( this.ui.templatesWindow[0], {suppressScrollX: ! 0} )), this.perfectScrollbar.isRtl = ! 1, this.perfectScrollbar.update()
			},
			setTagsFilterHover: function () {
				var e = this;
				e.ui.filterBar.hoverIntent(
					function () {
						e.ui.tagsFilter.css( "display", "block" ), e.ui.filterBar.find( ".xproTemplateLibrary__filter-btn i" ).addClass( "eicon-caret-down" ).removeClass( "eicon-caret-right" )
					},
					function () {
						e.ui.tagsFilter.css( "display", "none" ), e.ui.filterBar.find( ".xproTemplateLibrary__filter-btn i" ).addClass( "eicon-caret-right" ).removeClass( "eicon-caret-down" )
					},
					{sensitivity: 50, interval: 150, timeout: 100}
				)
			},
			onRender: function () {
				this.setTagsFilterHover(), this.updatePerfectScrollbar()
			}
			}
	), r.Views.Template                = Marionette.ItemView.extend(
		{
			template: "#tmpl-xproTemplateLibrary__template",
			className: "xproTemplateLibrary__template",
			ui: {previewButton: ".xproTemplateLibrary__preview-button, .xproTemplateLibrary__template-preview"},
			events: {"click @ui.previewButton": "onPreviewButtonClick"},
			behaviors: {insertTemplate: {behaviorClass: r.Behaviors.InsertTemplate}},
			onPreviewButtonClick: function () {
				i.library.showPreviewView( this.model )
			}
			}
	), r.Modal                         = elementorModules.common.views.modal.Layout.extend(
		{
			getModalOptions: function () {
				return {
					id: "xproTemplateLibrary__modal",
					hide: {onOutsideClick: ! 1, onEscKeyPress: ! 0, onBackgroundClick: ! 1}
				}
			}, getTemplateActionButton: function (e) {
				var t = "#tmpl-xproTemplateLibrary__" + (e.isPro && 0 == XproElementorAddonsEditor.hasPro ? "pro-button" : "insert-button"),
				i     = Marionette.TemplateCache.get( t );
				return Marionette.Renderer.render( i )
			}, showLogo: function (e) {
				this.getHeaderView().logoArea.show( new r.Views.Logo( e ) )
			}, showDefaultHeader: function () {
				this.showLogo( {title: "TEMPLATES"} );
				var e = this.getHeaderView();
				e.tools.show( new r.Views.Actions() ), e.menuArea.show( new r.Views.Menu() )
			}, showPreviewView: function (e) {
				var t = this.getHeaderView();
				t.menuArea.show( new r.Views.ResponsiveMenu() ), t.logoArea.show( new r.Views.BackButton() ), t.tools.show( new r.Views.InsertWrapper( {model: e} ) ), this.modalContent.show( new r.Views.Preview( {url: e.get( "url" )} ) )
			}, showTemplatesView: function (e) {
				this.showDefaultHeader(), this.modalContent.show( new r.Views.TemplateCollection( {collection: e} ) )
			}
			}
	), r.Manager                       = function () {
		function i() {
			var i = e( this ).closest( ".elementor-top-section" ), r = i.data( "id" ),
			n     = t.documents.getCurrent().container.children, a = i.prev( ".elementor-add-section" );
			n && _.each(
				n,
				function (e, t) {
					r === e.id && (m.atIndex = t)
				}
			), a.find( ".elementor-add-xpro-button" ).length || a.find( p ).before( d )
		}

		function n(t, i) {
			i.addClass( "elementor-active" ).siblings().removeClass( "elementor-active" );
			var r = u[t] || u.desktop;
			e( ".xproTemplateLibrary__preview" ).css( "width", r )
		}

			var a, o, l, s, c, m    = this, p = ".elementor-add-new-section .elementor-add-section-drag-title",
			d                       = '<div class="elementor-add-section-area-button elementor-add-xpro-button"> <i class="xi xi-xpro"></i> </div>',
			u                       = {desktop: "100%", tab: "680px", mobile: "360px"};
			this.atIndex            = -1, this.channels = {
				tabs: Backbone.Radio.channel( "tabs" ),
				templates: Backbone.Radio.channel( "templates" )
		}, this.updateBlocksView    = function () {
			m.setFilter( "tags", "", ! 0 ), m.setFilter( "text", "", ! 0 ), m.getModal().showTemplatesView( s )
		}, this.setFilter           = function (e, t, i) {
			m.channels.templates.reply( "filter:" + e, t ), i || m.channels.templates.trigger( "filter:change" )
		}, this.getFilter           = function (e) {
			return m.channels.templates.request( "filter:" + e )
		}, this.getFilterTerms      = function () {
			return {
				tags: {
					callback: function (e) {
						return _.any(
							this.get( "tags" ),
							function (t) {
								return t.indexOf( e ) >= 0
							}
						)
					}
				}, text: {
					callback: function (e) {
						return e = e.toLowerCase(), this.get( "title" ).toLowerCase().indexOf( e ) >= 0 || _.any(
							this.get( "tags" ),
							function (t) {
								return t.indexOf( e ) >= 0
							}
						)
					}
				}, type: {
					callback: function (e) {
						return this.get( "type" ) === e
					}
				}
			}
		}, this.showModal           = function () {
			m.getModal().showModal(), m.showTemplatesView()
		}, this.closeModal          = function () {
			this.getModal().hideModal()
		}, this.getModal            = function () {
			return a || (a = new r.Modal()), a
		}, this.init                = function () {
			m.setFilter( "type", "section", ! 0 ), t.on(
				"preview:loaded",
				function () {
					var e = window.elementor.$previewContents, t = setInterval(
						function () {
							(function (e) {
								var t = e.find( p );
								t.length && ! e.find( ".elementor-add-xpro-button" ).length && t.before( d ), e.on( "click.onAddElement", ".elementor-editor-section-settings .elementor-editor-element-add", i )
							})( e ), e.find( ".elementor-add-new-section" ).length > 0 && clearInterval( t )
						},
						100
					);
					e.on( "click.onAddTemplateButton", ".elementor-add-xpro-button", m.showModal.bind( m ) ), this.channels.tabs.on( "change:device", n )
				}.bind( this )
			)
		}, this.getTabs             = function () {
			var e = this.getFilter( "type" ), t = {section: {title: "Blocks"}, page: {title: "Pages"}};
			return _.each(
				t,
				function (i, r) {
					e === r && (t[e].active = ! 0)
				}
			), {tabs: t}
		}, this.getTags             = function () {
			return o
		}, this.getTypeTags         = function () {
			var e = m.getFilter( "type" );
			return l[e]
		}, this.showTemplatesView   = function () {
			m.setFilter( "tags", "", ! 0 ), m.setFilter( "text", "", ! 0 ), s ? m.getModal().showTemplatesView( s ) : m.loadTemplates(
				function () {
					m.getModal().showTemplatesView( s )
				}
			)
		}, this.showPreviewView     = function (e) {
			m.getModal().showPreviewView( e )
		}, this.loadTemplates       = function (e) {
			m.requestLibraryData(
				{
					onBeforeUpdate: m.getModal().showLoadingView.bind( m.getModal() ),
					onUpdate: function () {
						m.getModal().hideLoadingView(), e && e()
					}
					}
			)
		}, this.requestLibraryData  = function (e) {
			if ( ! s || e.forceUpdate) {
				e.onBeforeUpdate && e.onBeforeUpdate();
				var t = {
					data: {}, success: function (t) {
						s = new r.Collections.Template( t.templates ), t.tags && (o = t.tags), t.type_tags && (l = t.type_tags), e.onUpdate && e.onUpdate()
					}
				};
				e.forceSync && (t.data.sync = ! 0), elementorCommon.ajax.addRequest( "get_xpro_elementor_library_data", t )
			} else {
				e.onUpdate && e.onUpdate()
			}
		}, this.requestTemplateData = function (e, t) {
			var i = {unique_id: e, data: {edit_mode: ! 0, display: ! 0, template_id: e}};
			t && jQuery.extend( ! 0, i, t ), elementorCommon.ajax.addRequest( "get_xpro_elementor_template_data", i )
		}, this.insertTemplate      = function (e) {
			var t = e.model, i = this;
			i.getModal().showLoadingView(), i.requestTemplateData(
				t.get( "template_id" ),
				{
					success: function (e) {
						i.getModal().hideLoadingView(), i.getModal().hideModal();
						var r = {};
						- 1 !== i.atIndex && (r.at = i.atIndex), $e.run(
							"document/elements/import",
							{
								model: t,
								data: e,
								options: r
								}
						), i.atIndex = -1
					}, error: function (e) {
						i.showErrorDialog( e )
					}, complete: function (e) {
						i.getModal().hideLoadingView(), window.elementor.$previewContents.find( ".elementor-add-section .elementor-add-section-close" ).click()
					}
					}
			)
		}, this.showErrorDialog     = function (e) {
			if ("object" === _typeof( e )) {
				var t = "";
				_.each(
					e,
					function (e) {
						t += "<div>" + e.message + ".</div>"
					}
				), e  = t
			} else {
				e ? e += "." : e = "<i>&#60;The error message is empty&#62;</i>";
			}
			m.getErrorDialog().setMessage( 'The following error(s) occurred while processing the request:<div id="elementor-template-library-error-info">' + e + "</div>" ).show()
		}, this.getErrorDialog      = function () {
			return c || (c = elementorCommon.dialogsManager.createWidget(
				"alert",
				{
					id: "elementor-template-library-error-dialog",
					headerMessage: "An error occurred"
					}
			)), c
		}
	}, i.library                       = new r.Manager(), i.library.init(), window.xpro = i
}(jQuery, window.elementor, window.xpro || {});
