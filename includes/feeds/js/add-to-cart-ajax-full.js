jQuery.fn.ftg_apply_quant_btn = function() {
    setTimeout(function() {
        jQuery(".ft-wp-gallery .quantity input[type=number]").each(function() {
            var a = jQuery(this),
                t = parseFloat(a.attr("max")),
                r = parseFloat(a.attr("min")),
                e = parseInt(a.attr("step"), 10),
                n = jQuery(jQuery("<div />").append(a.clone(!0)).html().replace("number", "text")).insertAfter(a);
            a.remove(), setTimeout(function() {
                if (0 === n.next(".plus").length) {
                    var a = jQuery('<input type="button" value="-" class="minus">').insertBefore(n),
                        o = jQuery('<input type="button" value="+" class="plus">').insertAfter(n);
                    a.on("click", function() {
                        var a = parseInt(n.val(), 10) - e;
                        a = (a = a < 0 ? 0 : a) < r ? r : a, n.val(a).trigger("change")
                    }), o.on("click", function() {
                        var a = parseInt(n.val(), 10) + e;
                        a = a > t ? t : a, n.val(a).trigger("change")
                    })
                }
            }, 10)
        })
    }, 150)
}, jQuery.fn.ftg_apply_quant_btn(), jQuery.fn.ftg_ajax_cart = function() {
    jQuery(document).on("click", ".ft-wp-gallery .single_add_to_cart_button", function(a) {
        if (a.preventDefault(), $variation_form = jQuery(this).closest(".variations_form"), $variation_form.find("input[name=variation_id]").val()) var t = $variation_form.find("input[name=product_id]").val(),
            r = $variation_form.find("input[name=quantity]").val();
        else {
            $simple_form = jQuery(this).closest(".ft-gallery-simple-cart .cart");
            t = $simple_form.find("button[name=add-to-cart]").val(), r = $simple_form.find("input[name=quantity]").val()
        }
        jQuery(".ajaxerrors").remove();
        var e = {},
            n = !0;
        if (variations = $variation_form.find("select[name^=attribute]"), variations.length || (variations = $variation_form.find("[name^=attribute]:checked")), variations.length || (variations = $variation_form.find("input[name^=attribute]")), variations.each(function() {
            var a, t, r = jQuery(this),
                o = r.attr("name"),
                i = r.val();
            r.removeClass("error"), 0 === i.length ? (a = o.lastIndexOf("_"), t = o.substring(a + 1), r.addClass("required error").before('<div class="ajaxerrors"><p>Please select ' + t + "</p></div>"), n = !1) : e[o] = i
        }), !n) return !1;
        var o = jQuery(this);
        if (o.is(".ft-wp-gallery .single_add_to_cart_button")) {
            o.removeClass("added"), o.addClass("loading");
            var i = {
                action: "woocommerce_add_to_cart_variable_rc",
                product_id: t,
                quantity: r,
                variation_id: $variation_form.find("input[name=variation_id]").val(),
                variation: e
            };
            return jQuery("body").trigger("adding_to_cart", [o, i]), jQuery.post(wc_add_to_cart_params.ajax_url, i, function(a) {
                if (a) {
                    console.log(a), console.log("made it");
                    var t = window.location.toString();
                    if (t = t.replace("add-to-cart", "added-to-cart"), a.error && a.product_url) window.location = a.product_url;
                    else if ("yes" !== wc_add_to_cart_params.cart_redirect_after_add) {
                        o.removeClass("loading");
                        var r = a.fragments,
                            e = a.cart_hash;
                        r && jQuery.each(r, function(a) {
                            jQuery(a).addClass("updating")
                        }), jQuery(".shop_table.cart, .updating, .cart_totals").fadeTo("400", "0.6").block({
                            message: null,
                            overlayCSS: {
                                opacity: .6
                            }
                        }), o.addClass("added"), wc_add_to_cart_params.is_cart || 0 !== o.parent().find(".added_to_cart").size() || (console.log("wtf"), o.parent().parent().parent().parent().parent().find(".ft-gallery-simple-price .woocommerce-Price-amount, .ft-gallery-variations-text .woocommerce-Price-amount").addClass("added-to-cart-color"), o.parent().parent().parent().parent().parent().find(".ft-gallery-simple-price, .ft-gallery-variations-text .woocommerce-Price-amount").after(' <span class="ftg-completed-view-cart"> / <a href="' + wc_add_to_cart_params.cart_url + '" class="ftg_added_to_cart wc-forward" title="' + wc_add_to_cart_params.i18n_view_cart + '">' + wc_add_to_cart_params.i18n_view_cart + "</a></span>")), r && jQuery.each(r, function(a, t) {
                            jQuery(a).replaceWith(t)
                        }), jQuery(".widget_shopping_cart, .updating").stop(!0).css("opacity", "1").unblock(), jQuery(".shop_table.cart").load(t + " .shop_table.cart:eq(0) > *", function() {
                            jQuery(".shop_table.cart").stop(!0).css("opacity", "1").unblock(), jQuery(document.body).trigger("cart_page_refreshed")
                        }), jQuery(".cart_totals").load(t + " .cart_totals:eq(0) > *", function() {
                            jQuery(".cart_totals").stop(!0).css("opacity", "1").unblock()
                        }), jQuery(document.body).trigger("added_to_cart", [r, e, o])
                    } else window.location = wc_add_to_cart_params.cart_url
                }
            }), !1
        }
        return !0
    })
}, jQuery.fn.ftg_ajax_cart();