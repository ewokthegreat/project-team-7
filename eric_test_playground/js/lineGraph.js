/**
 * Created by ewokthegreat on 4/23/2016.
 */
/* implementation heavily influenced by http://bl.ocks.org/1166403 */
function LineChart(data, graphElement, sliderElement) {
    var selfObj = this instanceof LineChart ? this : Object.create(LineChart.prototype);

    console.log(data);

    // define dimensions of graph
    var margin = {top: 80, right: 80, bottom: 80, left: 80};
    var width = 1000 - margin.left - margin.right;
    var height = 400 - margin.top - margin.bottom;

    var parseDate = d3.time.format('%m %Y').parse;

    data.forEach(function (d) {
        d.date = parseDate(d.date);
        d.count = +d.count;
    });

    //define the x axis scale and set the domain
    var x = d3.time.scale().range([0, width]);
    x.domain(d3.extent(data, function (d) {
        return d.date;
    }));

    //define the y axis scale and set the domain
    var y = d3.scale.linear().range([height, 0]);
    y.domain([0, d3.max(data, function (d) {
        return d.count;
    })]);

    //create the xAxis and the yAxis
    // var xAxis = d3.svg.axis().scale(x).orient('bottom').tickSize(-height).tickSubdivide(3);
    var xAxis = d3.svg.axis().scale(x).orient('bottom').tickSize(-height).ticks(12);
    var yAxis = d3.svg.axis().scale(y).orient('left').ticks(5);

    //create the data line
    var line = d3.svg.line().interpolate('monotone')
        .x(function (d) {
            return x(d.date);
        })
        .y(function (d) {
            return y(d.count);
        });

    var svg = d3.select(graphElement)
        .append('svg')
        .attr('width', width + margin.left + margin.right)
        .attr('height', height + margin.top + margin.bottom)
        .append('svg:g')
        .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');

    svg.append('svg:g')
        .attr('class', 'x axis')
        .attr('transform', 'translate(0,' + height + ')')
        .call(xAxis);

    svg.append('svg:g')
        .attr('class', 'y axis')
        .call(yAxis);

    var clip = svg.append("defs").append("svg:clipPath")
        .attr("id", "clip")
        .append("svg:rect")
        .attr("id", "clip-rect")
        .attr("x", "0")
        .attr("y", "0")
        .attr("width", width)
        .attr("height", height);

    var path = svg.append('svg:path')
        .attr('class', 'path')
        .attr('clip-path', 'url(#clip)')
        .attr('d', line(data));

    function zoom(start, finish) {
        var begin = new Date(start);
        var end = new Date(finish);

        // console.log('Start: ' + start + '::: Finish: ' + finish);
        // console.log('Begin: ' + begin + '::: End: ' + end);

        x.domain([begin, end]);

        var t = svg.transition().duration(0);

        // var size = finish - start;
        // var step = size / 10;
        // var ticks = [];
        // for (var i = 0; i <= 10; i++) {
        //     ticks.push(new Date(Math.floor(start + step * i)));
        // }
        //
        // console.log(ticks);
        //
        // xAxis.tickValues(ticks);

        t.select(".x.axis").call(xAxis);
        t.select('.path').attr("d", line(data));
    }

    var max = d3.max(data, function (d) {
        return d.date;
    });
    var min = d3.min(data, function (d) {
        return d.date;
    });

    console.log(max);
    console.log(min);

    $(function () {
        $(sliderElement).slider({
            range: true,
            min: +min,
            max: +max,
            values: [+min, +max],
            slide: function (event, ui) {

                var begin = d3.min([ui.values[0], +max]);
                var end = d3.max([ui.values[1], 0]);

                zoom(begin, end);
            }
        });
    });


    return selfObj;
}
// /**
//  *
//  * @param scriptPath
//  * @param callback
//  * @param data
//  * @private
//  */
// var x = {};
//
// _requestScript('/php/lineGraph.php', _reportCallback, x);
//
// function _requestScript(scriptPath, callback, x) {
//     var xhr = new XMLHttpRequest();
//     xhr.open('post', scriptPath);
//     xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
//     xhr.onload = function () {
//         callback(JSON.parse(xhr.responseText));
//     };
//     xhr.send(JSON.stringify(x));
// }
//
// function _reportCallback(response) {
//     var rawAce = response;
//     var freqArr = rawAce.intervalFlaggedPosts;
//
//     // create a simple data array that we'll plot with a line (this array represents only the Y values, X will just be the index location)
//     var randomData = [0];
//     for (var i = 1; i < 1000; i++) {
//         var sign = Math.random() > 0.5 ? +1 : -1;
//         randomData.push(randomData[i-1] + sign * Math.random());
//     }
//
//     var jsonFreq = [];
//     for(var date in freqArr) {
//         var obj = {};
//
//         obj.date = date;
//         obj.count = freqArr[date];
//
//         jsonFreq.push(obj);
//     }
//
//     _testLineGraph(jsonFreq, '#freq-graph', '#freq-slider-range');
// }
//
// function _testLineGraph(data, graphElement, sliderElement) {
//     console.log(data);
//
//     // define dimensions of graph
//     var margin = {top: 80, right: 80, bottom: 80, left: 80};
//     var width = 1000 - margin.left - margin.right;
//     var height = 400 - margin.top - margin.bottom;
//
//     var parseDate = d3.time.format('%m %Y').parse;
//
//     data.forEach(function(d) {
//         d.date = parseDate(d.date);
//         d.count = +d.count;
//     });
//
//     //define the x axis scale and set the domain
//     var x = d3.time.scale().range([0, width]);
//     x.domain(d3.extent(data, function(d) {
//         return d.date;
//     }));
//
//     //define the y axis scale and set the domain
//     var y = d3.scale.linear().range([height, 0]);
//     y.domain([0, d3.max(data, function(d) {
//         return d.count;
//     })]);
//
//     //create the xAxis and the yAxis
//     // var xAxis = d3.svg.axis().scale(x).orient('bottom').tickSize(-height).tickSubdivide(3);
//     var xAxis = d3.svg.axis().scale(x).orient('bottom').tickSize(-height).ticks(12);
//     var yAxis = d3.svg.axis().scale(y).orient('left').ticks(5);
//
//     //create the data line
//     var line = d3.svg.line().interpolate('monotone')
//         .x(function(d) {
//             return x(d.date);
//         })
//         .y(function(d) {
//             return y(d.count);
//         });
//
//     var svg = d3.select(graphElement)
//         .append('svg')
//             .attr('width', width + margin.left + margin.right)
//             .attr('height', height + margin.top + margin.bottom)
//         .append('svg:g')
//         .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');
//
//     svg.append('svg:g')
//         .attr('class', 'x axis')
//         .attr('transform', 'translate(0,' + height + ')')
//         .call(xAxis);
//
//     svg.append('svg:g')
//         .attr('class', 'y axis')
//         .call(yAxis);
//
//     var clip = svg.append("defs").append("svg:clipPath")
//         .attr("id", "clip")
//         .append("svg:rect")
//         .attr("id", "clip-rect")
//         .attr("x", "0")
//         .attr("y", "0")
//         .attr("width", width)
//         .attr("height", height);
//
//     var path = svg.append('svg:path')
//         .attr('class', 'path')
//         .attr('clip-path', 'url(#clip)')
//         .attr('d', line(data));
//
//     function zoom(start, finish) {
//         var begin = new Date(start);
//         var end = new Date(finish);
//
//         // console.log('Start: ' + start + '::: Finish: ' + finish);
//         // console.log('Begin: ' + begin + '::: End: ' + end);
//
//         x.domain([begin, end]);
//
//         var t = svg.transition().duration(0);
//
//         // var size = finish - start;
//         // var step = size / 10;
//         // var ticks = [];
//         // for (var i = 0; i <= 10; i++) {
//         //     ticks.push(new Date(Math.floor(start + step * i)));
//         // }
//         //
//         // console.log(ticks);
//         //
//         // xAxis.tickValues(ticks);
//
//         t.select(".x.axis").call(xAxis);
//         t.select('.path').attr("d", line(data));
//     }
//
//     var max = d3.max(data, function(d) {
//         return d.date;
//     });
//     var min = d3.min(data, function(d) {
//         return d.date;
//     });
//
//     console.log(max);
//     console.log(min);
//
//     $(function() {
//         $(sliderElement).slider({
//             range: true,
//             min: +min,
//             max: +max,
//             values: [ +min, +max ],
//             slide: function( event, ui ) {
//
//                 var begin = d3.min([ui.values[0], +max]);
//                 var end = d3.max([ui.values[1], 0]);
//
//                 zoom(begin, end);
//             }
//         });
//     });
// }