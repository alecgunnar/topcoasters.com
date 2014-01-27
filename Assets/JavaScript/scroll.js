(function($) {
    $.fn.scrollToTop = function(icon) {
        var $this = $(this),
            $icon = $(icon);

        $icon.on('click', function() {
            $this.goToTop();
        });

        $this.scroll(function() {
            var windowHeight  = $(this).height(),
                showIconAfter = windowHeight * .75;

            if($this.scrollTop() > showIconAfter) {
                $icon.fadeIn();
            } else {
                $icon.fadeOut();
            }
        });
    };
    
    $.fn.goToTop = function() {
        var $this        = $(this),
            windowHeight = $(this).height(),
            scrollPos    = $this.scrollTop(),
            amount       = scrollPos / 20,
            interval     = setInterval(function() { doScroll(); }, 1),
            time         = 0;

        var eqn = function() {
            return scrollPos - (time * amount);
        };

        var doScroll = function() {
            var position = eqn();

            if(position <= 0) {
                position = 0;

                clearInterval(interval)
            }

            $this.scrollTop(position);

            time++;
        };
    };
}(jQuery));