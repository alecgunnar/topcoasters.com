(function ($) {
    var tabGroups = new Array(),
        activeRe  = /( )?active/;

    $.fn.tabs = function() {
        var $this = this,
            id    = $this.attr('id');

        if(typeof tabGroups[id] != 'undefined') {
            return;
        }

        tabGroups[id] = new Array();

        tabGroups[id]['current'] = new Array(null, null);
        tabGroups[id]['bodies']  = new Array();

        $this.children('.tabContent').children('.tabBody').each(function(index) {
            tabGroups[id]['bodies'][index] = $(this);

            if(activeRe.exec($(this).attr('class'))) {
                tabGroups[id]['current'][1] = $(this);
            }
        });

        $this.children('.tabMenu').children('ul').children('li').each(function(index) {
            if(activeRe.exec($(this).children('a').attr('class'))) {
                tabGroups[id]['current'][0] = $(this).children('a');
            }
        });

        if(tabGroups[id]['current'][0] == null || tabGroups[id]['current'][1] == null) {
            tabGroups[id]['current'][0] = $this.children('.tabMenu').children('ul').children('li:first-child').children('a').toggleClass('active');
            tabGroups[id]['current'][1] = $this.children('.tabContent').children('.tabBody:first-child').toggleClass('active');
        }

        $this.children('.tabMenu').children('ul').children('li').each(function(index) {
            $(this).children('a').on('click', function(e) {
                e.preventDefault();

                tabGroups[id]['current'][0].toggleClass('active');
                tabGroups[id]['current'][1].toggleClass('active');

                $(this).toggleClass('active');
                tabGroups[id]['current'][0] = $(this);

                tabGroups[id]['bodies'][index].toggleClass('active');
                tabGroups[id]['current'][1] = tabGroups[id]['bodies'][index];
            });
        });
    };
}(jQuery));