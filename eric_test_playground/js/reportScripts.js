/**
 * Created by ewokthegreat on 4/25/2016.
 */
(function(window, document, undefined) {
    //Get the ace data from localStorage
    var reportData = window.localStorage.getItem('report');
    //Remove the ace data from localStorage
    window.localStorage.removeItem('report');

    var report = JSON.parse(reportData);

    var freqArr = report.intervalFlaggedPosts;
    
    var jsonFreq = [];
    for(var date in freqArr) {
        var obj = {};

        obj.date = date;
        obj.count = freqArr[date];

        jsonFreq.push(obj);
    }
    
    
    var bubbleChart = new BubbleChart(report.bubbleGraphData, '#bubble-chart');
    var lineChart = new LineChart(jsonFreq, '#freq-graph', '#freq-slider-range');
    
}(window, document, undefined));
