(function() {
    var e = document,
    k = window,
    c = window.__external_files_loaded = window.__external_files_loaded || {},
    s = window.__external_files_loading = window.__external_files_loading || {},
    j = function(u) {
        return u.constructor === Array
    },
    h = {
        autoLoad: false,
        coreLib: [],
        mods: {}
    },
    f = e.getElementsByTagName("script"),
    d = f[f.length - 1],
    b,
    g,
    r = [],
    m = false,
    n = [],
    t = function(w, z, B, v, y) {
        var u = f[0];
        if (!w) {
            return
        };
        if (c[w]) {
            s[w] = false;
            if (v) {
                v(w, y)
            };
            return
        };
        if (s[w]) {
            setTimeout(function() {
                t(w, z, B, v, y)
            },
            1);
            return
        };
        s[w] = true;
        var A, x = z || w.toLowerCase().split(/\./).pop().replace(/[\?#].*/, "");
        if (x === "js") {
            A = e.createElement("script");
            A.setAttribute("type", "text/javascript");
            A.setAttribute("src", w);
            A.setAttribute("async", true)
        } else {
            if (x === "css") {
                A = e.createElement("link");
                A.setAttribute("type", "text/css");
                A.setAttribute("rel", "stylesheet");
                A.setAttribute("href", w);
                c[w] = true
            }
        };
        if (!A) {
            return
        };
        if (B) {
            A.charset = B
        };
        if (x === "css") {
            u.parentNode.insertBefore(A, u);
            if (v) {
                v(w, y)
            };
            return
        };
        A.onload = A.onreadystatechange = function() {
            if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") {
                c[this.getAttribute("src")] = true;
                if (v) {
                    v(this.getAttribute("src"), y)
                };
                A.onload = A.onreadystatechange = null
            }
        };
        u.parentNode.insertBefore(A, u)
    },
    q = function(B) {
        if (!B || !j(B)) {
            return
        };
        var x = 0,
        A, v = [],
        z = h.mods,
        u = [],
        w = {},
        y = function(F) {
            var E = 0,
            C, D;
            if (w[F]) {
                return u
            };
            w[F] = true;
            if (z[F].requires) {
                D = z[F].requires;
                for (; typeof(C = D[E++]) !== "undefined";) {
                    if (z[C]) {
                        y(C);
                        u.push(C)
                    } else {
                        u.push(C)
                    }
                };
                return u
            };
            return u
        };
        for (; typeof(A = B[x++]) !== "undefined";) {
            if (z[A] && z[A].requires && z[A].requires[0]) {
                u = [];
                w = {};
                v = v.concat(y(A))
            };
            v.push(A)
        };
        return v
    },
    a = function() {
        m = true;
        if (r.length > 0) {
            g.apply(this, r);
            r = []
        }
    },
    p = function() {
        if (e.addEventListener) {
            e.removeEventListener("DOMContentLoaded", p, false)
        } else {
            if (e.attachEvent) {
                e.detachEvent("onreadystatechange", p)
            }
        };
        a()
    },
    o = function() {
        if (m) {
            return
        };
        try {
            e.documentElement.doScroll("left")
        } catch(u) {
            return k.setTimeout(o, 1)
        };
        a()
    },
    i = function() {
        if (e.readyState === "complete") {
            return k.setTimeout(a, 1)
        };
        var u = false;
        if (e.addEventListener) {
            e.addEventListener("DOMContentLoaded", p, false);
            k.addEventListener("load", a, false)
        } else {
            if (e.attachEvent) {
                e.attachEvent("onreadystatechange", p);
                k.attachEvent("onload", a);
                try {
                    u = (k.frameElement === null)
                } catch(v) {}
                if (document.documentElement.doScroll && u) {
                    o()
                }
            }
        }
    },
    l = function(u) {
        if (!u || !j(u)) {
            return
        };
        this.queue = u;
        this.current = null
    };
    l.prototype = {
        _interval: 10,
        start: function() {
            var u = this;
            this.current = this.next();
            if (!this.current) {
                this.end = true;
                return
            };
            this.run()
        },
        run: function() {
            var w = this,
            u, v = this.current;
            if (typeof v === "function") {
                v();
                this.start();
                return
            } else {
                if (typeof v === "string") {
                    if (h.mods[v]) {
                        u = h.mods[v];
                        t(u.path, u.type, u.charset,
                        function(x) {
                            w.start()
                        },
                        w)
                    } else {
                        if (/\.js|\.css/i.test(v)) {
                            t(v, "", "",
                            function(x, y) {
                                y.start()
                            },
                            w)
                        } else {
                            this.start()
                        }
                    }
                }
            }
        },
        next: function() {
            return this.queue.shift()
        }
    };
    b = d.getAttribute("data-cfg-autoload");
    if (typeof b === "string") {
        h.autoLoad = (b.toLowerCase() === "true") ? true: false
    };
    b = d.getAttribute("data-cfg-corelib");
    if (typeof b === "string") {
        h.coreLib = b.split(",")
    };
    g = function() {
        var v = [].slice.call(arguments),
        u;
        if (n.length > 0) {
            v = n.concat(v)
        };
        if (h.autoLoad) {
            v = h.coreLib.concat(v)
        };
        u = new l(q(v));
        u.start()
    };
    g.add = function(v, u) {
        if (!v || !u || !u.path) {
            return
        };
        h.mods[v] = u
    };
    g.delay = function() {
        var v = [].slice.call(arguments),
        u = v.shift();
        k.setTimeout(function() {
            g.apply(this, v)
        },
        u)
    };
    g.global = function() {
        var u = [].slice.call(arguments);
        n = n.concat(u)
    };
    g.ready = function() {
        var u = [].slice.call(arguments);
        if (m) {
            return g.apply(this, u)
        };
        r = r.concat(u)
    };
    g.css = function(v) {
        var u = e.getElementById("do-inline-css");
        if (!u) {
            u = e.createElement("style");
            u.type = "text/css";
            u.id = "do-inline-css";
            e.getElementsByTagName("head")[0].appendChild(u)
        };
        if (u.styleSheet) {
            u.styleSheet.cssText = u.styleSheet.cssText + v
        } else {
            u.appendChild(e.createTextNode(v))
        }
    };
    if (h.autoLoad) {
        g(h.coreLib)
    };
    g.define = g.add;
    g._config = h;
    g._mods = h.mods;
    this.Do = g;
    i()
})();