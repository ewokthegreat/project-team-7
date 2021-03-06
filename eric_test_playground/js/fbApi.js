/**
 * Created by ewokthegreat on 3/21/2016.
 */
/**
 * Handles front-end interaction with the Facebook API.
 * Logs the proper user in to facebook and stores a cookie for use with the Facebook PHP-SDK on the back end.
 * @returns {boolean}
 */
window.fbAsyncInit = function() {
    FB.init({
        appId      : '1679655878969496', //Social Spider Test appID
        version    : 'v2.5',
        cookie     : true,
        xfbml      : true,
        status     : true
    });

    var loginButton = document.getElementById('fb-login');
    var logoutButton = document.getElementById('fb-logout');

    var statusChangeCallback = function(response) {
        if(response && response.status == 'connected') {
            console.log('You are logged in and cookie set!');

            var data = {
                userID: response.authResponse.userID,
                accessToken: response.authResponse.accessToken
            };
            // Now you can redirect the user or do an AJAX request to
            // a PHP script that grabs the signed request from the cookie.

            var xhr = new XMLHttpRequest();

            xhr.open('POST', '/php/init.php');
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onload = function() {
                console.log(xhr.responseText);
            };

            xhr.send(JSON.stringify(data));
        } else if (response.status === 'not_authorized') {
            alert('Not logged in to Social Spider app.')
        } else {
            alert('Not logged in to Facebook.');
            FB.login(statusChangeCallback, {scope: 'public_profile, email, user_posts'});
        }
    };


    loginButton.addEventListener('click', function() {
        console.log(this);
        FB.getLoginStatus(statusChangeCallback);
    });
    logoutButton.addEventListener('click', function() {
        console.log(this);
        FB.logout();
    });

    return false;
};
