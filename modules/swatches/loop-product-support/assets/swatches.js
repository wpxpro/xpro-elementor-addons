jQuery(document).ready((function (t) {
    t(".xpro_swatches_in_loop").on("click", ".swatch", (function (e) {
        e.preventDefault();
        let s = t(this).closest(".xpro_swatches_in_loop").attr("data-attribute");
        t(this).closest(".xpro_swatches_in_loop").find(".swatch").removeClass("selected"), t(this).toggleClass("selected"), s = JSON.parse(s);
        let a = t(this).attr("data-value"), c = t(this).closest(".product ").find("img"), n = c.attr("src");
        c.removeAttr("srcset"), s[a] !== undefined ? c.attr("src", s[a].src) : c.attr("src", n)
    }))
}));