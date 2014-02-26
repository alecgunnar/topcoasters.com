(function($) {
    $.fn.centerOnScreen = function() {
        var el   = $(this),
            top  = ($(window).height() - el.height()) / 2,
            left = ($(window).width() - el.width()) / 2;

        el.css({
            'postion': 'absolute',
            'top':     top + $(window).scrollTop() + 'px',
            'left':    left + 'px',
        });

        return el;
    };
})(jQuery);