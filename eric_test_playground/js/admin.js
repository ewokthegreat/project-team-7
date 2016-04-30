/**
 * Created by ewokthegreat on 4/17/2016.
 */
/**
 * Function that handles DOM manipulation for Profile.html (aka the admin interface).
 */
(function(w, d, u) {

    var data = {};

    _requestScript('php/applicantLoader.php', _populateApplicantTable, data);


    /**
     *  Wrapper for native XMLHttpRequest Objects
     * @param scriptPath
     * @param callback
     * @param data
     * @private
     */
    function _requestScript(scriptPath, callback, data, debug) {
        var debug = false || debug;
        var xhr = new XMLHttpRequest();
        xhr.open('post', scriptPath);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onload = function() {
            if(debug) {
                callback(xhr.responseText);
            } else {
                callback(JSON.parse(xhr.responseText));
            }
        };
        xhr.send(JSON.stringify(data));
    }

    /**
     *  Grabs the data for each applicant, passes it to the templating engine for
     *  and DOM insertion and attaches click event listeners to the new elements. 
     * @param response - the content of the XMLHttpRequest
     * @private
     */
    function _populateApplicantTable(response) {
        var applicantObject = {
            applicants: response
        };

        if (_storageAvailable('localStorage')) {
            console.log('Good to go!');
            localStorage.setItem('applicants', JSON.stringify(response));
        } else {
            console.log('LocalStorage is not available in your browser. Try a different browser.');
        }

        var template = document.getElementById('all-applicant-template').innerHTML;
        var target = document.getElementById('all-applicant-list');

        Mustache.parse(template);
        var rendered = Mustache.render(template, applicantObject);
        target.innerHTML = rendered;

        _handleReportLinkClicks();
    }

    /**
     *  Grabs the scan data from the database, feeds it to the templating engine for
     *  DOM insertion and attaches click event handlers to the new elements.
     * @param response - the content of the XMLHttpRequest
     * @private
     */
    function _populateScanTable(response) {
        var id = response.userID;
        var elementId = id + '-scans';
        var scanObject = {
            scans: response.scanData
        };

        var template = document.getElementById('applicant-scan-template').innerHTML;
        var target = document.getElementById(elementId);
        // target.classList.toggle('hide');

        Mustache.parse(template);

        var rendered = Mustache.render(template, scanObject);
        target.innerHTML = rendered;

        _handleViewLinkClicks();
    }

    /**
     * Places the report data into LocalStorage via the LocalStorage API
     * @param response 
     * @private
     */
    function _loadReport(response) {
        // console.log(response);
        if (_storageAvailable('localStorage')) {
            console.log('Good to go!');
            localStorage.setItem('report', JSON.stringify(response));
            var url = 'report_beta.html?nocache=1';
            var reportWindow = window.open(url, '_blank');
            reportWindow.focus;
        } else {
            console.log('LocalStorage is not available in your browser. Try a different browser.');
        }
    }

    /**
     *  Adds click event listeners to links in the report table.
     * @private
     */
    function _handleReportLinkClicks() {
        var reportLinkArray = document.getElementsByClassName('report-link');

        for(var i = 0; i < reportLinkArray.length; i++) {
            var currentLink = reportLinkArray[i];

            currentLink.addEventListener('click', function() {
                console.log('CLICK');
                console.log(this.dataset.id);
                var data = { id: this.dataset.id };

                _requestScript('php/scanLoader.php', _populateScanTable, data);
            });
        }
    }

    /**
     *  Adds click event listeners to each report link
     * @private
     */
    function _handleViewLinkClicks() {
        var reportLinkArray = document.getElementsByClassName('view-report');

        for(var i = 0; i < reportLinkArray.length; i++) {
            var currentLink = reportLinkArray[i];

            currentLink.addEventListener('click', function() {
                console.log('CLICK');
                console.log(this.dataset.path);
                var data = { path: this.dataset.path };

                _requestScript('php/reportLoader.php', _loadReport, data);
            });
        }
    }

    /**
     * Verifies that LocalStorage API is available and accessible to our scripts.
     * @param type
     * @returns {boolean}
     * @private
     */
    function _storageAvailable(type) {
        try {
            var storage = window[type],
                x = '__storage_test__';
            storage.setItem(x, x);
            storage.removeItem(x);
            return true;
        }
        catch(e) {
            return false;
        }
    }
})(window, document, undefined);