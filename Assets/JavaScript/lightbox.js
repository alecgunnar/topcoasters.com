(function($) {
    $.lightboxShow = function(el) {
        var img       = $(el),
            image     = null,
            imgView   = null,
            padding   = 10,
            methods   = {};

        methods = {
            setup: function() {
                image        = new Image();
                image.onload = function() {
                    var resized = methods.checkDimensions(this.width, this.height, $('#content').width() - 20);
                    resized     = methods.checkDimensions(resized.b, resized.a, $(window).height() - 200);

                    imgView = $('<img id="lightbox-image" />').attr({
                        src:    this.src,
                        width:  resized.b + 'px',
                        height: resized.a + 'px',
                    });

                    methods.setClick();
                };

                image.src = img.attr('src');
            },
            setClick: function() {
                img.on('click', function(event) {
                    event.stopPropagation();

                    methods.prepLightbox();
                });
            },
            prepLightbox: function() {
                $('html').css({'min-height': '100%'});
                $('body').css({'height': '100%'});

                var fade = $('<div id="lightbox-body-fade"></div>').css({
                    background:   '#000',
                    position:     'absolute',
                    width:        '100%',
                    height:       $('body').height(),
                    opacity:      '.6',
                    display:      'none',
                    'z-index':    '10000'
                });

                fade.prependTo('body');
                fade.fadeIn(methods.showLightbox);

                $('body').on('click', methods.closeLightbox);
            },
            showLightbox: function() {
                var lightbox = $('<div id="lightbox-viewer"></div>').css({
                    position:     'absolute',
                    background:   '#FFF',
                    padding:      padding + 'px',
                    width:        0,
                    height:       0,
                    'box-sizing': 'border-box',
                    'z-index':    '10001'
                }), closeButton = $('<a href="#" id="lightbox-close">Close Window</a>').css({
                    'line-height':     '30px',
                    display:           'none',
                    color:             '#000',
                    'text-decoration': 'none',
                    'font-weight':     '400',
                    'float':           'right'
                });

                lightbox.centerOnScreen().on('click', function(event) {
                    event.stopPropagation();
                });

                closeButton.prependTo(lightbox);
                imgView.prependTo(lightbox).css('display', 'none');
                lightbox.prependTo('body');

                $('#lightbox-close').on('click', function(event) {
                    event.preventDefault();

                    methods.closeLightbox();
                });

                methods.openLightbox();
            },
            openLightbox: function() {
                var openToWidth  = imgView.width() + (padding * 2),
                    openToHeight = imgView.height() + (padding * 2) + 30,
                    goUp         = ($(window).height() - openToHeight) / 2,
                    goLeft       = ($(window).width() - openToWidth) / 2; 

                $('#lightbox-viewer').animate({
                    width:  openToWidth,
                    left:   goLeft
                }, 250, function() {
                    $('#lightbox-viewer').animate({
                        height: openToHeight,
                        top:    goUp + $(window).scrollTop()
                    }, 100, function() {
                        $('#lightbox-image').fadeIn();
                        $('#lightbox-close').show();
                    });
                });
            },
            closeLightbox: function() {
                
                $('#lightbox-viewer').fadeOut(function(event) {
                    $('#lightbox-viewer').remove();

                    $('#lightbox-body-fade').fadeOut(function(event) {
                        $('#lightbox-body-fade').remove();
                    });
                });
            },
            checkDimensions: function(check, other, max) {
                if(check > max) {
                    diff   = max / check;
                    check  = max;
                    other *= diff;
                }

                return {a: check, b: other};
            }
        };

        methods.setup();
    };

    $.fn.lightbox = function() {
        this.each(function() {
            $.lightboxShow(this);
        });
    };
})(jQuery);