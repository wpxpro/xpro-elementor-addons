jQuery(window).on("elementor:init", (function () {

    "use strict";
    jQuery(".eicon-close").on("click", (function () {
        jQuery(".widgetarea_iframe_modal").css("display", "none")
    }));
    var e = elementor.modules.controls.BaseData, t = e.extend({
        ui: function () {
            var t = e.prototype.ui.apply(this, arguments);
            return t.inputs = '[type="text"]', t
        }, events: function () {
            return _.extend(e.prototype.events.apply(this, arguments), {"change @ui.inputs": "onBaseInputChange"})
        }, onBaseInputChange: function (e) {
            clearTimeout(this.correctionTimeout);
            var t = e.currentTarget, n = this.getInputValue(t);
            this.validators.slice(0), this.elementSettingsModel.validators[this.model.get("name")];
            this.updateElementModel(n, t)
        }, onDestroy: function () {
            clearInterval(window.xproWidgetAreaInterval)
        }, onRender: function () {
            e.prototype.onRender.apply(this, arguments);
            var t = this;
            clearInterval(window.xproWidgetAreaInterval), window.xproWidgetAreaInterval = setInterval((function () {
                var e = jQuery("body").attr("data-xpro-widgetarea-load"),
                    n = jQuery("body").attr("data-xpro-widgetarea-key");
                if ("true" == e && 1 == t.isRendered) {
                    var a, i = (new Date).getTime(), r = n.split("***");
                    a = (r = r[0]) + "***" + i, jQuery("body").attr("data-xpro-widgetarea-load", "false"), t.setValue(a)
                }
            }), 1e3)
        }
    }, {});
    elementor.addControlView("widgetarea", t)
}));
