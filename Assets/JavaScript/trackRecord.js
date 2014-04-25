var working     = false,
    workingOn   = 0,
    saving      = false,
    timesRidden = 0,
    rating      = 0;

var riddenField = '<input type="number" id="riddenEntry" style="width: 100px" />',
    ratingField = '<select id="ratingEntry"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>';

$(document).ready(function() {
    $('#trackrecord').find('.editRecord').each(function() {
        var $this = $(this);

        $this.children('a').each(function(index) {
            var a  = $(this),
                tr = a.parent().parent();

            if(workingOn != tr.attr('favorite')) {
                if(index) {
                    a.on('click', function(e) {
                        e.preventDefault();
                        removeRecord(tr);
                    });
                } else {
                    a.on('click', function(e) {
                        e.preventDefault();
                        editRecord(tr);
                    });
                }
            }
        });
    });
});

function editRecord(tr) {
    if(working === true) {
        alert('You are still editing another record, please save before editing another!');

        return;
    }

    working   = true;
    workingOn = tr.attr('favorite');

    tr.children('.editRecord').children('a').css('display', 'inline');

    timesRidden = tr.children('.fav_ridden').text();
    rating      = tr.children('.fav_rating').children('.rating').attr('rating');

    tr.children('.fav_ridden').html($(riddenField).val(timesRidden));
    tr.children('.fav_rating').html($(ratingField).val(rating));

    getForwardButton(tr).text('Save').unbind('click').on('click', function(e) {
        e.preventDefault();
        saveRecord(tr);
    });

    getBackwardButton(tr).text('Cancel').unbind('click').on('click', function(e) {
        e.preventDefault();
        finish(tr, timesRidden, rating);
    });
}

function removeRecord(tr) {
    var confirmation = confirm('Are you sure you want to remove this roller coaster from your track record?');

    if(!confirmation) {
        return;
    }

    $.standardAjax({
        url: '/track-record/' + getWhat(tr) + '/remove/' + tr.attr('favorite')
    }, function() {
        showMessage('Your track record has been updated.');
        tr.remove();
    });
}

function saveRecord(tr) {
    var riddenEntryValue = $('#riddenEntry').val(),
        ratingEntryValue = $('#ratingEntry').val();

    if((riddenEntryValue != timesRidden && typeof riddenEntryValue != 'undefined') || ratingEntryValue != rating) {
        if(saving) {
            return;
        }

        saving = true;

        if(riddenEntryValue <= 0) {
            alert('Your value for the number of times ridden is invalid.');

            return;
        }

        if(riddenEntryValue < timesRidden) {
            var confirmLess = confirm('You entered a value for the times ridden field which was less then what was there before. Is that correct?');

            if(!confirmLess) {
                return false;
            }
        }

        var saveData = {rating: ratingEntryValue};

        if(typeof riddenEntryValue != 'undefined') {
            saveData.timesRidden = riddenEntryValue;
        }

        $.standardAjax({
            url:  '/track-record/' + getWhat(tr) + '/save/' + tr.attr('favorite'),
            data: saveData
        }, function() {
            showMessage('Your track record has been updated.');
            finish(tr, riddenEntryValue, ratingEntryValue);
        }, function() {
            release();
        });
    } else {
        alert('You have not made any changes.');
    }
}

function finish(tr, riddenVal, ratingVal) {
    tr.children('.editRecord').children('a').css('display', '');
    tr.children('.fav_ridden').html(riddenVal);
    tr.children('.fav_rating').html($('<div class="rating" rating="' + ratingVal + '"></div>').showRatingStars());

    getForwardButton(tr).text('Edit').unbind('click').on('click', function(e) {
        e.preventDefault();
        editRecord(tr);
    });

    getBackwardButton(tr).text('Remove').unbind('click').on('click', function(e) {
        e.preventDefault();
        removeRecord(tr);
    });

    working = false;
}

function release() {
    working = false;
    saving  = false;
}

function editRecordOnLoad(id) {
    var tr = $('tr[favorite="' + id + '"]'),
        a  = tr.children('.editRecord').children('a:first');

    editRecord(tr);
}

function getForwardButton(tr) {
    return tr.children('.editRecord').children('a:first');
}

function getBackwardButton(tr) {
    return tr.children('.editRecord').children('a:last');
}

function getWhat(tr) {
    if(tr.attr('favorite-type') == 'coaster') {
        return 'coasters';
    } else {
        return 'parks';
    }
}