window.fbAsyncInit = function() {
    FB.init({appId      : '318806675389',
             status     : true,
             cookie     : true,
             xfbml      : true});
};

(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

function doFacebookLogin(href) {
    FB.login(function(resp) {
        if (resp.authResponse) {
            FB.api('/me', function(response) {
                window.location = href;
            });
        }
    }, {scope: 'email'});
}