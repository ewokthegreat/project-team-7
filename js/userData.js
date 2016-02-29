/**
 * Created by ewokthegreat on 2/20/2016.
*/
function UserData(data) {
    'use strict';
    var self = this instanceof UserData ? this : Object.create(UserData.prototype);

    //Private Members
    var data = data || {};
    var firstName;
    var lastName;
    var oauthToken;

    //Public Methods
    self.getUserData = function() {
        return {
            firstName: firstName,
            lastName: lastName,
            oauthToken: oauthToken
        }
    };
    self.getOauthToken = function() {
        return oauthToken;
    };
    self.setOauthToken = function(data) {
        oauthToken = data;

        return oauthToken;
    };

    return self;
}
