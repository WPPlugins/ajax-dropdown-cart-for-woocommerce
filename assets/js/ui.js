(function(a, n, t) {
    var m = function(d, k, c) {
        var l = n.width(),
            g = a(document).scrollTop(),
            f, b, e;
        0 < a(".nw-message").length && a(".nw-message").remove();
        a(".ajaxerrors").remove();
        c.removeClass("added");
        c.addClass("loading");
        a.post(woocommerce_params.ajax_url, d, function(h) {
            if (h) {
                var d = window.location.toString(),
                    d = d.replace("add-to-cart", "added-to-cart");
                c.removeClass("loading");
                fragments = h.fragments;
                cart_hash = h.cart_hash;
                fragments && (a.each(fragments, function(b, c) {
                        a(b).replaceWith(c)
                    }), a(".nw-drop-cart").each(function(c,
                        d) {
                        b = a(d).find(".nw-cart-drop-toggle");
                        f = b.offset();
                        paddingLeft = parseInt(b.css("padding-left"), 10);
                        paddingRight = parseInt(b.css("padding-right"), 10);
                        paddingTop = parseInt(b.css("padding-top"), 10);
                        paddingBottom = parseInt(b.css("padding-bottom"), 10);
                        e = a("<span class='nw-message'></span>").text(k);
                        a(d).after(e);
                        f.top < g ? (e.css({
                            left: 0,
                            right: 0,
                            top: 10,
                            width: "40%",
                            position: "fixed"
                        }).stop().animate({
                            opacity: 1
                        }, 100, "easeInOutExpo", function() {}), e.addClass("arrow_top")) : 320 <= l && 568 >= l ? (e.css({
                            left: 0,
                            right: 0,
                            top: f.top +
                                b.height(),
                            width: "60%"
                        }).stop().animate({
                            opacity: 1
                        }, 100, "easeInOutExpo", function() {}), e.addClass("arrow_top")) : e.css({
                            left: f.left - a(".nw-message").width() - parseInt(a(".nw-message").css("padding-left")) - parseInt(a(".nw-message").css("padding-right")) - 10,
                            top: f.top + a(".nw-message").height() / 2
                        }).stop().animate({
                            opacity: 1
                        }, 100, "easeInOutExpo", function() {})
                    }), setTimeout(function() {
                        a(".nw-message").clearQueue().stop().animate({
                            opacity: 0
                        }, 100, "easeInOutExpo", function() {
                            a(".nw-message").remove()
                        })
                    }, 1300),
                    c.is(".ajax-remove-item") ? a(".single_add_to_cart_button").removeClass("added") : c.addClass("added"))
                }
            })
        },
        r = function(d, k) {
            var c = n.width(),
                l = a(document).scrollTop(),
                g, f, b;
            0 < a(".nw-message").length && a(".nw-message").remove();
            d.removeClass("added");
            d.addClass("loading");
            $variation_form = d.closest(".variations_form");
            var e = $variation_form.find("input[name=variation_id]").val(),
                h = $variation_form.find("input[name=product_id]").val(),
                m = $variation_form.find("input[name=quantity]").val();
            a(".ajaxerrors").remove();
            var p = {},
                q = !0;
            $variation_form.find("[name^=attribute]").each(function() {
                var b = a(this),
                    c = b.attr("name"),
                    d = b.val();
                b.removeClass("error");
                0 === d.length ? (d = c.lastIndexOf("_"), c = c.substring(d + 1), b.addClass("required error").before('<div class="ajaxerrors"><p>Please select ' + c + "</p></div>"), q = !1) : p[c] = d
            });
            if (!q) return !1;
            e = {
                action: "woocommerce_add_to_cart_variable_rc",
                product_id: h,
                quantity: m,
                variation_id: e,
                variation: p
            };
            console.log(e);
            a("body").trigger("adding_to_cart", [d, e]);
            a.post(wc_add_to_cart_params.ajax_url, e, function(e) {
                if (e) {
                    var h =
                        window.location.toString(),
                        h = h.replace("add-to-cart", "added-to-cart");
                    d.removeClass("loading");
                    e.error && e.product_url ? window.location = e.product_url : (fragments = e.fragments, cart_hash = e.cart_hash, fragments && (a.each(fragments, function(b, c) {
                        a(b).replaceWith(c)
                    }), a(".nw-drop-cart").each(function(d, e) {
                        f = a(e).find(".nw-cart-drop-toggle");
                        g = f.offset();
                        b = a("<span class='nw-message'></span>").text(k);
                        a(e).after(b);
                        g.top < l ? (b.css({
                            left: 0,
                            right: 0,
                            top: 10,
                            width: "40%",
                            position: "fixed"
                        }).stop().animate({
                                opacity: 1
                            },
                            100, "easeInOutExpo",
                            function() {}), b.addClass("arrow_top")) : 320 <= c && 568 >= c ? (b.css({
                            left: 0,
                            right: 0,
                            top: g.top + f.height(),
                            width: "60%"
                        }).stop().animate({
                            opacity: 1
                        }, 100, "easeInOutExpo", function() {}), b.addClass("arrow_top")) : b.css({
                            left: g.left - a(".nw-message").width() - parseInt(a(".nw-message").css("padding-left")) - parseInt(a(".nw-message").css("padding-right")) - 10,
                            top: g.top + a(".nw-message").height() / 2
                        }).stop().animate({
                            opacity: 1
                        }, 100, "easeInOutExpo", function() {})
                    }), setTimeout(function() {
                        a(".nw-message").clearQueue().stop().animate({
                                opacity: 0
                            },
                            100, "easeInOutExpo",
                            function() {
                                a(".nw-message").remove()
                            })
                    }, 1300), d.addClass("added"), a("body").trigger("added_to_cart", [fragments, cart_hash])))
                }
            })
        };
    a(document).ready(function($) {
        var d = navigator.userAgent,
            k = /WebKit/.test(d) && /Mobile/.test(d);
        a(".nw-drop-cart").each(function(c, d,l) {
            var g = a(d).find(".nw-cart-click .nw-cart-drop-toggle"),
                f = a(d).find(".nw-cart-hover .nw-cart-drop-toggle"),
                b = a(d).find(".nw-cart-drop-content"),
                e = a(d).find(".nw-cart-container");
            g.click(function(c) {
                b.hasClass("nw-hidden") ? b.animate({
                        opacity: 1
                    },
                    300, "easeInQuart").removeClass("nw-hidden") : k && !b.hasClass("nw-hidden") && b.stop().animate({
                    opacity: 0
                }, 300, "easeOutQuart", function() {
                    a(this).addClass("nw-hidden")
                })
            });
            k ? f.click(function(c) {
                b.hasClass("nw-hidden") ? b.animate({
                    opacity: 1
                }, 300, "easeInQuart").removeClass("nw-hidden") : k && !b.hasClass("nw-hidden") && b.stop().animate({
                    opacity: 0
                }, 300, "easeOutQuart", function() {
                    a(this).addClass("nw-hidden")
                })
            }) : f.mouseenter(function(a) {
                b.hasClass("nw-hidden") && b.animate({
                    opacity: 1
                }, 300, "easeInQuart").removeClass("nw-hidden")
            });
            e.mouseleave(function(c) {
                b.hasClass("nw-hidden") || b.stop().animate({
                    opacity: 0
                }, 300, "easeOutQuart", function() {
                    a(this).addClass("nw-hidden")
                })
            })
            

        });

        a(document).on("click", ".single_add_to_cart_button", function(c,l,q) {
            l=a(this);
            if(l.is(".disabled")){

            }else{
                a('html, body').animate({
                    'scrollTop' : a(".cart_anchor").position().top
                });
                c.preventDefault();
                var cart = a('.cart_anchor');
                var imgtodrag = a('.woocommerce-main-image , .images').find("img").eq(0);
                var widths= imgtodrag.width();
                var heights= imgtodrag.height();
                if (imgtodrag) {
                    var imgclone = imgtodrag.clone()
                        .offset({
                        top: imgtodrag.offset().top,
                        left: imgtodrag.offset().left
                    })
                        .css({
                        'opacity': '0.5',
                            'position': 'absolute',
                            'height': heights,
                            'width': widths,
                            'z-index': '100'
                    })
                        .appendTo(a('body'))
                        .animate({
                        'top': cart.offset().top - parseInt(nb_script_vars.y),
                            'left': cart.offset().left + parseInt(nb_script_vars.x),
                            'width': 75,
                            'height': 75
                    }, parseInt(nb_script_vars.speed), 'easeInOutExpo');
                    
                    setTimeout(function () {
                        cart.animate( {
                            opacity:'0.3'
                        }, 'fast');
                        cart.animate( {
                            opacity:'1'
                        }, 'fast');
                        cart.animate( {
                            opacity:'0.3'
                        }, 'fast');
                        cart.animate( {
                            opacity:'1'
                        }, 'fast');
                    }, parseInt(nb_script_vars.speed)*1.5);

                    imgclone.animate({
                        'width': 0,
                            'height': 0
                    }, function () {
                        a(this).detach()
                    });
                };
                c = a(this);
                if (c.is(".product-type-variable .single_add_to_cart_button")) r(c, "Added to cart");
                else {
                    var q = jQuery(".quantity").find("input[name=quantity]").val();
                    var d = {
                        action: "add_to_cart_single",
                        product_id: c.prev().val(),
                        quantity: q
                    };
                    console.log(d);
                    m(d, "Added to cart", c)
                }
            }
        });
        a(document).on("click", ".ajax-remove-item", function(c) {
            c.preventDefault();
            c = a(this);
            var d = {
                action: "woocommerce_remove_from_cart",
                remove_item: c.attr("rel")
            };
            m(d, "Removed from cart", c)
        });
        a(".add_to_cart_button").on("click", function() {
            a('html, body').animate({
                'scrollTop' : a(".cart_anchor").position().top
            },parseInt(nb_script_vars.speed));
            var cart = a('.cart_anchor');
            var imgtodrag1 = a(this).parent('.product-type-simple').find("img").eq(0);
            var widtha= imgtodrag1.width();
            var heighta= imgtodrag1.height();
            if (imgtodrag1) {
                // alert(.width + 'x' + .height);
                
                var imgclone1 = imgtodrag1.clone()
                    .offset({
                    top: imgtodrag1.offset().top,
                    left: imgtodrag1.offset().left
                })
                    .css({
                    'opacity': '0.5',
                        'position': 'absolute',
                        'height': heighta,
                        'width': widtha,
                        'z-index': '100'
                })
                    .appendTo(a('body'))
                    .animate({
                    'top': cart.offset().top - parseInt(nb_script_vars.y),
                        'left': cart.offset().left + parseInt(nb_script_vars.x),
                        'width': 75,
                        'height': 75
                }, parseInt(nb_script_vars.speed), 'easeInOutExpo');
                
                setTimeout(function () {
                    cart.animate( {
                        opacity:'0.3'
                    }, 'fast');
                    cart.animate( {
                        opacity:'1'
                    }, 'fast');
                    cart.animate( {
                        opacity:'0.3'
                    }, 'fast');
                    cart.animate( {
                        opacity:'1'
                    }, 'fast');
                }, parseInt(nb_script_vars.speed));

                imgclone1.animate({
                    'width': 0,
                        'height': 0
                },function () {
                a(this).detach()

                });
            }
            
        });

    })

})(jQuery, jQuery(window), jQuery(document));
