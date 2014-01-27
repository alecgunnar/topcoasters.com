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

function setupSearchField() {
    var $clearField       = $('#header #search .clearField'),
        showingClearField = false,
        clickedClear      = false,
        $searchField      = $('#header #search input'),
        searchStartWidth  = $searchField.css('width'),
        searchMaxWidth    = $searchField.css('max-width');

    $clearField.on('mousedown', function() {
        clickedClear = true;
    }).on('click', function(e) {
        $searchField.val('').focus().keyup();
    });

    $searchField.on('click', function(e) {
        if($searchField.attr('expanded') != 'true') {
            $searchField.css('width', searchMaxWidth).attr('expanded', 'true');
        }

        if($searchField.val()) {
            $searchField.keyup();
        }
    }).on('keyup', function(e) {
        if(!showingClearField) {
            $clearField.fadeIn(200);

            showingClearField = true;
        } else if(!$searchField.val()) {
            $clearField.fadeOut(200);

            showingClearField = false;
        }
    });

    $searchField.on('blur', function(e) {
        if($searchField.attr('expanded') != 'false' && !clickedClear) {
            if(showingClearField) {
                $clearField.fadeOut(200);

                showingClearField = false;
            }

            $searchField.css('width', searchStartWidth).attr('expanded', 'false');
        }

        clickedClear = false;
    });
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
    setupSearchField();
    hideMessage();
    lookForFacebookLoginButtons();
    lookForRatings();
    lookForMiniMaxes();

    $(window).scrollToTop('#scrollToTop');
});