/**
 * Created by ewokthegreat on 3/21/2016.
 */

//iife to test some things out
(function(window, document) {
    'use strict';

    console.log("Creating request object...");

    var xhr = new XMLHttpRequest();
    console.log(xhr);
    xhr.open('POST', '/spider/login/php/test.php');
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function() {
        console.log(xhr.responseText);
    };
    xhr.send(JSON.stringify({
        message: 'TESTING',
        type: 'SEND'
    }));

})(window, document);

window.fbAsyncInit = function() {
    FB.init({
        appId      : '1679655878969496', //Social Spider Test appID
        version    : 'v2.5'
    });

    FB.getLoginStatus(function(response) {
        console.log(response);
    }, true);

    FB.getLoginStatus(_eatMe, true);

    function _eatMe(response) {
        console.log(response);
    }

    //This is the best way to show how an anonymous function works.
    function _testes(cb, param1) {
        cb(param1);
    }

    function _testesCb(e) {
        console.log('Phrase:');
        console.log(e);
        console.log('Now split it:');

        var array = e.split(' ');

        for(var i = 0; i < array.length; i++) {
            console.log(array[i]);
        }

    }

    _testes(function(e) {
        console.log('Phrase:');
        console.log(e);
        console.log('Now split it:');
        var array = e.split(' ');

        for(var i = 0; i < array.length; i++) {
            console.log(array[i]);
        }
    }, 'My pickle is a beautiful girl.');

    _testes(_testesCb, 'My sharona!');
};
