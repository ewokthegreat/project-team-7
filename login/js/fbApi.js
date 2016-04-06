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

        console.log(response);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/spider/login/php/test.php');
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
        xhr.onload = function() {
            console.log(xhr.responseText);
        };
        xhr.send(JSON.stringify({
            authResponse: response.authResponse
        }));

    }, true);
};
