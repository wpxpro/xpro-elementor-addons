let frame, swatchConf = swatch_conf || {};
jQuery(document).ready((function (e) {
    "use strict";
    let t = window.wp, n = e("body");
    e("#term-color").wpColorPicker(), n.on("click", ".xpro_upload_img_button", (function (n) {
        n.preventDefault();
        let i = e(this);
        frame || (frame = t.media.frames.downloadable_file = t.media({
            title: swatchConf.i18n.title,
            button: {text: swatchConf.i18n.button},
            multiple: !1
        }), frame.on("select", (function () {
            let e = frame.state().get("selection").first().toJSON();
            i.siblings("input.xpro_term_img").val(e.id), i.siblings(".xpro_remove_img_btn").show(), "undefined" != typeof e.sizes.thumbnail ? i.parent().prev(".xpro_term_img_thumbnail").find("img").attr("src", e.sizes.thumbnail.url) : i.parent().prev(".xpro_term_img_thumbnail").find("img").attr("src", e.url)
        }))), frame.open()
    })).on("click", ".xpro_remove_img_btn", (function () {
        let t = e(this);
        return t.siblings("input.xpro_term_img").val(""), t.siblings(".xpro_remove_img_btn").hide(), t.parent().prev(".xpro_term_img_thumbnail").find("img").attr("src", swatchConf.dummy), !1
    }));
    let i = e("#xpro_tpl_modal"), s = i.find(".spinner"), o = i.find(".message"), a = null;

    function r() {
        i.find(".xpro_term__name input, .xpro_term__slug input").val(""), s.removeClass("is-active"), o.removeClass("error success").hide(), i.hide()
    }

    n.on("click", ".xpro_assign_new_attribute", (function (n) {
        n.preventDefault();
        let s = e(this), o = t.template("xpro__tpl_input__tax"),
            r = {type: s.data("type"), tax: s.closest(".woocommerce_attribute").data("taxonomy")};
        i.find(".xpro_term__swatch").html(e("#tmpl-xpro__tpl_input__" + r.type).html()), i.find(".xpro_term__tax").html(o(r)), "color" == r.type && i.find("input.xpro_input__color").wpColorPicker(), a = s.closest(".woocommerce_attribute.wc-metabox"), i.show()
    })).on("click", ".xpro_modal__close, .xpro_modal__backdrop", (function (e) {
        e.preventDefault(), r()
    })), n.on("click", ".xpro_add_attribute_submit", (function (n) {
        n.preventDefault();
        e(this).data("type");
        let l = !1, _ = {};
        i.find(".xpro__input").each((function () {
            let t = e(this);
            "slug" === t.attr("name") || t.val() ? t.removeClass("error") : (t.addClass("error"), l = !0), _[t.attr("name")] = t.val()
        })), l || (s.addClass("is-active"), o.hide(), t.ajax.send("xpro_add_new_attribute", {
            data: _,
            error: function (e) {
                s.removeClass("is-active"), o.addClass("error").text(e).show()
            },
            success: function (e) {
                s.removeClass("is-active"), o.addClass("success").text(e.msg).show(), a.find("select.attribute_values").append('<option value="' + e.id + '" selected="selected">' + e.name + "</option>"), a.find("select.attribute_values").change(), r()
            }
        }))
    }))
}));