! function(e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : e("object" == typeof exports ? require("jquery") : window.jQuery || window.Zepto)
}(function(e) {
    var t, o, i, s, a, r, n = "Close",
        p = "BeforeClose",
        l = "MarkupParse",
        f = "Open",
        c = "Change",
        u = "mfp",
        d = "." + u,
        m = "mfp-ready",
        h = "mfp-removing",
        g = "mfp-prevent-close",
        v = function() {},
        y = !!window.jQuery,
        b = e(window),
        w = function(e, o) {
            t.ev.on(u + e + d, o)
        },
        j = function(t, o, i, s) {
            var a = document.createElement("div");
            return a.className = "mfp-" + t, i && (a.innerHTML = i), s ? o && o.appendChild(a) : (a = e(a), o && a.appendTo(o)), a
        },
        C = function(o, i) {
            t.ev.triggerHandler(u + o, i), t.st.callbacks && (o = o.charAt(0).toLowerCase() + o.slice(1), t.st.callbacks[o] && t.st.callbacks[o].apply(t, e.isArray(i) ? i : [i]))
        },
        Q = function(o) {
            return o === r && t.currTemplate.closeBtn || (t.currTemplate.closeBtn = e(t.st.closeMarkup.replace("%title%", t.st.tClose)), r = o), t.currTemplate.closeBtn
        },
        k = function() {
            e.magnificPopup.instance || ((t = new v).init(), e.magnificPopup.instance = t)
        };
    v.prototype = {
        constructor: v,
        init: function() {
            var o = navigator.appVersion;
            t.isLowIE = t.isIE8 = document.all && !document.addEventListener, t.isAndroid = /android/gi.test(o), t.isIOS = /iphone|ipad|ipod/gi.test(o), t.supportsTransition = function() {
                var e = document.createElement("p").style,
                    t = ["ms", "O", "Moz", "Webkit"];
                if (void 0 !== e.transition) return !0;
                for (; t.length;)
                    if (t.pop() + "Transition" in e) return !0;
                return !1
            }(), t.probablyMobile = t.isAndroid || t.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent), i = e(document), t.popupsCache = {}
        },
        open: function(o) {
            var s;
            if (!1 === o.isObj) {
                t.items = o.items.toArray(), t.index = 0;
                var r, n = o.items;
                for (s = 0; s < n.length; s++)
                    if ((r = n[s]).parsed && (r = r.el[0]), r === o.el[0]) {
                        t.index = s;
                        break
                    }
            } else t.items = e.isArray(o.items) ? o.items : [o.items], t.index = o.index || 0;
            if (!t.isOpen) {
                t.types = [], a = "", o.mainEl && o.mainEl.length ? t.ev = o.mainEl.eq(0) : t.ev = i, o.key ? (t.popupsCache[o.key] || (t.popupsCache[o.key] = {}), t.currTemplate = t.popupsCache[o.key]) : t.currTemplate = {}, t.st = e.extend(!0, {}, e.magnificPopup.defaults, o), t.fixedContentPos = "auto" === t.st.fixedContentPos ? !t.probablyMobile : t.st.fixedContentPos, t.st.modal && (t.st.closeOnContentClick = !1, t.st.closeOnBgClick = !1, t.st.showCloseBtn = !1, t.st.enableEscapeKey = !1), t.bgOverlay || (t.bgOverlay = j("bg").on("click" + d, function() {
                    t.close()
                }), t.wrap = j("wrap").attr("tabindex", -1).on("click" + d, function(e) {
                    t._checkIfClose(e.target) && t.close()
                }), t.container = j("container", t.wrap)), t.contentContainer = j("content"), t.st.preloader && (t.preloader = j("preloader", t.container, t.st.tLoading));
                var p = e.magnificPopup.modules;
                for (s = 0; s < p.length; s++) {
                    var c = p[s];
                    c = c.charAt(0).toUpperCase() + c.slice(1), t["init" + c].call(t)
                }
                C("BeforeOpen"), t.st.showCloseBtn && (t.st.closeBtnInside ? (w(l, function(e, t, o, i) {
                    o.close_replaceWith = Q(i.type)
                }), a += " mfp-close-btn-in") : t.wrap.append(Q())), t.st.alignTop && (a += " mfp-align-top"), t.fixedContentPos ? t.wrap.css({
                    overflow: t.st.overflowY,
                    overflowX: "hidden",
                    overflowY: t.st.overflowY
                }) : t.wrap.css({
                    top: b.scrollTop(),
                    position: "absolute"
                }), (!1 === t.st.fixedBgPos || "auto" === t.st.fixedBgPos && !t.fixedContentPos) && t.bgOverlay.css({
                    height: i.height(),
                    position: "absolute"
                }), t.st.enableEscapeKey && i.on("keyup" + d, function(e) {
                    27 === e.keyCode && t.close()
                }), b.on("resize" + d, function() {
                    t.updateSize()
                }), t.st.closeOnContentClick || (a += " mfp-auto-cursor"), a && t.wrap.addClass(a);
                var u = t.wH = b.height(),
                    h = {};
                if (t.fixedContentPos && t._hasScrollBar(u)) {
                    var g = t._getScrollbarSize();
                    g && (h.marginRight = g)
                }
                t.fixedContentPos && (t.isIE7 ? e("body, html").css("overflow", "hidden") : h.overflow = "hidden");
                var v = t.st.mainClass;
                return t.isIE7 && (v += " mfp-ie7"), v && t._addClassToMFP(v), t.updateItemHTML(), C("BuildControls"), e("html").css(h), t.bgOverlay.add(t.wrap).prependTo(t.st.prependTo || e(document.body)), t._lastFocusedEl = document.activeElement, setTimeout(function() {
                    t.content ? (t._addClassToMFP(m), t._setFocus()) : t.bgOverlay.addClass(m), i.on("focusin" + d, t._onFocusIn)
                }, 16), t.isOpen = !0, t.updateSize(u), C(f), o
            }
            t.updateItemHTML()
        },
        close: function() {
            t.isOpen && (C(p), t.isOpen = !1, t.st.removalDelay && !t.isLowIE && t.supportsTransition ? (t._addClassToMFP(h), setTimeout(function() {
                t._close()
            }, t.st.removalDelay)) : t._close())
        },
        _close: function() {
            C(n);
            var o = h + " " + m + " ";
            if (t.bgOverlay.detach(), t.wrap.detach(), t.container.empty(), t.st.mainClass && (o += t.st.mainClass + " "), t._removeClassFromMFP(o), t.fixedContentPos) {
                var s = {
                    marginRight: ""
                };
                t.isIE7 ? e("body, html").css("overflow", "") : s.overflow = "", e("html").css(s)
            }
            i.off("keyup.mfp focusin" + d), t.ev.off(d), t.wrap.attr("class", "mfp-wrap").removeAttr("style"), t.bgOverlay.attr("class", "mfp-bg"), t.container.attr("class", "mfp-container"), !t.st.showCloseBtn || t.st.closeBtnInside && !0 !== t.currTemplate[t.currItem.type] || t.currTemplate.closeBtn && t.currTemplate.closeBtn.detach(), t.st.autoFocusLast && t._lastFocusedEl && e(t._lastFocusedEl).focus(), t.currItem = null, t.content = null, t.currTemplate = null, t.prevHeight = 0, C("AfterClose")
        },
        updateSize: function(e) {
            if (t.isIOS) {
                var o = document.documentElement.clientWidth / window.innerWidth,
                    i = window.innerHeight * o;
                t.wrap.css("height", i), t.wH = i
            } else t.wH = e || b.height();
            t.fixedContentPos || t.wrap.css("height", t.wH), C("Resize")
        },
        updateItemHTML: function() {
            var o = t.items[t.index];
            t.contentContainer.detach(), t.content && t.content.detach(), o.parsed || (o = t.parseEl(t.index));
            var i = o.type;
            if (C("BeforeChange", [t.currItem ? t.currItem.type : "", i]), t.currItem = o, !t.currTemplate[i]) {
                var a = !!t.st[i] && t.st[i].markup;
                C("FirstMarkupParse", a), t.currTemplate[i] = !a || e(a)
            }
            s && s !== o.type && t.container.removeClass("mfp-" + s + "-holder");
            var r = t["get" + i.charAt(0).toUpperCase() + i.slice(1)](o, t.currTemplate[i]);
            t.appendContent(r, i), o.preloaded = !0, C(c, o), s = o.type, t.container.prepend(t.contentContainer), C("AfterChange")
        },
        appendContent: function(e, o) {
            t.content = e, e ? t.st.showCloseBtn && t.st.closeBtnInside && !0 === t.currTemplate[o] ? t.content.find(".mfp-close").length || t.content.append(Q()) : t.content = e : t.content = "", C("BeforeAppend"), t.container.addClass("mfp-" + o + "-holder"), t.contentContainer.append(t.content)
        },
        parseEl: function(o) {
            var i, s = t.items[o];
            if (s.tagName ? s = {
                el: e(s)
            } : (i = s.type, s = {
                data: s,
                src: s.src
            }), s.el) {
                for (var a = t.types, r = 0; r < a.length; r++)
                    if (s.el.hasClass("mfp-" + a[r])) {
                        i = a[r];
                        break
                    }
                s.src = s.el.attr("data-mfp-src"), s.src || (s.src = s.el.attr("href"))
            }
            return s.type = i || t.st.type || "inline", s.index = o, s.parsed = !0, t.items[o] = s, C("ElementParse", s), t.items[o]
        },
        addGroup: function(e, o) {
            var i = function(i) {
                i.mfpEl = this, t._openClick(i, e, o)
            };
            o || (o = {});
            var s = "click.magnificPopup";
            o.mainEl = e, o.items ? (o.isObj = !0, e.off(s).on(s, i)) : (o.isObj = !1, o.delegate ? e.off(s).on(s, o.delegate, i) : (o.items = e, e.off(s).on(s, i)))
        },
        _openClick: function(o, i, s) {
            if ((void 0 !== s.midClick ? s.midClick : e.magnificPopup.defaults.midClick) || !(2 === o.which || o.ctrlKey || o.metaKey || o.altKey || o.shiftKey)) {
                var a = void 0 !== s.disableOn ? s.disableOn : e.magnificPopup.defaults.disableOn;
                if (a)
                    if (e.isFunction(a)) {
                        if (!a.call(t)) return !0
                    } else if (b.width() < a) return !0;
                o.type && (o.preventDefault(), t.isOpen && o.stopPropagation()), s.el = e(o.mfpEl), s.delegate && (s.items = i.find(s.delegate)), t.open(s)
            }
        },
        updateStatus: function(e, i) {
            if (t.preloader) {
                o !== e && t.container.removeClass("mfp-s-" + o), i || "loading" !== e || (i = t.st.tLoading);
                var s = {
                    status: e,
                    text: i
                };
                C("UpdateStatus", s), e = s.status, i = s.text, t.preloader.html(i), t.preloader.find("a").on("click", function(e) {
                    e.stopImmediatePropagation()
                }), t.container.addClass("mfp-s-" + e), o = e
            }
        },
        _checkIfClose: function(o) {
            if (!e(o).hasClass(g)) {
                var i = t.st.closeOnContentClick,
                    s = t.st.closeOnBgClick;
                if (i && s) return !0;
                if (!t.content || e(o).hasClass("mfp-close") || t.preloader && o === t.preloader[0]) return !0;
                if (o === t.content[0] || e.contains(t.content[0], o)) {
                    if (i) return !0
                } else if (s && e.contains(document, o)) return !0;
                return !1
            }
        },
        _addClassToMFP: function(e) {
            t.bgOverlay.addClass(e), t.wrap.addClass(e)
        },
        _removeClassFromMFP: function(e) {
            this.bgOverlay.removeClass(e), t.wrap.removeClass(e)
        },
        _hasScrollBar: function(e) {
            return (t.isIE7 ? i.height() : document.body.scrollHeight) > (e || b.height())
        },
        _setFocus: function() {
            (t.st.focus ? t.content.find(t.st.focus).eq(0) : t.wrap).focus()
        },
        _onFocusIn: function(o) {
            return o.target === t.wrap[0] || e.contains(t.wrap[0], o.target) ? void 0 : (t._setFocus(), !1)
        },
        _parseMarkup: function(t, o, i) {
            var s;
            i.data && (o = e.extend(i.data, o)), C(l, [t, o, i]), e.each(o, function(o, i) {
                if (void 0 === i || !1 === i) return !0;
                if ((s = o.split("_")).length > 1) {
                    var a = t.find(d + "-" + s[0]);
                    if (a.length > 0) {
                        var r = s[1];
                        "replaceWith" === r ? a[0] !== i[0] && a.replaceWith(i) : "img" === r ? a.is("img") ? a.attr("src", i) : a.replaceWith(e("<img>").attr("src", i).attr("class", a.attr("class"))) : a.attr(s[1], i)
                    }
                } else t.find(d + "-" + o).html(i)
            })
        },
        _getScrollbarSize: function() {
            if (void 0 === t.scrollbarSize) {
                var e = document.createElement("div");
                e.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;", document.body.appendChild(e), t.scrollbarSize = e.offsetWidth - e.clientWidth, document.body.removeChild(e)
            }
            return t.scrollbarSize
        }
    }, e.magnificPopup = {
        instance: null,
        proto: v.prototype,
        modules: [],
        open: function(t, o) {
            return k(), (t = t ? e.extend(!0, {}, t) : {}).isObj = !0, t.index = o || 0, this.instance.open(t)
        },
        close: function() {
            return e.magnificPopup.instance && e.magnificPopup.instance.close()
        },
        registerModule: function(t, o) {
            o.options && (e.magnificPopup.defaults[t] = o.options), e.extend(this.proto, o.proto), this.modules.push(t)
        },
        defaults: {
            disableOn: 0,
            key: null,
            midClick: !1,
            mainClass: "",
            preloader: !0,
            focus: "",
            closeOnContentClick: !1,
            closeOnBgClick: !0,
            closeBtnInside: !0,
            showCloseBtn: !0,
            enableEscapeKey: !0,
            modal: !1,
            alignTop: !1,
            removalDelay: 0,
            prependTo: null,
            fixedContentPos: "auto",
            fixedBgPos: "auto",
            overflowY: "auto",
            closeMarkup: '<button title="%title%" type="button" class="mfp-close">&#215;</button>',
            tClose: "Close (Esc)",
            tLoading: "Loading...",
            autoFocusLast: !0
        }
    }, e.fn.magnificPopup = function(o) {
        k();
        var i = e(this);
        if ("string" == typeof o)
            if ("open" === o) {
                var s, a = y ? i.data("magnificPopup") : i[0].magnificPopup,
                    r = parseInt(arguments[1], 10) || 0;
                a.items ? s = a.items[r] : (s = i, a.delegate && (s = s.find(a.delegate)), s = s.eq(r)), t._openClick({
                    mfpEl: s
                }, i, a)
            } else t.isOpen && t[o].apply(t, Array.prototype.slice.call(arguments, 1));
        else o = e.extend(!0, {}, o), y ? i.data("magnificPopup", o) : i[0].magnificPopup = o, t.addGroup(i, o);
        return i
    };
    var x, I, P, T = "inline",
        S = function() {
            P && (I.after(P.addClass(x)).detach(), P = null)
        };
    e.magnificPopup.registerModule(T, {
        options: {
            hiddenClass: "hide",
            markup: "",
            tNotFound: "Content not found"
        },
        proto: {
            initInline: function() {
                t.types.push(T), w(n + "." + T, function() {
                    S()
                })
            },
            getInline: function(o, i) {
                if (S(), o.src) {
                    var s = t.st.inline,
                        a = e(o.src);
                    if (a.length) {
                        var r = a[0].parentNode;
                        r && r.tagName && (I || (x = s.hiddenClass, I = j(x), x = "mfp-" + x), P = a.after(I).detach().removeClass(x)), t.updateStatus("ready")
                    } else t.updateStatus("error", s.tNotFound), a = e("<div>");
                    return o.inlineElement = a, a
                }
                return t.updateStatus("ready"), t._parseMarkup(i, {}, o), i
            }
        }
    });
    var _, O = "ajax",
        z = function() {
            _ && e(document.body).removeClass(_)
        },
        E = function() {
            z(), t.req && t.req.abort()
        };
    e.magnificPopup.registerModule(O, {
        options: {
            settings: null,
            cursor: "mfp-ajax-cur",
            tError: '<a href="%url%">The content</a> could not be loaded.'
        },
        proto: {
            initAjax: function() {
                t.types.push(O), _ = t.st.ajax.cursor, w(n + "." + O, E), w("BeforeChange." + O, E)
            },
            getAjax: function(o) {
                _ && e(document.body).addClass(_), t.updateStatus("loading");
                var i = e.extend({
                    url: o.src,
                    success: function(i, s, a) {
                        var r = {
                            data: i,
                            xhr: a
                        };
                        C("ParseAjax", r), t.appendContent(e(r.data), O), o.finished = !0, z(), t._setFocus(), setTimeout(function() {
                            t.wrap.addClass(m)
                        }, 16), t.updateStatus("ready"), C("AjaxContentAdded")
                    },
                    error: function() {
                        z(), o.finished = o.loadError = !0, t.updateStatus("error", t.st.ajax.tError.replace("%url%", o.src))
                    }
                }, t.st.ajax.settings);
                return t.req = e.ajax(i), ""
            }
        }
    });
    var B, F = function(o) {
        if (o.data && void 0 !== o.data.title) return o.data.title;
        var i = t.st.image.titleSrc;
        if (i) {
            if (e.isFunction(i)) return i.call(t, o);
            if (o.el) return o.el.attr(i) || ""
        }
        return ""
    };
    e.magnificPopup.registerModule("image", {
        options: {
            markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
            cursor: "mfp-zoom-out-cur",
            titleSrc: "title",
            verticalFit: !0,
            tError: '<a href="%url%">The image</a> could not be loaded.'
        },
        proto: {
            initImage: function() {
                var o = t.st.image,
                    i = ".image";
                t.types.push("image"), w(f + i, function() {
                    "image" === t.currItem.type && o.cursor && e(document.body).addClass(o.cursor)
                }), w(n + i, function() {
                    o.cursor && e(document.body).removeClass(o.cursor), b.off("resize" + d)
                }), w("Resize" + i, t.resizeImage), t.isLowIE && w("AfterChange", t.resizeImage)
            },
            resizeImage: function() {
                var e = t.currItem;
                if (e && e.img && t.st.image.verticalFit) {
                    var o = 0;
                    t.isLowIE && (o = parseInt(e.img.css("padding-top"), 10) + parseInt(e.img.css("padding-bottom"), 10)), e.img.css("max-height", t.wH - o)
                }
            },
            _onImageHasSize: function(e) {
                e.img && (e.hasSize = !0, B && clearInterval(B), e.isCheckingImgSize = !1, C("ImageHasSize", e), e.imgHidden && (t.content && t.content.removeClass("mfp-loading"), e.imgHidden = !1))
            },
            findImageSize: function(e) {
                var o = 0,
                    i = e.img[0],
                    s = function(a) {
                        B && clearInterval(B), B = setInterval(function() {
                            return i.naturalWidth > 0 ? void t._onImageHasSize(e) : (o > 200 && clearInterval(B), void(3 === ++o ? s(10) : 40 === o ? s(50) : 100 === o && s(500)))
                        }, a)
                    };
                s(1)
            },
            getImage: function(o, i) {
                var s = 0,
                    a = function() {
                        o && (o.img[0].complete ? (o.img.off(".mfploader"), o === t.currItem && (t._onImageHasSize(o), t.updateStatus("ready")), o.hasSize = !0, o.loaded = !0, C("ImageLoadComplete")) : 200 > ++s ? setTimeout(a, 100) : r())
                    },
                    r = function() {
                        o && (o.img.off(".mfploader"), o === t.currItem && (t._onImageHasSize(o), t.updateStatus("error", n.tError.replace("%url%", o.src))), o.hasSize = !0, o.loaded = !0, o.loadError = !0)
                    },
                    n = t.st.image,
                    p = i.find(".mfp-img");
                if (p.length) {
                    var l = document.createElement("img");
                    l.className = "mfp-img", o.el && o.el.find("img").length && (l.alt = o.el.find("img").attr("alt")), o.img = e(l).on("load.mfploader", a).on("error.mfploader", r), l.src = o.src, p.is("img") && (o.img = o.img.clone()), (l = o.img[0]).naturalWidth > 0 ? o.hasSize = !0 : l.width || (o.hasSize = !1)
                }
                return t._parseMarkup(i, {
                    title: F(o),
                    img_replaceWith: o.img
                }, o), t.resizeImage(), o.hasSize ? (B && clearInterval(B), o.loadError ? (i.addClass("mfp-loading"), t.updateStatus("error", n.tError.replace("%url%", o.src))) : (i.removeClass("mfp-loading"), t.updateStatus("ready")), i) : (t.updateStatus("loading"), o.loading = !0, o.hasSize || (o.imgHidden = !0, i.addClass("mfp-loading"), t.findImageSize(o)), i)
            }
        }
    });
    var M;
    e.magnificPopup.registerModule("zoom", {
        options: {
            enabled: !1,
            easing: "ease-in-out",
            duration: 300,
            opener: function(e) {
                return e.is("img") ? e : e.find("img")
            }
        },
        proto: {
            initZoom: function() {
                var e, o = t.st.zoom,
                    i = ".zoom";
                if (o.enabled && t.supportsTransition) {
                    var s, a, r = o.duration,
                        l = function(e) {
                            var t = e.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),
                                i = "all " + o.duration / 1e3 + "s " + o.easing,
                                s = {
                                    position: "fixed",
                                    zIndex: 9999,
                                    left: 0,
                                    top: 0,
                                    "-webkit-backface-visibility": "hidden"
                                },
                                a = "transition";
                            return s["-webkit-" + a] = s["-moz-" + a] = s["-o-" + a] = s[a] = i, t.css(s), t
                        },
                        f = function() {
                            t.content.css("visibility", "visible")
                        };
                    w("BuildControls" + i, function() {
                        if (t._allowZoom()) {
                            if (clearTimeout(s), t.content.css("visibility", "hidden"), !(e = t._getItemToZoom())) return void f();
                            (a = l(e)).css(t._getOffset()), t.wrap.append(a), s = setTimeout(function() {
                                a.css(t._getOffset(!0)), s = setTimeout(function() {
                                    f(), setTimeout(function() {
                                        a.remove(), e = a = null, C("ZoomAnimationEnded")
                                    }, 16)
                                }, r)
                            }, 16)
                        }
                    }), w(p + i, function() {
                        if (t._allowZoom()) {
                            if (clearTimeout(s), t.st.removalDelay = r, !e) {
                                if (!(e = t._getItemToZoom())) return;
                                a = l(e)
                            }
                            a.css(t._getOffset(!0)), t.wrap.append(a), t.content.css("visibility", "hidden"), setTimeout(function() {
                                a.css(t._getOffset())
                            }, 16)
                        }
                    }), w(n + i, function() {
                        t._allowZoom() && (f(), a && a.remove(), e = null)
                    })
                }
            },
            _allowZoom: function() {
                return "image" === t.currItem.type
            },
            _getItemToZoom: function() {
                return !!t.currItem.hasSize && t.currItem.img
            },
            _getOffset: function(o) {
                var i, s = (i = o ? t.currItem.img : t.st.zoom.opener(t.currItem.el || t.currItem)).offset(),
                    a = parseInt(i.css("padding-top"), 10),
                    r = parseInt(i.css("padding-bottom"), 10);
                s.top -= e(window).scrollTop() - a;
                var n = {
                    width: i.width(),
                    height: (y ? i.innerHeight() : i[0].offsetHeight) - r - a
                };
                return void 0 === M && (M = void 0 !== document.createElement("p").style.MozTransform), M ? n["-moz-transform"] = n.transform = "translate(" + s.left + "px," + s.top + "px)" : (n.left = s.left, n.top = s.top), n
            }
        }
    });
    var L = "iframe",
        A = function(e) {
            if (t.currTemplate[L]) {
                var o = t.currTemplate[L].find("iframe");
                o.length && (e || (o[0].src = "//about:blank"), t.isIE8 && o.css("display", e ? "block" : "none"))
            }
        };
    e.magnificPopup.registerModule(L, {
        options: {
            markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
            srcAction: "iframe_src",
            patterns: {
                youtube: {
                    index: "youtube.com",
                    id: "v=",
                    src: "//www.youtube.com/embed/%id%?autoplay=1"
                },
                vimeo: {
                    index: "vimeo.com/",
                    id: "/",
                    src: "//player.vimeo.com/video/%id%?autoplay=1"
                },
                gmaps: {
                    index: "//maps.google.",
                    src: "%id%&output=embed"
                }
            }
        },
        proto: {
            initIframe: function() {
                t.types.push(L), w("BeforeChange", function(e, t, o) {
                    t !== o && (t === L ? A() : o === L && A(!0))
                }), w(n + "." + L, function() {
                    A()
                })
            },
            getIframe: function(o, i) {
                var s = o.src,
                    a = t.st.iframe;
                e.each(a.patterns, function() {
                    return s.indexOf(this.index) > -1 ? (this.id && (s = "string" == typeof this.id ? s.substr(s.lastIndexOf(this.id) + this.id.length, s.length) : this.id.call(this, s)), s = this.src.replace("%id%", s), !1) : void 0
                });
                var r = {};
                return a.srcAction && (r[a.srcAction] = s), t._parseMarkup(i, r, o), t.updateStatus("ready"), i
            }
        }
    });
    var H = function(e) {
            var o = t.items.length;
            return e > o - 1 ? e - o : 0 > e ? o + e : e
        },
        N = function(e, t, o) {
            return e.replace(/%curr%/gi, t + 1).replace(/%total%/gi, o)
        };
    e.magnificPopup.registerModule("gallery", {
        options: {
            enabled: !1,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            preload: [0, 2],
            navigateByImgClick: !0,
            arrows: !0,
            tPrev: "Previous (Left arrow key)",
            tNext: "Next (Right arrow key)",
            tCounter: "%curr% of %total%"
        },
        proto: {
            initGallery: function() {
                var o = t.st.gallery,
                    s = ".mfp-gallery";
                return t.direction = !0, !(!o || !o.enabled) && (a += " mfp-gallery", w(f + s, function() {
                    o.navigateByImgClick && t.wrap.on("click" + s, ".mfp-img", function() {
                        return t.items.length > 1 ? (t.next(), !1) : void 0
                    }), i.on("keydown" + s, function(e) {
                        37 === e.keyCode ? t.prev() : 39 === e.keyCode && t.next()
                    })
                }), w("UpdateStatus" + s, function(e, o) {
                    o.text && (o.text = N(o.text, t.currItem.index, t.items.length))
                }), w(l + s, function(e, i, s, a) {
                    var r = t.items.length;
                    s.counter = r > 1 ? N(o.tCounter, a.index, r) : ""
                }), w("BuildControls" + s, function() {
                    if (t.items.length > 1 && o.arrows && !t.arrowLeft) {
                        var i = o.arrowMarkup,
                            s = t.arrowLeft = e(i.replace(/%title%/gi, o.tPrev).replace(/%dir%/gi, "left")).addClass(g),
                            a = t.arrowRight = e(i.replace(/%title%/gi, o.tNext).replace(/%dir%/gi, "right")).addClass(g);
                        s.click(function() {
                            t.prev()
                        }), a.click(function() {
                            t.next()
                        }), t.container.append(s.add(a))
                    }
                }), w(c + s, function() {
                    t._preloadTimeout && clearTimeout(t._preloadTimeout), t._preloadTimeout = setTimeout(function() {
                        t.preloadNearbyImages(), t._preloadTimeout = null
                    }, 16)
                }), void w(n + s, function() {
                    i.off(s), t.wrap.off("click" + s), t.arrowRight = t.arrowLeft = null
                }))
            },
            next: function() {
                t.direction = !0, t.index = H(t.index + 1), t.updateItemHTML()
            },
            prev: function() {
                t.direction = !1, t.index = H(t.index - 1), t.updateItemHTML()
            },
            goTo: function(e) {
                t.direction = e >= t.index, t.index = e, t.updateItemHTML()
            },
            preloadNearbyImages: function() {
                var e, o = t.st.gallery.preload,
                    i = Math.min(o[0], t.items.length),
                    s = Math.min(o[1], t.items.length);
                for (e = 1; e <= (t.direction ? s : i); e++) t._preloadItem(t.index + e);
                for (e = 1; e <= (t.direction ? i : s); e++) t._preloadItem(t.index - e)
            },
            _preloadItem: function(o) {
                if (o = H(o), !t.items[o].preloaded) {
                    var i = t.items[o];
                    i.parsed || (i = t.parseEl(o)), C("LazyLoad", i), "image" === i.type && (i.img = e('<img class="mfp-img" />').on("load.mfploader", function() {
                        i.hasSize = !0
                    }).on("error.mfploader", function() {
                        i.hasSize = !0, i.loadError = !0, C("LazyLoadError", i)
                    }).attr("src", i.src)), i.preloaded = !0
                }
            }
        }
    });
    var W = "retina";
    e.magnificPopup.registerModule(W, {
        options: {
            replaceSrc: function(e) {
                return e.src.replace(/\.\w+$/, function(e) {
                    return "@2x" + e
                })
            },
            ratio: 1
        },
        proto: {
            initRetina: function() {
                if (window.devicePixelRatio > 1) {
                    var e = t.st.retina,
                        o = e.ratio;
                    (o = isNaN(o) ? o() : o) > 1 && (w("ImageHasSize." + W, function(e, t) {
                        t.img.css({
                            "max-width": t.img[0].naturalWidth / o,
                            width: "100%"
                        })
                    }), w("ElementParse." + W, function(t, i) {
                        i.src = e.replaceSrc(i, o)
                    }))
                }
            }
        }
    }), k()
}), jQuery(document).ready(function() {
    jQuery(".popup-gallery-twitter").each(function() {
        jQuery(this).magnificPopup({
            delegate: "a.fts-twitter-link-image",
            type: "image",
            tLoading: "Loading image #%curr%...",
            mainClass: "fts-instagram-img-mobile",
            removalDelay: 100,
            mainClass: "fts-instagram-fade",
            gallery: {
                enabled: !0,
                navigateByImgClick: !0,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(e) {
                    return e.el.parents(".fts-tweeter-wrap, .fts-feed-type-twitter").find(".fts-twitter-text, .fts-mashup-description-wrap").html()
                }
            }
        })
    });
    var e = jQuery.magnificPopup.instance;
    jQuery("body").on("click", "#fts-photo-prev", function() {
        e.prev(), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").height() < jQuery(".mfp-img").height() ? jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".mfp-img").height()) : jQuery(".fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-image-position").height())
    }), jQuery("body").on("click", "#fts-photo-next", function() {
        e.next(), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").height() < jQuery(".mfp-img").height() && jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".mfp-img").height())
    }), jQuery("body").on("click", ".fts-facebook-popup .mfp-image-holder .fts-popup-image-position", function() {
        e.next(), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").height() < jQuery(".mfp-img").height() && jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".mfp-img").height())
    }), jQuery("body").on("click", "#fts-photo-prev, #fts-photo-next, .fts-facebook-popup .mfp-image-holder .fts-popup-image-position", function(e) {
        jQuery("body").addClass("fts-using-arrows"), setTimeout(function() {
            jQuery.fn.ftsShare(), /fbcdn.net/i.test(jQuery(".fts-iframe-popup-element").attr("src")) || /scontent.cdninstagram.com/i.test(jQuery(".fts-iframe-popup-element").attr("src")) ? (jQuery("body").addClass("fts-video-iframe-choice"), jQuery(".fts-video-popup-element").show(), jQuery(".fts-iframe-popup-element").attr("src", "").hide()) : (jQuery("body").removeClass("fts-video-iframe-choice, .fts-using-arrows"), jQuery(".fts-video-popup-element").attr("src", "").hide(), jQuery(".fts-iframe-popup-element").show()), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").height() < jQuery(".mfp-img").height() && jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".mfp-img").height()), jQuery(".fts-popup-second-half .fts-greater-than-width-height")[0] ? (console.log("Arrows: Open Callback: Irregular size"), jQuery("iframe.fts-iframe-popup-element").css({
                "max-width": "100%",
                width: jQuery(".fts-popup-half").height()
            }), jQuery(".fts-popup-image-position").css({
                height: "100%",
                "min-height": "auto"
            }), jQuery(".mfp-iframe-scaler").css("padding-top", "100%")) : jQuery(".fts-popup-second-half .fts-equal-width-height")[0] ? (console.log("Arrows: Open Callback: Square size"), jQuery("iframe.fts-iframe-popup-element").css({
                "max-width": "100%",
                width: jQuery(".fts-popup-half").height()
            }), jQuery(".mfp-iframe-scaler").css("padding-top", "")) : (console.log("Arrows: Open Callback: Regular size"), jQuery("iframe.fts-iframe-popup-element").css({
                "max-width": "100%",
                width: "100%"
            }), jQuery(".mfp-iframe-scaler").css("padding-top", "56.0%"), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-half").height()))
        }, 10)
    }), jQuery.fn.slickFacebookPopUpFunction = function() {
        jQuery(".popup-gallery-fb-posts, .popup-gallery-fb, .popup-video-gallery-fb").each(function() {
            var e = jQuery(this).find("a.fts-facebook-link-target, a.fts-fb-large-photo, a.fts-view-album-photos-large, a.fts-view-fb-videos-large, a.fts-view-fb-videos-btn, a.fts-jal-fb-vid-html5video"),
                t = [];
            e.each(function() {
                var e = jQuery(this),
                    o = "image";
                if (e.hasClass("fts-jal-fb-vid-image") || e.hasClass("fts-view-fb-videos-btn")) {
                    o = "iframe";
                    var i = jQuery(this).parents(".fts-fb-photo-post-wrap, .fts-events-list-wrap, .fts-jal-single-fb-post").find(".fts-fb-embed-iframe-check-used-for-popup").html();
                    if (i) var s = i;
                    else s = ""
                } else s = "";
                var a = {
                    src: e.attr("href"),
                    type: o
                };
                r = jQuery(this).parents(".fts-fb-album-additional-pics-content").find(".fts-fb-album-additional-pics-description-wrap").html() ? jQuery(this).parents(".fts-fb-album-additional-pics-content").find(".fts-fb-album-additional-pics-description-wrap").html() : "",
                    n = jQuery(this).parents(".fts-jal-fb-post-time-album").find(".fts-jal-fb-post-time-album").html() ? jQuery(this).parents(".fts-fb-album-additional-pics-content").find(".fts-jal-fb-post-time-album").html() : "";
                a.title = jQuery(this).parents(".fts-events-list-wrap, .fts-jal-single-fb-post").find(".fts-jal-fb-top-wrap").html() + r + n + jQuery(this).parents(".fts-fb-photo-post-wrap, .fts-events-list-wrap, .fts-jal-single-fb-post").find(".fts-likes-shares-etc-wrap").html() + jQuery(this).parents(".fts-fb-photo-post-wrap, .fts-events-list-wrap, .fts-jal-single-fb-post").find(".fts-fb-comments-wrap").html() + s, t.push(a)
            }), e.magnificPopup({
                mainClass: "fts-facebook-popup fts-facebook-styles-popup",
                items: t,
                removalDelay: 150,
                preloader: !1,
                closeOnContentClick: !1,
                closeOnBgClick: !0,
                closeBtnInside: !0,
                showCloseBtn: !1,
                enableEscapeKey: !0,
                autoFocusLast: !1,
                gallery: {
                    enabled: !0,
                    navigateByImgClick: !1,
                    tCounter: '<span class="mfp-counter">%curr% of %total%</span>',
                    preload: [0, 1],
                    arrowMarkup: ""
                },
                type: "image",
                callbacks: {
                    beforeOpen: function() {
                        var t = e.index(this.st.el); - 1 !== t && this.goTo(t)
                    },
                    open: function() {
                        if (console.log("Popup is opened"), jQuery.fn.ftsShare(), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").height() < jQuery(".mfp-img").height() ? jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".mfp-img").height()) : jQuery(".fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-image-position").height()), jQuery(".fts-popup-second-half .fts-greater-than-width-height")[0] ? (console.log("Open Callback: Irregular size"), jQuery("iframe.fts-iframe-popup-element").css({
                            "max-width": "100%",
                            width: jQuery(".fts-popup-half").height()
                        }), jQuery(".mfp-iframe-scaler").css("padding-top", "100%")) : jQuery(".fts-popup-second-half .fts-equal-width-height")[0] ? (console.log("Open Callback: Square size"), jQuery("iframe.fts-iframe-popup-element").css({
                            "max-width": "100%",
                            width: jQuery(".fts-popup-half").height()
                        }), jQuery(".mfp-iframe-scaler").css("padding-top", "")) : (console.log("Open Callback: Regular size"), jQuery("iframe.fts-iframe-popup-element").css({
                            "max-width": "100%",
                            width: "100%"
                        }), jQuery(".mfp-iframe-scaler").css("padding-top", "56.0%"), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-half").height())), matchMedia("only screen and (max-device-width: 736px)").matches) {
                            var e = event.target.id,
                                t = jQuery("#" + e).data("poster");
                            jQuery(".fts-fb-vid-popup video").attr("poster", t), console.log(t)
                        }
                        jQuery("body").addClass("fts-using-arrows")
                    },
                    change: function() {
                        jQuery.fn.ftsShare(), jQuery(window).trigger("resize"), console.log("Content changed"), jQuery("body").hasClass("fts-using-arrows")
                    },
                    imageLoadComplete: function() {},
                    markupParse: function(e, t, o) {
                        if (console.log("Parsing:", e, t, o), !jQuery("body").hasClass("fts-using-arrows")) {
                            var i = o.src;
                            /fbcdn.net/i.test(i) && "image" !== o.type ? jQuery("body").addClass("fts-video-iframe-choice") : jQuery("body").hasClass("fts-using-arrows") || jQuery("body").removeClass("fts-video-iframe-choice")
                        }
                    },
                    afterClose: function() {
                        jQuery("body").removeClass("fts-using-arrows"), console.log("Popup is completely closed")
                    }
                },
                image: {
                    markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half ">               <button title="previous" type="button" id="fts-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position" style="height:591px;">                   <span class="fts-position-helper"></span><div class="mfp-img"></div>       </div>               <button title="next" type="button" id="fts-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button>    </div><div class="fts-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://www.slickremix.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>',
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
                },
                iframe: {
                    markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half ">               <button title="previous" type="button" id="fts-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position"><div class="fts-fb-embed-iframe-check-used-for-popup"></div>                           <div class="mfp-iframe-scaler"><iframe class="mfp-iframe fts-iframe-popup-element" align="middle" frameborder="0" allowTransparency="true" allow="encrypted-media" allowFullScreen="true"></iframe><video class="mfp-iframe fts-video-popup-element" allowfullscreen autoplay controls></video>                           </div>               <button title="next" type="button" id="fts-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button><script>if(jQuery("body").hasClass("fts-video-iframe-choice")){jQuery(".fts-iframe-popup-element").attr("src", "").hide(); } else if(!jQuery("body").hasClass("fts-using-arrows")){jQuery(".fts-video-popup-element").attr("src", "").hide(); }  jQuery(".fts-facebook-popup video").click(function(){jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause")}); <\/script>       </div>    </div><div class="fts-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://www.slickremix.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>',
                    srcAction: "iframe_src"
                }
            })
        })
    }, jQuery.fn.slickFacebookPopUpFunction(), jQuery.fn.slickInstagramPopUpFunction = function() {
        jQuery(".popup-gallery").each(function() {
            var e = jQuery(this).find(".fts-instagram-link-target"),
                t = [];
            e.each(function() {
                var e = jQuery(this);
                if (e.hasClass("fts-jal-fb-vid-image")) o = "iframe";
                else if (e.hasClass("fts-instagram-video-link")) o = "inline";
                else var o = "image";
                if ("inline" == o) var i = "",
                    s = '<video controls width="100%;" style="max-width:100%;" allowfullscreen  controls><source src="' + e.attr("href") + '" type="video/mp4"></video><script>jQuery(".fts-instagram-styles-popup video").get(0).play();jQuery(".fts-instagram-styles-popup video").click(function(){ jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause") });<\/script>';
                else i = e.attr("href"), s = "";
                var a = {
                    src: i,
                    type: o,
                    html5videolink: s
                };
                a.title = jQuery(this).parents(".fts-instagram-wrapper").find(".fts-instagram-popup-profile-wrap").html() + jQuery(this).parents(".fts-instagram-wrapper").find(".slicker-date").html() + jQuery(this).parents(".fts-instagram-wrapper").find(".fts-insta-likes-comments-grab-popup").html() + jQuery(this).parents(".fts-instagram-wrapper").find(".fts-instagram-caption").html(), t.push(a)
            }), e.magnificPopup({
                mainClass: "fts-facebook-popup fts-instagram-styles-popup",
                items: t,
                removalDelay: 150,
                preloader: !1,
                closeOnContentClick: !1,
                closeOnBgClick: !0,
                closeBtnInside: !0,
                showCloseBtn: !1,
                enableEscapeKey: !0,
                autoFocusLast: !1,
                gallery: {
                    enabled: !0,
                    navigateByImgClick: !1,
                    tCounter: '<span class="mfp-counter">%curr% of %total%</span>',
                    preload: [0, 1],
                    arrowMarkup: ""
                },
                callbacks: {
                    beforeOpen: function() {
                        var t = e.index(this.st.el); - 1 !== t && this.goTo(t)
                    },
                    open: function() {
                        console.log("Popup is opened"), jQuery.fn.ftsShare(), jQuery(window).resize(function() {
                            jQuery(".fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-image-position").height())
                        }), jQuery(window).trigger("resize")
                    },
                    change: function() {
                        console.log("Content changed"), console.log(this.content), jQuery.fn.ftsShare(), jQuery("body").hasClass("fts-using-arrows")
                    },
                    imageLoadComplete: function() {
                        jQuery.fn.ftsShare(), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").height() < jQuery(".mfp-img").height() ? jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".mfp-img").height()) : jQuery(".fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-image-position").height())
                    },
                    markupParse: function(e, t, o) {
                        console.log("Parsing:", e, t, o)
                    },
                    afterClose: function() {
                        jQuery("body").removeClass("fts-using-arrows"), console.log("Popup is completely closed")
                    }
                },
                inline: {
                    markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half fts-instagram-popup-half">               <button title="previous" type="button" id="fts-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position">                           <div class="mfp-iframe-scaler mfp-html5videolink" id="fts-html5videolink">                           </div>               <button title="next" type="button" id="fts-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button>       </div>    </div><div class="fts-popup-second-half fts-instagram-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://slickremix.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>'
                },
                image: {
                    markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half fts-instagram-popup-half"> <div // MUST FIGURED OUT WHY THIS IS SHOWING ALL THE DAMN TIME NOW, SOMETHING GOT GOOFED UP IN ONE OF THE LAST PUSHES........THE COMPRESSED VS IS NOT IDENTICAL TO THE FULL SO THAT ALSO NEEDS TO BE SORTED OUT...UUUUUUG................class="fts-carousel-image"></div>               <button title="previous" type="button" id="fts-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position">                   <span class="fts-position-helper"></span><div class="mfp-img"></div>       </div>               <button title="next" type="button" id="fts-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button>    </div><div class="fts-popup-second-half fts-instagram-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://feedthemsocial.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>',
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
                },
                iframe: {
                    markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half fts-instagram-popup-half">               <button title="previous" type="button" id="fts-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position">                           <div class="mfp-iframe-scaler"><iframe class="mfp-iframe fts-iframe-popup-element" frameborder="0" allowfullscreen></iframe><video class="mfp-iframe fts-video-popup-element" allowfullscreen autoplay controls></video>                           </div>               <button title="next" type="button" id="fts-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button><script>if(jQuery("body").hasClass("fts-video-iframe-choice")){jQuery(".fts-iframe-popup-element").attr("src", "").hide(); } else if(!jQuery("body").hasClass("fts-using-arrows")){jQuery(".fts-video-popup-element").attr("src", "").hide(); }  jQuery(".fts-facebook-popup video").click(function(){jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause")});<\/script>       </div>    </div><div class="fts-popup-second-half fts-instagram-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://feedthemsocial.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>',
                    srcAction: "iframe_src"
                }
            })
        })
    }, jQuery.fn.slickInstagramPopUpFunction(), jQuery.fn.slickYoutubePopUpFunction = function() {
        jQuery(".fts-youtube-popup-gallery").each(function() {
            var e = jQuery(this).find("a.fts-yt-popup-open"),
                t = [];
            e.each(function() {
                var e = jQuery(this);
                type = "iframe";
                var o = {
                    src: e.attr("href"),
                    type: type
                };
                o.title = jQuery(this).parents(".slicker-youtube-placeholder").find(".youtube-social-btn-top").html() || jQuery(this).parents(".slicker-youtube-placeholder").find(".entriestitle").html(), t.push(o)
            }), e.magnificPopup({
                mainClass: "fts-facebook-popup fts-facebook-styles-popup fts-youtube-popup",
                items: t,
                removalDelay: 150,
                preloader: !1,
                closeOnContentClick: !1,
                closeOnBgClick: !0,
                closeBtnInside: !0,
                showCloseBtn: !1,
                enableEscapeKey: !0,
                autoFocusLast: !1,
                gallery: {
                    enabled: !0,
                    navigateByImgClick: !1,
                    tCounter: '<span class="mfp-counter">%curr% of %total%</span>',
                    preload: [0, 1],
                    arrowMarkup: ""
                },
                callbacks: {
                    beforeOpen: function() {
                        var t = e.index(this.st.el); - 1 !== t && this.goTo(t)
                    },
                    open: function() {
                        console.log("Popup is opened"), jQuery.fn.ftsShare(), jQuery(window).resize(function() {
                            jQuery(".fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-image-position").height())
                        }), jQuery(window).trigger("resize")
                    },
                    change: function() {
                        console.log("Content changed"), console.log(this.content), jQuery.fn.ftsShare(), jQuery("body").hasClass("fts-using-arrows")
                    },
                    imageLoadComplete: function() {
                        jQuery.fn.ftsShare(), jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").height() < jQuery(".mfp-img").height() ? jQuery(".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".mfp-img").height()) : jQuery(".fts-popup-second-half .mfp-bottom-bar").css("height", jQuery(".fts-popup-image-position").height())
                    },
                    markupParse: function(e, t, o) {
                        console.log("Parsing:", e, t, o)
                    },
                    afterClose: function() {
                        jQuery("body").removeClass("fts-using-arrows"), console.log("Popup is completely closed")
                    }
                },
                iframe: {
                    markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half ">               <button title="previous" type="button" id="fts-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position">                           <div class="mfp-iframe-scaler"><iframe class="mfp-iframe fts-iframe-popup-element" frameborder="0" allowfullscreen></iframe><video class="mfp-iframe fts-video-popup-element" allowfullscreen autoplay controls></video>                           </div>               <button title="next" type="button" id="fts-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button><script>if(jQuery("body").hasClass("fts-video-iframe-choice")){jQuery(".fts-iframe-popup-element").attr("src", "").hide();  } else if(!jQuery("body").hasClass("fts-using-arrows")){jQuery(".fts-video-popup-element").attr("src", "").hide(); }  jQuery(".fts-facebook-popup video").click(function(){jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause")}); <\/script>       </div>    </div><div class="fts-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://slickremix.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>',
                    srcAction: "iframe_src"
                }
            })
        })
    }, jQuery.fn.slickYoutubePopUpFunction()
});