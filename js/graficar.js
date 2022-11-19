function graficar_chart(id,tipo,data,callback) {
	switch(tipo) {
		case 1: //barras
		  barras(id,data,callback);
		break;
		
		case 2: // tortas
		  tortas(id,data,callback)
		break;

		case 3: // tortas_donut
		  tortas_donut(id,data,callback)
		break;

		case 4: // Baras categoria
		  barras_categoria(id,data,callback)
		break;

		case 5: // Baras categoria
		  barras_verticales(id,data,callback)
		break;

		case 6: // Baras categoria
		  barras_categoria_vertical(id,data,callback)
		break;

		case 7: // Baras categoria
		  barras_categoria_inicio(id,data,callback)
		break;

    case 8: // Baras categoria
      barras_categoria_vertical_v2(id,data,callback)
    break;
     case 9: // Baras categoria
      barras_categoria_vertical_v3(id,data,callback)
    break;

    case 10: // Baras categoria
      tortas_v2(id,data,callback)
    break;
		
		default:
		  tortas(id,data,callback)
	}
}

function barras_verticales(id,data,callback) {

 var data_json  = data.data;
	var value  = data.value;
	var category  = data.category;
 
  if (data_json.length<=0) {
  	var img = '<img src="img/no_data.jpg" style="width:35%">';
  	document.getElementById(id).style.textAlign = "center";
  	$("#"+id).html(img);
  	return false;
  }

am4core.useTheme(am4themes_animated);

// Create chart instance
var chart = am4core.create(id, am4charts.XYChart);
chart.padding(40, 40, 40, 40);

// Add data
chart.data = data_json

var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = category;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 1;
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.disabled = true;


var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());

var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueX = value;
series.dataFields.categoryY = category;
series.tooltipText = "{valueX.value}"
series.columns.template.strokeOpacity = 0;
series.columns.template.column.cornerRadiusBottomRight = 5;
series.columns.template.column.cornerRadiusTopRight = 5;


var labelBullet = series.bullets.push(new am4charts.LabelBullet())
labelBullet.label.horizontalCenter = "left";
labelBullet.label.dx = 10;
labelBullet.label.text = "{values.valueX.workingValue.formatNumber('#.0as')}";
labelBullet.locationX = 1;

// as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
series.columns.template.adapter.add("fill", function(fill, target){
  return chart.colors.getIndex(target.dataItem.index);
});
if (callback!=false) {series.columns.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;}

series.columns.template.events.on("hit", function (ev) { 
   var data = ev.target.dataItem.dataContext;
   callback(data);
});


}


function barras(id,data,callback) {
   

    var data_json  = data.data;
	var value  = data.value;
	var category  = data.category;
 
  if (data_json.length<=0) {
  	var img = '<img src="img/no_data.jpg" style="width:35%">';
  	document.getElementById(id).style.textAlign = "center";
  	$("#"+id).html(img);
  	return false;
  }

  am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		
		var chart = am4core.create(id, am4charts.XYChart);
		
		chart.data = data_json;
		
		chart.padding(40, 40, 40, 40);
		
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.renderer.grid.template.location = 10;
		categoryAxis.dataFields.category =category;
		categoryAxis.renderer.minGridDistance = 60;
		categoryAxis.renderer.inversed = true;
		categoryAxis.renderer.grid.template.disabled = true;
		
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.extraMax = 0.1;
		//valueAxis.rangeChangeEasing = am4core.ease.linear;
		//valueAxis.rangeChangeDuration = 1500;
		
		var series = chart.series.push(new am4charts.ColumnSeries());
		series.dataFields.categoryX =category;
		series.dataFields.valueY = value;
		series.tooltipText = "{valueY.value}"
		series.columns.template.strokeOpacity = 0;
		series.columns.template.column.cornerRadiusTopRight = 10;
		series.columns.template.column.cornerRadiusTopLeft = 10;
		series.columns.template.propertyFields.fill = "color";
        if (callback!=false) {series.columns.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;}
		

		//series.interpolationDuration = 1500;
		//series.interpolationEasing = am4core.ease.linear;
		var labelBullet = series.bullets.push(new am4charts.LabelBullet());
		labelBullet.label.verticalCenter = "bottom";
		labelBullet.label.dy = -10;
		labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#.')}";
		
		chart.zoomOutButton.disabled = true;
		
		series.columns.template.events.on("hit", function (ev) { 
			var data = ev.target.dataItem.dataContext;
			callback(data);
		});

    }); // end am4core.ready()

}




