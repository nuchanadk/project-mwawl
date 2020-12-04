'use strict';
am4core.useTheme(am4themes_animated);
angular.module('Chartmodules', ['ngRoute'])
 
/*----------กราฟ-----------*/
.controller('ChartCtrl',
['$scope', '$http',
function ($scope,$http) {

    //console.log("ChartCtrl");
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

        
    $scope.station = "";
    $scope.type = "";
    $scope.date1 = new Date();
    $scope.date2 = new Date();

    $scope.searchData=function(){  

        var station = $scope.station;
        var type = "10MIN";
        var dates = formatDate($scope.date1)+' '+"00:00";
        var datee = formatDate($scope.date2)+' '+"23:59";

        //console.log(station+'-'+type+'-'+dates+'-'+datee);

        var data = {
            'stationID': station,
            'dates': dates,
            'datee': datee,
            'type': type
        }
        //console.log(data);
        $http.post("modules/chart/selectdata.php",data).then(function(response){
           
            //console.log(response);
            $scope.datatable = response.data;
            //console.log($scope.datatable.length);
            if($scope.datatable.length == 0 )
		    return;
            $scope.series =  $scope.datatable[0].stationName;
            
            /*var chartjsData = [];
            for (var i = 0; i < $scope.datatable.length; i++) {
                chartjsData.push($scope.datatable[i].dataValue);
            }

            var chartjsLabels = [];
            for (var i = 0; i < $scope.datatable.length; i++) {
                chartjsLabels.push($scope.datatable[i].dataDatetime);
            }*/

            var data = [];
            for (var i = 0; i < $scope.datatable.length; i++) {


                var dt = new Date($scope.datatable[i].chartDatetime);
                var dt_m =  dt.getMonth();
                var dt_d = dt.getDate();
                var dt_y = dt.getFullYear();
                var dt_h = dt.getHours();
                var dt_min = dt.getMinutes()

                //console.log(dt_y+'_'+dt_m+'_'+dt_d+'_'+dt_h+'_'+dt_min);
                data.push({ date: new Date(dt_y, dt_m, dt_d ,dt_h,dt_min), value: $scope.datatable[i].dataValue });
            }

            //console.log(data);

            var chart = am4core.create("chartdiv", am4charts.XYChart);

            
            am4core.useTheme(am4themes_animated);
            chart.paddingRight = 20;

            chart.data = data;
            //console.log(data);

            //chart.numberFormatter.numberFormat = "#.##";
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            //dateAxis.renderer.grid.template.location = 0;
            //dateAxis.dateFormats.setKey("dd/MM/yyyy H:m");
            //dateAxis.renderer.grid.template.location = 0;
            dateAxis.renderer.labels.template.location = 0;
            dateAxis.renderer.labels.template.rotation = 305;
		    dateAxis.renderer.labels.template.verticalCenter = "middle";
		    dateAxis.renderer.labels.template.horizontalCenter = "right";
            //dateAxis.renderer.minGridDistance = 30;
            //dateAxis.dateFormats.setKey("month", "[font-size: 12px]MM");
            //dateAxis.periodChangeDateFormats.setKey("day", "[bold]dd");

            dateAxis.title.text = "วัน-เวลา";

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.tooltip.disabled = true;
            //valueAxis.renderer.minWidth = 35;
            valueAxis.title.text = "ระดับน้ำ ม.(รทก.)";

            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.dateX = "date";
            series.dataFields.valueY = "value";
            series.name = $scope.series;
            series.tensionX = 0.8;
            series.tensionY = 0.7;

            //series.tooltipText = "Series: {name}\n Category: {dateX}\nValue: [b]{valueY.formatNumber('###.##')}[/]";
            series.tooltipText = "{valueY.formatNumber('#.##')}";

            chart.numberFormatter.numberFormat = '###.##';

            //chart.dateFormatter.dateFormat = "dd/MM/yyyy H:m";
            //chart.dateFormatter.inputDateFormat = "dd/MM/yyyy H:m";

            var title = chart.titles.create();
		    title.text = "กราฟข้อมูลระดับน้ำ";
		    title.fontSize = 18;
		    title.marginBottom = 30;

            // Export
            chart.exporting.menu = new am4core.ExportMenu();
            chart.exporting.filePrefix = $scope.series;

            chart.legend = new am4charts.Legend();
            
            chart.cursor = new am4charts.XYCursor();
            chart.cursor.lineY.disabled = true;
            chart.cursor.lineX.disabled = true;

            //var scrollbarX = new am4charts.XYChartScrollbar();
            //scrollbarX.series.push(series);
            //chart.scrollbarX = scrollbarX;

            chart.scrollbarX = new am4core.Scrollbar();
            chart.logo.disabled = true;
            chart.responsive.enabled = true;
            
        });
    }
}]);