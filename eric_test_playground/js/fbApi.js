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

    FB.getLoginStatus(function(response) {
        /**
         * Logged into FB and our app.
         */
        if(response && response.status == 'connected') {

        }
    });

    FB.login(function(response) {
        console.log(response);
        if (response.authResponse) {
            console.log('You are logged in and cookie set!');
            // Now you can redirect the user or do an AJAX request to
            // a PHP script that grabs the signed request from the cookie.

            var xhr = new XMLHttpRequest();
            // xhr.open('POST', '/spider/php/ProcessFBPosts.php');
            // xhr.open('POST', '/spider/eric_test_playground/php/test.php');
            xhr.open('POST', '/php/ProcessFBPosts.php');
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onload = function() {
                console.log(xhr.responseText);
                // var response = JSON.parse(xhr.responseText);
                // console.log(response);
            };
            xhr.send(JSON.stringify({name:'NAME', time: 'TIME', dickSize: 'TINY'}));
        } else {
            console.log('User cancelled login or did not fully authorize.');
        }
    });

    return false;
};