// PIE //
function tortas(id,data,callback) {
	
	var data_json  = data.data;
	var value  = data.value;
	var category  = data.category;

  if (data_json.length<=0) {
  	var img = '<img src="img/no_data.jpg" style="width:35%">';
  	document.getElementById(id).style.textAlign = "center";
  	$("#"+id).html(img);
  	return false;
  }

	am4core.ready(function() {
		
		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		
		// Create chart instance
		var chart = am4core.create(id, am4charts.PieChart);		
		// Add data
		chart.data = data_json;		
		// Add and configure Series
		var pieSeries = chart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value = value;
		pieSeries.dataFields.category = category;
		pieSeries.slices.template.propertyFields.fill = "color";
		
        if (callback!=false) {pieSeries.slices.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;}
		
		chart.legend = new am4charts.Legend();
		
		pieSeries.slices.template.events.on("hit", function (ev) { 
			var data = ev.target.dataItem.dataContext;
			callback(data);
		});


    }); // end am4core.ready()


}

function tortas_donut(id,data,callback) {
	
	var data_json  = data.data;
	var value  = data.value;
	var category  = data.category;
   
   if (data_json.length<=0) {
  	var img = '<img src="img/no_data.jpg" style="width:35%">';
  	document.getElementById(id).style.textAlign = "center";
  	$("#"+id).html(img);
  	return false;
  }

  
	am4core.ready(function() {
		
		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		
		// Create chart instance
		var chart = am4core.create(id, am4charts.PieChart);		
		// Add data
		chart.data = data_json;	
		// Set inner radius
		chart.innerRadius = am4core.percent(50);

		// Add and configure Series
		var pieSeries = chart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value = value;
		pieSeries.dataFields.category = category;
		//pieSeries.ticks.template.disabled = true;
		//pieSeries.labels.template.disabled = true;
		//pieSeries.slices.template.propertyFields.fill = "color";
		pieSeries.slices.template.stroke = am4core.color("#fff");
		pieSeries.slices.template.strokeWidth = 1;
		pieSeries.slices.template.strokeOpacity = 1;

    // This creates initial animation
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;


		if (callback!=false) {pieSeries.slices.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;}
		
		chart.legend = new am4charts.Legend();

		
		pieSeries.slices.template.events.on("hit", function (ev) { 
			var data = ev.target.dataItem.dataContext;
			callback(data);
		});


    }); // end am4core.ready()


}


function barras_categoria(id,data,callback) {


    var data_json  = data.data;
    var data_series  = data.series;
	var value  = data.value;
	var category  = data.category;
 
  if (data_json.length<=0) {
  	var img = '<img src="img/no_data.jpg" style="width:35%">';
  	document.getElementById(id).style.textAlign = "center";
  	$("#"+id).html(img);
  	return false;
  }


am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end



var chart = am4core.create(id, am4charts.XYChart)
chart.colors.step = 2;

chart.legend = new am4charts.Legend()
chart.legend.position = 'top'
chart.legend.paddingBottom = 20
chart.legend.labels.template.maxWidth = 95

var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
xAxis.dataFields.category = 'category'
xAxis.renderer.cellStartLocation = 0.1
xAxis.renderer.cellEndLocation = 0.9
xAxis.renderer.grid.template.location = 0;

var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
yAxis.min = 0;

function createSeries(value, name) {
    var series = chart.series.push(new am4charts.ColumnSeries())
    series.dataFields.valueY = value
    series.dataFields.categoryX = 'category'
    series.name = name

    series.events.on("hidden", arrangeColumns);
    series.events.on("shown", arrangeColumns);

    var bullet = series.bullets.push(new am4charts.LabelBullet())
    bullet.interactionsEnabled = false
    bullet.dy = 30;
    bullet.label.text = '{valueY}'
    bullet.label.fill = am4core.color('#ffffff')

    return series;
}

chart.data = data_json

for (const rw in data_series) {
     createSeries(rw,data_series[rw])
}


function arrangeColumns() {

    var series = chart.series.getIndex(0);

    var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
    if (series.dataItems.length > 1) {
        var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
        var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
        var delta = ((x1 - x0) / chart.series.length) * w;
        if (am4core.isNumber(delta)) {
            var middle = chart.series.length / 2;

            var newIndex = 0;
            chart.series.each(function(series) {
                if (!series.isHidden && !series.isHiding) {
                    series.dummyData = newIndex;
                    newIndex++;
                }
                else {
                    series.dummyData = chart.series.indexOf(series);
                }
            })
            var visibleCount = newIndex;
            var newMiddle = visibleCount / 2;

            chart.series.each(function(series) {
                var trueIndex = chart.series.indexOf(series);
                var newIndex = series.dummyData;

                var dx = (newIndex - trueIndex + middle - newMiddle) * delta

                series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
            })
        }
    }
}

      /*  series.columns.template.events.on("hit", function (ev) { 
			var data = ev.target.dataItem.dataContext;
			callback(data);
		});*/

}); // end am4core.ready()


}


