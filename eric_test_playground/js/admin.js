/**
 * Created by ewokthegreat on 4/17/2016.
 */
(function(w, d, u) {

    var data = {};

    _requestScript('php/applicantLoader.php', _populateApplicantTable, data);

    function _requestScript(scriptPath, callback, data) {
        var xhr = new XMLHttpRequest();
        xhr.open('post', scriptPath);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onload = function() {
            callback(JSON.parse(xhr.responseText));
        };
        xhr.send(JSON.stringify(data));
    }

    function _populateApplicantTable(response) {
        var applicantObject = {
            applicants: response
        };
        var template = document.getElementById('all-applicant-template').innerHTML;
        var target = document.getElementById('all-applicant-list');

        Mustache.parse(template);
        var rendered = Mustache.render(template, applicantObject);
        target.innerHTML = rendered;

        _handleReportLinkClicks();
    }

    function _populateScanTable(response) {
        var scanObject = {
            scans: response
        };
        var template = document.getElementById('applicant-scan-template').innerHTML;
        var target = document.getElementById('applicant-scan-data');

        Mustache.parse(template);
        var rendered = Mustache.render(template, )
        console.log(scanObject.scans);
    }

    function _handleReportLinkClicks() {
        console.log('handleReportLink');
        var reportLinkArray = document.getElementsByClassName('report-link');
        for(var i = 0; i < reportLinkArray.length; i++) {
            var currentLink = reportLinkArray[i];
            currentLink.addEventListener('click', function() {
                var data = { id: this.dataset.id };
                _requestScript('php/scanLoader.php', _populateScanTable, data);
            })
        }
    }
})(window, document, undefined);