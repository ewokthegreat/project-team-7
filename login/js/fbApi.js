/**
 * Created by ewokthegreat on 3/21/2016.
 */
window.fbAsyncInit = function() {
    FB.init({
        appId      : '1679655878969496', //Social Spider Test appID
        version    : 'v2.5',
        cookie     : true,
        xfbml      : true
    });

    FB.login(function(response) {
        if (response.authResponse) {
            alert('You are logged in and cookie set!');
            // Now you can redirect the user or do an AJAX request to
            // a PHP script that grabs the signed request from the cookie.

            $.post("/spider/login/php/test.php", { json_string:JSON.stringify({name:"John", time:"2pm"}) });

        } else {
            alert('User cancelled login or did not fully authorize.');
        }
    });

    return false;
};
