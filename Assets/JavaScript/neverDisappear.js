(function($) {
    var timer;

    $.fn.neverDisappear = function() {
        var $this        = this,
            windowWidth  = $(window).width(),
            contentWidth = $('#content').width(),
            leftWidth    = (windowWidth - contentWidth) / 2,
            margin       = (leftWidth - $this.width()) / 5;

        if(margin >= 15) {
            $this.toggleClass('neverDisappear');
            $this.css('margin-left', margin + 'px');
        } else {
            $this.toggleClass('neverDisappear');
        }

        $(window).resize(function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                $this.neverDisappear();
            }, 100);
        });
    };
}(jQuery));

$(document).ready(function() {
    $('.neverDisappear').neverDisappear();
});