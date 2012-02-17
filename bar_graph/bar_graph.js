var labelType, useGradients, nativeTextSupport, animate,
	ajax_url = "http://davidmregister.com/ci/index.php/google/update";

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
     this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};


function init(json){
  //init data
	
    //init BarChart
    var barChart = new $jit.BarChart({
      //id of the visualization container
      injectInto: 'infovis',
      //whether to add animations
      animate: true,
      //horizontal or vertical barcharts
      orientation: 'vertical',
      //bars separation
      barsOffset: 20,
      //visualization offset
      Margin: {
        top:5,
        left: 5,
        right: 5,
        bottom:5
      },
      //labels offset position
      labelOffset: 5,
      //bars style
      type: useGradients? 'stacked:gradient' : 'stacked',
      //whether to show the aggregation of the values
      showAggregates:true,
      //whether to show the labels for the bars
      showLabels:true,
      //labels style
      Label: {
        type: labelType, //Native or HTML
        size: 13,
        family: 'Arial',
        color: 'white'
      },
      //add tooltips
      Tips: {
        enable: true,
        onShow: function(tip, elem) {
          tip.innerHTML = "<b>" + elem.name + "</b>: " + elem.value;
        }
      }
    });
    //load JSON data.
    barChart.loadJSON(json);
    //end
    var list = $jit.id('id-list'),
        button = $jit.id('update'),
        orn = $jit.id('switch-orientation');
    //update json on click 'Update Data'
    $jit.util.addEvent(button, 'click', function() {
    //show loader
      document.getElementById('loader').style.display = 'block';
      var util = $jit.util;
      //remove white class and add gray class
      util.removeClass(button, 'white');
      util.addClass(button, 'gray');
			        var xmlHttpReq = false;
				    var self = this;
				    // Mozilla/Safari
				    if (window.XMLHttpRequest) {
				        self.xmlHttpReq = new XMLHttpRequest();
				    }
				    // IE
				    else if (window.ActiveXObject) {
				        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
				    }
				    self.xmlHttpReq.open('GET', ajax_url, true);
				    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				    self.xmlHttpReq.onreadystatechange = function() {
				    	if (self.xmlHttpReq.readyState == 4) {
				    		//format JSON response
				    		var update = eval('(' + self.xmlHttpReq.response + ')');
				    		//update bar chart with formatted response
				        	barChart.updateJSON(update);
				        	//remove gray class from button
				        	util.removeClass(button, 'gray');
				        	//add white class
      						util.addClass(button, 'white');
      						//hide loader
      						document.getElementById('loader').style.display = 'none';
      						//update time
      						document.getElementById('updated_time').innerHTML = update.updated;
				        }
				    }
				    self.xmlHttpReq.send();
    });
    //dynamically add legend to list
    var legend = barChart.getLegend(),
        listItems = [];
    for(var name in legend) {
      listItems.push('<div class=\'query-color\' style=\'background-color:'
          + legend[name] +'\'>&nbsp;</div>' + name);
    }
    list.innerHTML = '<li>' + listItems.join('</li><li>') + '</li>';
}