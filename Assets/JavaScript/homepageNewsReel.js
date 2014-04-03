(function($) {
    var obj          = null,
        topics       = new Array(),
        currentPos   = 0,
        newsreelLink = $('<a></a>'),
        methods      = {};
    
    methods = {
        compose: function() {
            if(topics.length) {
                obj.text('');
                methods.showNext();
            }
        },
        showNext: function() {
            newsreelLink.html(topics[currentPos].title)
                .attr('href', topics[currentPos].url);
            
            if(newsreelLink.parent().length == 0) {
                newsreelLink.appendTo(obj)
            } else {
                newsreelLink.fadeIn();
            }
            
            setTimeout(function() {
                newsreelLink.fadeOut(function() {
                    currentPos = (currentPos + 1) % topics.length;
                    
                    methods.showNext();
                });
            }, 5000);
        }
    };
    
    $.fn.composeNewsreel = function(newsreelTopics) {
        obj    = $(this);
        topics = newsreelTopics;
        
        methods.compose();
    };
})(jQuery);