!function (e) {
    "use strict";
    e.fn.xpro__extend_var_swatch_form = function () {
        return this.each((function () {
            var a = e(this);
            a.addClass("swatches-support").delegate(".swatch", "click", (function (t) {
                t.preventDefault();
                var s = e(this), n = s.closest(".value").find("select"), i = s.attr("data-value");
                if (!s.hasClass("disabled")) {
                    if (n.trigger("focusin"), !n.find('option[value="' + i + '"]').length) return s.siblings(".swatch").removeClass("selected"), n.val("").change(), void a.trigger("xpro__no_matching_variation", [s]);
                    s.hasClass("selected") ? (n.val(""), s.removeClass("selected")) : (s.addClass("selected").siblings(".selected").removeClass("selected"), n.val(i)), n.change()
                }
            })).on("click", ".reset_variations", (function () {
                a.find(".swatch.selected").removeClass("selected"), a.find(".swatch.disabled").removeClass("disabled")
            })).on("woocommerce_update_variation_values", (function () {
                setTimeout((function () {
                    a.find("tbody tr").each((function () {
                        var a = e(this), t = a.find("select").find("option"), s = t.filter(":selected"), n = [];
                        t.each((function (e, a) {
                            "" !== a.value && n.push(a.value)
                        })), a.find(".swatch").each((function () {
                            var a = e(this), t = a.attr("data-value");
                            n.indexOf(t) > -1 ? a.removeClass("disabled") : (a.addClass("disabled"), s.length && t === s.val() && a.removeClass("selected"))
                        }))
                    }))
                }), 100)
            })).on("xpro__no_matching_variation", (function () {
                window.alert(wc_add_to_cart_variation_params.i18n_no_matching_variations_text)
            }))
        }))
    }, e((function () {
        e(".variations_form").xpro__extend_var_swatch_form();
        e(".xpro-qv-popup-wrapper").xpro__extend_var_swatch_form();
    }))
}(jQuery);