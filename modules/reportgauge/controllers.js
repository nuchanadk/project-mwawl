'use strict';
 
angular.module('Reportgauge', ['ngRoute'])
 
/*----------ข้อมูลจากเครื่องวัด-----------*/
.controller('ReportgaugeCtrl',
['$scope', '$http',
function ($scope,$http) {

    //console.log("ReportgaugeCtrl");
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }	

	function createchartData(jsondata){  

		$scope.series =  jsondata[0].deviceID;
		var data = [];
		for (var i = 0; i < jsondata.length; i++) {

			var dt = new Date(jsondata[i].chartDatetime);
			var dt_m =  dt.getMonth();
			var dt_d = dt.getDate();
			var dt_y = dt.getFullYear();
			var dt_h = dt.getHours();
			var dt_min = dt.getMinutes()
			
			data.push({ date: new Date(dt_y, dt_m, dt_d ,dt_h), value: jsondata[i].dataValue });
		}

		am4core.useTheme(am4themes_animated);
		var chart = am4core.create("chartdiv", am4charts.XYChart);

		// Responsive
		chart.responsive.enabled = true;
		chart.responsive.useDefault = false;

		chart.responsive.rules.push({
		relevant: function(target) {
			if (target.pixelWidth <= 800) {
			return true;
			}
			
			return false;
		},
		state: function(target, stateId) {
			
			if (target instanceof am4charts.Chart) {
			var state = target.states.create(stateId);
			state.properties.paddingTop = 0;
			state.properties.paddingRight = 25;
			state.properties.paddingBottom = 0;
			state.properties.paddingLeft = 25;
			return state;
			} else if (target instanceof am4charts.AxisLabelCircular ||
			target instanceof am4charts.PieTick) {
			var state = target.states.create(stateId);
			state.properties.disabled = true;
			return state;
			}
			return null;
		}
		})

		chart.data = data;
		
		// Create axes
		var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
		//dateAxis.renderer.grid.template.location = 0.5;
		//dateAxis.renderer.labels.template.location = 0.5;
		//dateAxis.renderer.minGridDistance = 30;

		dateAxis.renderer.minGridDistance = 60;

		dateAxis.renderer.grid.template.location = 0;
		dateAxis.renderer.labels.template.location = 0;
		dateAxis.renderer.labels.template.rotation = 305;
		dateAxis.renderer.labels.template.verticalCenter = "middle";
		dateAxis.renderer.labels.template.horizontalCenter = "right";

		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

		// Create series
		function createSeries(field, name) {
		var series = chart.series.push(new am4charts.ColumnSeries());
		series.dataFields.valueY = field; 
		series.dataFields.dateX = "date"; 
		series.name = name;   
		series.tooltipText = "[b]{valueY}[/]";
		//series.tooltipText = "Series: {name}\n Category: {dateX}\nValue: [b]{valueY}[/]";
		series.strokeWidth = 2;

		}

		createSeries("value", $scope.series);

		var title = chart.titles.create();
		title.text = "% ข้อมูลที่ได้รับจากเครื่องวัด ต่อจำนวนข้อมูลทั้งหมด รายเดือน";
		title.fontSize = 18;
		title.marginBottom = 30;

		// Export
		chart.exporting.menu = new am4core.ExportMenu();
		chart.exporting.filePrefix = "PT";
		//chart.dateFormatter.dateFormat = "dd/MM/yyyy H:m";
        //chart.dateFormatter.inputDateFormat = "dd/MM/yyyy H:m";
		chart.logo.disabled = true;
		chart.cursor = new am4charts.XYCursor();
		chart.cursor.lineY.disabled = true;
		chart.cursor.lineX.disabled = true;
		
		
		//chart.scrollbarX = new am4core.Scrollbar();
    }

	$scope.station = "";
    $scope.type = "";
    $scope.date1 = new Date();
	$scope.date2 = new Date();

	$scope.loadData=function(){  

        var station = "DK20700010";
        var type = "10MIN";
        var dates = formatDate($scope.date1)+' '+"00:00";
        var datee = formatDate($scope.date2)+' '+"23:59";

        //console.log(station+'-'+type+'-'+dates+'-'+datee);

        var data = {
            'deviceID': station,
            'dates': dates,
            'datee': datee,
            'type': type
        }
        //console.log(data);
        $http.post("modules/reportgauge/selectdata.php",data).then(function(response){
           
            //console.log(response.data);
			$scope.datatable = response.data;
			
			createchartData($scope.datatable);

            /*var chart = am4core.create("chartdiv", am4charts.XYChart);
			am4core.useTheme(am4themes_animated);
			chart.logo.disabled = true;
			chart.colors.step = 2;		


            chart.legend = new am4charts.Legend()
			chart.legend.position = 'top'
			chart.legend.paddingBottom = 20
			chart.legend.labels.template.maxWidth = 95

			var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
			xAxis.dataFields.category = 'date'
			xAxis.renderer.cellStartLocation = 0.1
			xAxis.renderer.cellEndLocation = 0.9
			xAxis.renderer.grid.template.location = 0;

			var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
			yAxis.min = 0;

			function createSeries(value, name) {
				var series = chart.series.push(new am4charts.ColumnSeries())
				series.dataFields.valueY = value
				series.dataFields.categoryX = 'date'
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

			createSeries('value', $scope.series);
			//createSeries('second', 'The Second');
			//createSeries('third', 'The Third');

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
			}*/
		});
	}


    $scope.searchData=function(){  

        var station = $scope.station;
        var type = "10MIN";
        var dates = formatDate($scope.date1)+' '+"00:00";
        var datee = formatDate($scope.date2)+' '+"23:59";

        //console.log(station+'-'+type+'-'+dates+'-'+datee);

        var data = {
            'deviceID': station,
            'dates': dates,
            'datee': datee,
            'type': type
        }
        //console.log(data);
        $http.post("modules/reportgauge/selectdata.php",data).then(function(response){
           
            //console.log(response.data);
            $scope.datatable = response.data;

			createchartData($scope.datatable);
            
        });
    }
}]);