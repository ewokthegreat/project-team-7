/** Created by ewokthegreat on 4/6/2016. */
/**
 * Handles the creation of the bubble chart. REQUIRES D3.js
 * @param bubbleGraphData - the data that the bubble chart will use
 * @param bubbleGraphElement - the element that contains the bubble chart.
 * @returns {BubbleChart}
 * @constructor
 */
function BubbleChart(bubbleGraphData, bubbleGraphElement) {
    var selfObj = this instanceof BubbleChart ? this : Object.create(BubbleChart.prototype);
    
    var data = {
        name: 'flare',
        children: []
    };

    for (var dictionary in bubbleGraphData) {
        var currentDictionary = bubbleGraphData[dictionary];
        var flareObject = {};

        flareObject.name = dictionary;
        flareObject.children = [];

        data.children.push(flareObject);

        for (var word in currentDictionary) {
            var currentWordCount = currentDictionary[word];
            var children = {};

            children.name = word.replace(/\w\S*/g, function (txt){
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
            children.size = currentWordCount;

            flareObject.children.push(children);
        }
    }

    var diameter = 750,
        format = d3.format(",d"),
        color = d3.scale.category10();

    var bubble = d3.layout.pack()
        .sort(null)
        .size([diameter, diameter])
        .padding(1.5);

    var svg = d3.select(bubbleGraphElement).append("svg")
        .attr("width", diameter)
        .attr("height", diameter)
        .attr("class", "bubble");


    var node = svg.selectAll(".node")
        .data(bubble.nodes(classes(data))
            .filter(function(d) { return !d.children; }))
        .enter().append("g")
        .attr("class", "node")
        .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

    node.append("title")
        .text(function(d) { return d.className + ": " + format(d.value); });

    node.append("circle")
        .attr("r", function(d) { return d.r; })
        .style("fill", function(d) { return color(d.packageName); });

    node.append("text")
        .attr("dy", ".3em")
        .style("text-anchor", "middle")
        .text(function(d) { return d.className.substring(0, d.r / 3); });

    // Returns a flattened hierarchy containing all leaf nodes under the root.
    function classes(root) {
        var classes = [];

        function recurse(name, node) {

            if (node.children) {
                node.children.forEach(function (child) {
                    recurse(node.name, child);
                });
            } else {
                classes.push({packageName: name, className: node.name, value: node.size});
            }
        }

        recurse(null, root);

        return {children: classes};
    }

    d3.select(self.frameElement).style("height", diameter + "px");
    
    return selfObj;
}