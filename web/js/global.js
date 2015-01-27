(function(win, $) {
    win.G = win.G || {};

    win.G = $.extend({
        init: function() {
            this._initModal();
        },
        // modal默认模板
        modalTemplate: '' +
            '<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">' +
                '<div class="modal-dialog">' +
                    '<div class="modal-content"></div>' +
                '</div>' +
            '</div>',
        // 自动为remote modal注册模板载体
        _initModal: function() {
            var _this = this;
            $('[data-toggle^="modal"]').each(function() {
                var $this = $(this),
                    href = $this.attr('href'),
                    target = $this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')),
                    symbol = target.substr(0, 1);
                if (!href || !target || (symbol !== '.' && symbol !== '#')) return ;
                $(_this.modalTemplate)
                    .attr((symbol == '#' ? 'id' : 'class'), target.substr(1, target.length))
                    .appendTo('body');
            });
        },
        getUrl: function(module, _do, type) {
            return G.baseUrl + '/' + [module, _do, type].join('/')
        }
    }, win.G);

    win.G.init();
})(window, jQuery);