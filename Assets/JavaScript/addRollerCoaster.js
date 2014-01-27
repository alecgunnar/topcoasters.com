$(document).ready(function() {
    var parent     = $('#addLiftButton').parent().parent(),
        liftsGroup = parent.children('.fieldGroup'),
        lift       = liftsGroup.children('.field:first'),
        numLifts   = liftsGroup.children('.field').length + 1,
        removeLink = $('<div class="right"><a href="#" class="redLink">Remove</a></div>');

    liftsGroup.children('.field').each(function(index) {
        if(!index) {
            return;
        }

        var $this      = $(this),
            clonedLink = removeLink.clone();

        clonedLink.on('click', function(e) {
            e.preventDefault();

            removeLiftRow($this);
        });

        clonedLink.prependTo($this);
    });

    function removeLiftRow(row) {
        var confirmRemove = confirm('Are you sure you would like to remove this launch/lift?');

        if(confirmRemove) {
            row.slideUp(function() {
                $(this).remove();
            });
        }
    }

    $('#database-contribute-rollercoaster').find('#addLiftButton').on('click', function() {
        var $this    = $(this),
            newLift  = lift.clone();

        newLift.children('select:first').attr('name', 'rollerCoaster[trackAndLayout][transports][transport_type_' + numLifts + ']').val(0);
        newLift.children('input[type="number"]').attr('name', 'rollerCoaster[trackAndLayout][transports][transport_speed_' + numLifts + ']').val('');
        newLift.children('select:last').attr('name', 'rollerCoaster[trackAndLayout][transports][transport_reverse_' + numLifts + ']');

        var clonedLink = removeLink.clone();
        
        clonedLink.children('a').on('click', function(e) {
            e.preventDefault();

            removeLiftRow(newLift);
        });

        clonedLink.prependTo(newLift);

        numLifts++;

        newLift.appendTo(liftsGroup);
    });
});