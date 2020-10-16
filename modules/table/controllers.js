'use strict';
 
angular.module('Table', ['ngRoute','ui.bootstrap'])
 
.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
})

/*----------ตาราง-----------*/
.controller('TableCtrl',
['$scope', '$http',
function ($scope,$http) {

    //console.log("TableCtrl");

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

        $http.get("modules/table/selectstn.php").then(function(response){
        
            $scope.selectstn = response.data;
            //console.log($scope.selectstn);
        });

        $http.get("modules/table/selectdevice.php").then(function(response){
        
            $scope.selectdevice = response.data;
            //console.log($scope.selectdevice);
        });

        $scope.searchData=function(){  

            var station = $scope.station;
            var type = $scope.type;
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
            $http.post("modules/table/selectdata.php",data).then(function(response){
               
                //console.log(response.data);
                $scope.datatable = response.data;

                $scope.currentPage = 1; //current page
				$scope.entryLimit = 20; //max no of items to display in a page
				$scope.maxSize = 5;
				$scope.filteredItems = $scope.datatable.length; //Initially for no filter  
                $scope.totalItems = $scope.datatable.length;

            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                console.log("error"+' '+response);
            });
        }

}]);