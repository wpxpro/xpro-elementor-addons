jQuery(document).ready(function(a){a(".xpro_swatches_in_loop").on("click",".swatch",function(e){e.preventDefault();let b=a(this).closest(".xpro_swatches_in_loop").attr("data-attribute");a(this).closest(".xpro_swatches_in_loop").find(".swatch").removeClass("selected"),a(this).toggleClass("selected"),b=JSON.parse(b);let d=a(this).attr("data-value"),c=a(this).closest(".product ").find("img"),f=c.attr("src");c.removeAttr("srcset"),void 0!==b[d]?c.attr("src",b[d].src):c.attr("src",f)})})