function barras_categoria_vertical(id,data,callback) {


    var data_json  = data.data;
    var data_series  = data.series;
	var value  = data.value;
	var category  = data.category;
 
  if (data_json.length<=0) {
  	var img = '<img src="img/no_data.jpg" style="width:35%">';
  	document.getElementById(id).style.textAlign = "center";
  	$("#"+id).html(img);
  	return false;
  }


am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

 // Create chart instance
var chart = am4core.create(id, am4charts.XYChart);
chart.colors.step = 2;

chart.legend = new am4charts.Legend()
chart.legend.position = 'top'
chart.legend.paddingBottom = 20
chart.legend.labels.template.maxWidth = 100

// Add data
chart.data = data_json

// Create axes
var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.numberFormatter.numberFormat = "#";
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;

var  valueAxis = chart.xAxes.push(new am4charts.ValueAxis()); 
valueAxis.renderer.opposite = true;

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueX = field;
  series.dataFields.categoryY = "category";
  series.name = name;
  series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
  series.columns.template.height = am4core.percent(100);
  series.sequencedInterpolation = true;

  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
  valueLabel.label.text = "{valueX}";
  valueLabel.label.horizontalCenter = "left";
  valueLabel.label.dx = 10;
  valueLabel.label.hideOversized = false;
  valueLabel.label.truncate = false;

}



for (const rw in data_series) {
     createSeries(rw,data_series[rw])
}


}); // end am4core.ready()





}





function barras_categoria_inicio(id,data,callback) {


    var data_json  = data.data;
    var data_series  = data.series;
	var value  = data.value;
	var category  = data.category;
 
  if (data_json.length<=0) {
  	var img = '<img src="img/no_data.jpg" style="width:35%">';
  	document.getElementById(id).style.textAlign = "center";
  	$("#"+id).html(img);
  	return false;
  }


am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end



var chart = am4core.create(id, am4charts.XYChart)
chart.colors.step = 2;

chart.legend = new am4charts.Legend()
chart.legend.position = 'right'
chart.legend.paddingBottom = 10
chart.legend.labels.template.maxWidth = 195
chart.legend.scrollable = true;

var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
xAxis.dataFields.category = 'category'
xAxis.renderer.cellStartLocation = 0.1
xAxis.renderer.cellEndLocation = 0.9
xAxis.renderer.grid.template.location = 0;

var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
yAxis.min = 0;

function createSeries(value, name) {
    var series = chart.series.push(new am4charts.ColumnSeries())
    series.dataFields.valueY = value
    series.dataFields.categoryX = 'category'
    series.name = name
    series.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
    series.columns.template.height = am4core.percent(100);
    series.sequencedInterpolation = true;

    series.events.on("hidden", arrangeColumns);
    series.events.on("shown", arrangeColumns);

    var bullet = series.bullets.push(new am4charts.LabelBullet())
    bullet.interactionsEnabled = false
    bullet.label.dy = -5;
    bullet.label.text = '{valueY}'
    bullet.label.fill = am4core.color('#000')

    return series;
}

chart.data = data_json

for (const rw in data_series) {
     createSeries(rw,data_series[rw])
}


function arrangeColumns() {

    var series = chart.series.getIndex(0);

    var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
    if (series.dataItems.length > 1) {
        var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
        var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
        var delta = ((x1 - x0) / chart.series.length) * w;
        if (am4core.isNumber(delta)) {
            var middle = chart.series.length / 2;

            var newIndex = 0;
            chart.series.each(function(series) {
                if (!series.isHidden && !series.isHiding) {
                    series.dummyData = newIndex;
                    newIndex++;
                }
                else {
                    series.dummyData = chart.series.indexOf(series);
                }
            })
            var visibleCount = newIndex;
            var newMiddle = visibleCount / 2;

            chart.series.each(function(series) {
                var trueIndex = chart.series.indexOf(series);
                var newIndex = series.dummyData;

                var dx = (newIndex - trueIndex + middle - newMiddle) * delta

                series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
            })
        }
    }
}

      /*  series.columns.template.events.on("hit", function (ev) { 
			var data = ev.target.dataItem.dataContext;
			callback(data);
		});*/

}); // end am4core.ready()


}





