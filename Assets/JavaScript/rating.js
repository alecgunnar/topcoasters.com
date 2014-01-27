(function($) {
    $.fn.showRatingStars = function() {
        return $(this).each(function() {
            var rating = $(this).attr('rating');
    
            for(var minRating in ratings) {
                if(ratings[minRating] <= rating) {
                    $(this).addClass(minRating);
                    break;
                }
            }
        });
    };
}(jQuery));