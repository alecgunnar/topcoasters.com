(function($) {
    $.fn.confirmClick = function() {
        this.on('click', function(e) {
            var conf = confirm("Are you sure you would like to continue?");

            if(conf != true) {
                e.preventDefault();
            }
        });
    };
})(jQuery);

$(document).ready(function() {
    $('*[confirm-click="true"]').confirmClick();
});