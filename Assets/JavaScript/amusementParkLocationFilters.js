function lookForMiniMaxes() {
    $('#database-amusementparks').find('a.minimax').each(function() {
        var $this  = $(this),
            $group = $this.parent().parent();

        $group.children('.locations').children('.field').children('label').children('input[type="checkbox"]').each(function() {
            $(this).on('click', function() {
                $(this).parent().parent().toggleClass('noHide').toggleClass('dontHide');
            });
        });

        $this.parent().on('click', function() {
            if($this.text() == 'Show') {
                $this.text('Hide');
                $group.children('.locations').children('.field').show();
            } else {
                $this.text('Show');
                $group.children('.locations').children('.field').not('.noHide').hide();
            }
        }).css('cursor', 'pointer');
    });
}