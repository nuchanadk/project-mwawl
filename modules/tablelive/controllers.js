'use strict';
 
angular.module('Tablelive', ['ngRoute'])
 
/*----------ค่าธรรมเนียม-----------*/
.controller('TableliveCtrl',
['$scope', '$http','$interval',
function ($scope,$http,$interval) {

    //console.log("TableliveCtrl");
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
        
        $scope.station = "";
        $scope.type = "";
        $scope.date1 = new Date();
        $scope.date2 = new Date();

        $scope.datetitle = formatDate(new Date());

        var loadData=function(){  

            var station = "DK20700010";
            var type = "10min";
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
            $http.post("modules/tablelive/selectdata.php",data).then(function(response){
               
                //console.log(response.data);
                $scope.datatable = response.data;

            });
        }

        loadData();
        $interval(loadData, 10000);

}]);