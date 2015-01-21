(function(win, $) {
    win.G = win.G || {};

    win.G = $.extend({
        getApiUrl: function(module, _do, type) {
            return G.baseUrl + '/' + [module, 'api', _do, type].join('/')
        }
    }, win.G);
})(window, jQuery);