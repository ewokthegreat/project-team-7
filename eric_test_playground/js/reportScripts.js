/**
 * Created by ewokthegreat on 4/25/2016.
 */
(function(window, document, undefined) {
    //Get the ace data from localStorage
    var reportData = window.localStorage.getItem('report');
    var applicantData = window.localStorage.getItem('applicants');

    //Remove the ace data from localStorage
    // window.localStorage.removeItem('report');

    var report = JSON.parse(reportData);
    var applicantArr = JSON.parse(applicantData);
    var applicant = _getCurrentApplicantData(report.userID, applicantArr);
    var freqArr = report.intervalFlaggedPosts;

    console.log(applicant);

    var jsonFreq = [];
    for(var date in freqArr) {
        var obj = {};

        obj.date = date;
        obj.count = freqArr[date];

        jsonFreq.push(obj);
    }

    var bubbleChart = new BubbleChart(report.bubbleGraphData, '#bubble-chart');
    var lineChart = new LineChart(jsonFreq, '#freq-graph', '#freq-slider-range');

    var picture = document.getElementById('profile-picture');
    var fname = document.getElementById('first-name');
    var lname = document.getElementById('last-name');
    var email = document.getElementById('profile-email');
    var date = document.getElementById('profile-scan-date');
    var profileLink = document.getElementById('profile-link');

    picture.style.backgroundImage = 'url(' + applicant.profilePicture + ')';
    fname.innerHTML = applicant.firstName;
    lname.innerHTML = applicant.lastName;

    function _getCurrentApplicantData(id, data) {
        for(var i = 0; i < data.length; i++) {
            var currentData = data[i];

            if(currentData.applicantID == id) {
                return currentData;
            }
        }
    }

}(window, document, undefined));