function barras_categoria_vertical_v2(id,data,callback) {


    var data_json  = data.data;
    var data_series  = data.series;
  var value  = data.value;
  var category  = data.category;
 
  if (data_json.length<=0) {
    var img = '<img src="img/no_data.jpg" style="width:35%">';
    document.getElementById(id).style.textAlign = "center";
    $("#"+id).html(img);
    return false;
  }


am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

 // Create chart instance
var chart = am4core.create(id, am4charts.XYChart);
chart.colors.step = 2;

// Add data
chart.data = data_json

// Create axes
var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.numberFormatter.numberFormat = "#";
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;

var  valueAxis = chart.xAxes.push(new am4charts.ValueAxis()); 
valueAxis.renderer.opposite = true;

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueX = field;
  series.dataFields.categoryY = "category";
  series.name = name;
  series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
  series.columns.template.height = am4core.percent(100);
  series.sequencedInterpolation = true;

  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
  valueLabel.label.text = "{valueX}";
  valueLabel.label.horizontalCenter = "left";
  valueLabel.label.dx = 10;
  valueLabel.label.hideOversized = false;
  valueLabel.label.truncate = false;

  var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
  categoryLabel.label.text = "{name}";
  categoryLabel.label.horizontalCenter = "right";
  categoryLabel.label.dx = -10;
  categoryLabel.label.fill = am4core.color("#000");
  categoryLabel.label.hideOversized = false;
  categoryLabel.label.truncate = false;

}



for (const rw in data_series) {
     createSeries(rw,data_series[rw])
}


}); // end am4core.ready()





}


function barras_categoria_vertical_v3(id,data,callback) {


    var data_json  = data.data;
    var data_series  = data.series;
  var value  = data.value;
  var category  = data.category;
 
  if (data_json.length<=0) {
    var img = '<img src="img/no_data.jpg" style="width:35%">';
    document.getElementById(id).style.textAlign = "center";
    $("#"+id).html(img);
    return false;
  }


am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

 // Create chart instance
var chart = am4core.create(id, am4charts.XYChart);
chart.colors.step = 2;

chart.legend = new am4charts.Legend()
chart.legend.position = 'right'
chart.legend.paddingBottom = 20
chart.legend.labels.template.maxWidth = 100
chart.legend.maxWidth = 400;
chart.legend.scrollable = true;

// Add data
chart.data = data_json

// Create axes
var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.numberFormatter.numberFormat = "#";
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;

var  valueAxis = chart.xAxes.push(new am4charts.ValueAxis()); 
valueAxis.renderer.opposite = true;

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueX = field;
  series.dataFields.categoryY = "category";
  series.name = name;
  series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
  series.columns.template.height = am4core.percent(100);
  series.sequencedInterpolation = true;

  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
  valueLabel.label.text = "{valueX}";
  valueLabel.label.horizontalCenter = "left";
  valueLabel.label.dx = 10;
  valueLabel.label.hideOversized = false;
  valueLabel.label.truncate = false;
  

   if (callback!=false) {series.columns.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;}
   
   series.columns.template.events.on("hit", function (ev) { 
      var data = ev.target.dataItem.dataContext;
      callback(data);
    });
   


}



for (const rw in data_series) {
     createSeries(rw,data_series[rw])
}

    
   // var series = chart.series.push(new am4charts.ColumnSeries());
   
  


}); // end am4core.ready()





}



// PIE //
function tortas_v2(id,data,callback) {
  
  var data_json  = data.data;
  var value  = data.value;
  var category  = data.category;

  if (data_json.length<=0) {
    var img = '<img src="img/no_data.jpg" style="width:35%">';
    document.getElementById(id).style.textAlign = "center";
    $("#"+id).html(img);
    return false;
  }

  am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end
    
    // Create chart instance
    var chart = am4core.create(id, am4charts.PieChart);  
    chart.innerRadius = am4core.percent(50); 
    // Add data
    chart.data = data_json;   
    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = value;
    pieSeries.dataFields.category = category;
    pieSeries.slices.template.propertyFields.fill = "color";
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;
    
    // This creates initial animation
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;
    
        if (callback!=false) {pieSeries.slices.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;}
    
    //chart.legend = new am4charts.Legend();
    
    pieSeries.slices.template.events.on("hit", function (ev) { 
      var data = ev.target.dataItem.dataContext;
      callback(data);
    });


    }); // end am4core.ready()


}