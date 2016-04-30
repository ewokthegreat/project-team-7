/**
 * Created by ewokthegreat on 4/17/2016.
 */
(function(w, d, u) {

    var data = {};

    _requestScript('php/applicantLoader.php', _populateApplicantTable, data);


    /**
     *
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
     *
     * @param response
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
     *
     * @param response
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
     * 
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
     *
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
     *
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
     * 
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