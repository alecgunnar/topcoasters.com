var ratings = {
    five: 5,
    fourHalf: 4.5,
    four: 4,
    threeHalf: 3.5,
    three: 3,
    twoHalf: 2.5,
    two: 2,
    oneHalf: 1.5,
    one: 1,
    half: .5,
    zero: 0
};

function checkMessage() {
    if($('#redirectWrapper #redirectMessage').text() != '') {
        $('#redirectWrapper').show();

        setTimeout(function() {
            $('#redirectWrapper').fadeOut();
        }, 5000);
    }
}

function hideMessage() {
    if($('#redirectWrapper #redirectMessage').text() != '') {
        setTimeout(function() {
            $('#redirectWrapper').fadeOut();
        }, 5000);
    } else {
        $('#redirectWrapper').hide();
    }
}

function showMessage(msg) {
    $('#redirectWrapper #redirectMessage').text(msg);

    $('#redirectWrapper').fadeIn();
    
    hideMessage();
}

function lookForFacebookLoginButtons() {
    $('.facebookLogin, .facebookLoginMini a').on('click', function(e) {
        e.preventDefault();

        doFacebookLogin($(this).attr('href'));
    });
}

function lookForRatings() {
    $('.rating').showRatingStars();
}

$(document).ready(function() {
    checkMessage();
    lookForFacebookLoginButtons();
    lookForRatings();
    lookForMiniMaxes();

    $(window).scrollToTop('#scrollToTop');
});