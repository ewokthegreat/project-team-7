/**
 * Created by ewokthegreat on 4/17/2016.
 */
(function(w, d, u) {

    var data = {};

    _requestScript('php/applicantLoader.php', _populateApplicantTable, data);

    function _requestScript(scriptPath, callback, data) {
        console.log(data);
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
    }

    function _handleReportLinkClicks() {
        var reportLinkArray = document.getElementsByClassName('report-link');

        for(var i = 0; i < reportLinkArray.length; i++) {
            var currentLink = reportLinkArray[i];

            currentLink.addEventListener('click', function() {
                console.log('CLICK');
                console.log(this.dataset.id);
                var data = { id: this.dataset.id };
                console.log(data);
                _requestScript('php/scanLoader.php', _populateScanTable, data);
            });
        }
    }
})(window, document, undefined);