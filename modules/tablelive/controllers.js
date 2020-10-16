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
        
        $scope.date1 = new Date();
        $scope.date2 = new Date();
        $scope.show = true;

        $scope.datetitle = formatDate(new Date());

        var loadData=function(){  

            $http.post("modules/tablelive/selectdata.php").then(function(response){
               
                //console.log(response.data);
                if (response) { 
                    $scope.show = false;
                } 
                $scope.datatable = response.data;

            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                console.log("error"+' '+response);
            });
        }

        loadData();
        $interval(loadData, 60000);

}]);