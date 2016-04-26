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
    console.log(report);

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
    var profileLinks = document.getElementById('profile-links');
    var fbLink = document.querySelector('#profile-links .fb-link');
    var freqMetric = document.getElementById('frequency-metric');
    var qualityMetric = document.getElementById('quality-metric');
    var scanDate = document.querySelector('.dl-horizontal .scan-date');
    var firstFlag = document.querySelector('.dl-horizontal .first-flag');
    var lastFlag = document.querySelector('.dl-horizontal .last-flag');
    var favoriteTeam = document.querySelector('.dl-horizontal .fav-team');

    fbLink.href = applicant.profileLink;
    picture.style.backgroundImage = 'url(' + applicant.profilePicture + ')';
    fname.innerHTML = applicant.firstName;
    lname.innerHTML = applicant.lastName;
    email.innerHTML = applicant.email;
    freqMetric.innerHTML = report.flaggedPostsPerYear.toFixed(2);
    qualityMetric.innerHTML = report.averageWeightOfFlaggedPost.toFixed(2);

    var dateArray = report.dateGenerated.split('.');
    var date = new Date(dateArray[1] + ' ' + dateArray[2] + ' ' + 20 + dateArray[0]);
    scanDate.innerHTML = date;

    dateArray = report.firstFlaggedPostDate.split('-');
    date = new Date(dateArray[2] + ' ' + dateArray[1] + ' ' + dateArray[0]);
    firstFlag.innerHTML = date;

    dateArray = report.lastFlaggedPostDate.split('-');
    date = new Date(dateArray[2] + ' ' + dateArray[1] + ' ' + dateArray[0]);
    lastFlag.innerHTML = date;

    favoriteTeam.innerHTML = report.favoriteTeam.charAt(0).toUpperCase() + report.favoriteTeam.slice(1);

    var flaggedPostArr = report.sortedByWeightFlaggedPostsArray;

    var flaggedPostObj = {
        flaggedPostArr: flaggedPostArr
    };

    console.log(flaggedPostObj);
    
    _loadTemplate('post-detail', 'post-detail-template', flaggedPostObj );

    function _getCurrentApplicantData(id, data) {
        for(var i = 0; i < data.length; i++) {
            var currentData = data[i];

            if(currentData.applicantID == id) {
                return currentData;
            }
        }
    }

    function _loadTemplate(target, template, data) {
        var template = document.getElementById(template).innerHTML;
        var target = document.getElementById(target);
        // target.classList.toggle('hide');

        Mustache.parse(template);

        var rendered = Mustache.render(template, data);
        target.innerHTML = rendered;
    }



}(window, document, undefined));