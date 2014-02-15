$.standardAjax = function(data, done, always) {
    data.type        = 'POST';
    data.dataType    = 'json';

    if(typeof data.data != 'undefined') {
        data.data.ajaxRequest = true;
    } else {
        data.data = {ajaxRequest: true};
    }

    return $.ajax(data).done(function(data, textStatus, jqXHR) {
        var resp = {status: 'ok'};

        if(typeof jqXHR.responseText != 'undefined') {
            resp = JSON.parse(jqXHR.responseText);
        }

        if(resp.status == 'ok') {
            done(data, textStatus, jqXHR);
        } else if(resp.status == 'signin') {
            alert('You must sign in to continue!');

            window.location = '/sign-in';
        } else {
            alert('Something went wrong, please try again! (' + resp.status + ')');
        }
    }).fail(function(data, textStatus, jqXHR) {
        alert('Something went wrong, please try again!');
    }).always(always);
};