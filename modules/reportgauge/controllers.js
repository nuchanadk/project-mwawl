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
	
	$http.get("modules/table/selectstn.php").then(function(response){
        
		$scope.selectstn = response.data;
		//console.log($scope.selectstn);
	});

	function createchartData(jsondata , type){  

		//console.log(jsondata);
		if(jsondata.length == 0 )
		return;
		//$scope.series =  jsondata[0].deviceID;
		var data = [];
		for (var i = 0; i < jsondata.length; i++) {

			var dt = new Date(jsondata[i].dataDatetime);
			var dt_m =  dt.getMonth();
			var dt_d = dt.getDate();
			var dt_y = dt.getFullYear();
			var dt_h = dt.getHours();
            var dt_min = dt.getMinutes();
			
			data.push({ datadate: jsondata[i].dataDatetime, STN1: jsondata[i].STN01, STN2: jsondata[i].STN02
				, STN3: jsondata[i].STN03, STN4: jsondata[i].STN04, STN5: jsondata[i].STN05, STN6: jsondata[i].STN06
				, STN7: jsondata[i].STN07, STN8: jsondata[i].STN08, STN9: jsondata[i].STN09, STN10: jsondata[i].STN10 });
		}

		var chart = am4core.create("chartdiv", am4charts.XYChart);
		am4core.options.autoDispose = true;
		am4core.useTheme(am4themes_animated);
		chart.logo.disabled = true;
		chart.colors.step = 2;		
		chart.numberFormatter.numberFormat = "#.#'%'";

		var dateAxis = chart.xAxes.push(new am4charts.CategoryAxis())
		dateAxis.dataFields.category = 'datadate'
		dateAxis.renderer.grid.template.location = 0;
		dateAxis.renderer.labels.template.location = 0;
		//dateAxis.renderer.labels.template.rotation = 305;
		dateAxis.renderer.labels.template.verticalCenter = "middle";
		//dateAxis.renderer.labels.template.horizontalCenter = "right";
		//dateAxis.renderer.cellStartLocation = 0.1
		//xAxis.renderer.cellEndLocation = 0.9
		//xAxis.renderer.grid.template.location = 0;

		// Configure axis label
		var label = dateAxis.renderer.labels.template;
		label.truncate = true;
		label.wrap = true;
		label.maxWidth = 140;
		label.tooltipText = "{category}";

		dateAxis.events.on("sizechanged", function(ev) {
		var axis = ev.target;
		var cellWidth = axis.pixelWidth / (axis.endIndex - axis.startIndex);
		if (cellWidth < axis.renderer.labels.template.maxWidth) {
			axis.renderer.labels.template.rotation = -45;
			axis.renderer.labels.template.horizontalCenter = "right";
			axis.renderer.labels.template.verticalCenter = "middle";
		}
		else {
			axis.renderer.labels.template.rotation = 0;
			axis.renderer.labels.template.horizontalCenter = "middle";
			axis.renderer.labels.template.verticalCenter = "top";
		}
		});

		var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
		yAxis.min = 0;
		yAxis.max = 100;
		yAxis.strictMinMax = true;
		yAxis.renderer.minGridDistance = 10;

		function createSeries(value, name) {
			var series = chart.series.push(new am4charts.ColumnSeries())
			series.dataFields.valueY = value
			series.dataFields.categoryX = 'datadate'
			series.name = name

			//series.events.on("hidden", arrangeColumns);
			//series.events.on("shown", arrangeColumns);

			//series.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX} : [bold]{valueY}";
			// Configure legend
			series.legendSettings.itemValueText = "[bold]{valueY}";

			//var bullet = series.bullets.push(new am4charts.LabelBullet())
			//bullet.interactionsEnabled = true
			//bullet.dy = 30;
			//bullet.label.fill = am4core.color('#ffffff')

			return series;
		}

		chart.data = data;
		//console.log(data);

		createSeries('STN1', 'ประตูน้ำท่าม่วง(เหนือน้ำ)');
		createSeries('STN2', 'ประตูน้ำท่าม่วง(ท้ายน้ำ)');
		createSeries('STN3', 'ไซฟอน (เหนือน้ำ)');
		createSeries('STN4', 'ไซฟอน (ท้ายน้ำ)');
		createSeries('STN5', 'อาคารระบายน้ำฉุกเฉิน');
		createSeries('STN6', 'สถานีสูบน้ำบางเลน (เหนือน้ำ)');
		createSeries('STN7', 'สถานีสูบน้ำบางเลน (ท้ายน้ำ)');
		createSeries('STN8', 'ไซฟอนบางภาษี (เหนือน้ำ)');
		createSeries('STN9', 'ไซฟอนมะสง (เหนือน้ำ)');
		createSeries('STN10', 'ไซฟอนบางใหญ่ (ท้ายน้ำ)');

		function arrangeColumns() {

			var series = chart.series.getIndex(0);
			var w = 1 - dateAxis.renderer.cellStartLocation - (1 - dateAxis.renderer.cellEndLocation);
			if (series.dataItems.length > 1) {
				var x0 = dateAxis.getX(series.dataItems.getIndex(0), "categoryX");
				var x1 = dateAxis.getX(series.dataItems.getIndex(1), "categoryX");
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

		var typename = (type == 'day') ? "รายวัน" : "รายเดือน";

		var title = chart.titles.create();
		title.text = "% ข้อมูลที่ได้รับจากเครื่องวัด ต่อจำนวนข้อมูลทั้งหมด "+ typename ;
		title.fontSize = 16;
		title.marginBottom = 30;

		// Export
		chart.exporting.menu = new am4core.ExportMenu();
		chart.exporting.filePrefix = $scope.series;
		chart.logo.disabled = true;
		chart.cursor = new am4charts.XYCursor();
		chart.cursor.lineY.disabled = true;
		chart.cursor.lineX.disabled = true;
	
		chart.legend = new am4charts.Legend();
		//chart.legend.scrollable = true;
		//chart.legend.position = "right";	
		//chart.legend.labels.template.wrap = true;
		chart.legend.valueLabels.template.textAlign = "end"; 

		// Responsive
		chart.responsive.enabled = true;
		chart.responsive.useDefault = false

		chart.responsive.rules.push({
		relevant: function(target) {
			if (target.pixelWidth <= 400) {
			return true;
			}
			
			return false;
		},
		state: function(target, stateId) {
			
			if (target instanceof am4charts.Chart) {
			var state = target.states.create(stateId);
			state.properties.paddingTop = 0;
			state.properties.paddingRight = 15;
			state.properties.paddingBottom = 5;
			state.properties.paddingLeft = 15;
			return state;
			}
			
			if (target instanceof am4core.Scrollbar) {
			var state = target.states.create(stateId);
			state.properties.marginBottom = -10;
			return state;
			}
			
			if (target instanceof am4charts.Legend) {
			var state = target.states.create(stateId);
			state.properties.paddingTop = 0;
			state.properties.paddingRight = 0;
			state.properties.paddingBottom = 0;
			state.properties.paddingLeft = 0;
			state.properties.marginLeft = 0;
			return state;
			}
			
			if (target instanceof am4charts.AxisRendererY) {
			var state = target.states.create(stateId);
			state.properties.inside = true;
			state.properties.maxLabelPosition = 0.99;
			return state;
			}
			
			if ((target instanceof am4charts.AxisLabel) && (target.parent instanceof am4charts.AxisRendererY)) { 
			var state = target.states.create(stateId);
			state.properties.dy = -15;
			state.properties.paddingTop = 3;
			state.properties.paddingRight = 5;
			state.properties.paddingBottom = 3;
			state.properties.paddingLeft = 10;
			
			// Create a separate state for background
			target.setStateOnChildren = true;
			var bgstate = target.background.states.create(stateId);
			bgstate.properties.fill = am4core.color("#fff");
			bgstate.properties.fillOpacity = 0.7;
			
			return state;
			}
			
			return null;
			}
		});
	}

	$scope.station = "";
	$scope.type = "month";
	$scope.dtyear = "2020";
    $scope.date1 = new Date();
	$scope.date2 = new Date();
	$scope.showyear = true;
	$scope.showdate = false;

	$scope.changedivdt = function() 
	{
		if($scope.type == "day")
		{
			$scope.showyear = false;
			$scope.showdate = true;
		}
		else
		{
			$scope.showyear = true;
			$scope.showdate = false;
		}
		//console.log($scope.type)
	}

	$scope.loadData=function(){  

        var dates = formatDate($scope.date1)+' '+"00:00";
		var datee = formatDate($scope.date2)+' '+"23:59";
		var type = $scope.type;
		var dyear = $scope.dtyear+'-01-01';

        //console.log(station+'-'+type+'-'+dates+'-'+datee);

        var data = {
            'dates': dates,
			'datee': datee,
			'type': type,
			'dyear': dyear
        }
        //console.log(data);
        $http.post("modules/reportgauge/selectdata.php",data).then(function(response){
           
            //console.log(response.data);
			$scope.datatable = response.data;
			
			createchartData($scope.datatable,type);
            /**/
		});
	}


    $scope.searchData=function(){  

        var dates = formatDate($scope.date1)+' '+"00:00";
		var datee = formatDate($scope.date2)+' '+"23:59";
		var type = $scope.type;
		var dyear = $scope.dtyear+'-01-01';

        //console.log(station+'-'+type+'-'+dates+'-'+datee);

        var data = {
            'dates': dates,
			'datee': datee,
			'type': type,
			'dyear': dyear
        }
        //console.log(data);
        $http.post("modules/reportgauge/selectdata.php",data).then(function(response){
           
            //console.log(response.data);
            $scope.datatable = response.data;
			createchartData($scope.datatable,type);
            
        });
    }
}]);