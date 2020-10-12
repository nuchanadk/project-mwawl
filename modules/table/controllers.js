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

        /*$http.get("modules/setting/selecfishspec.php").then(function(response){
        
            $scope.fishspecies = response.data;
            //console.log($scope.fishspecies);
        });*/

        $scope.loadData=function(){  

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
            $http.post("modules/table/selectdata.php",data).then(function(response){
               
                //console.log(response.data);
                $scope.datatable = response.data;

                $scope.currentPage = 1; //current page
				$scope.entryLimit = 20; //max no of items to display in a page
				$scope.maxSize = 5;
				$scope.filteredItems = $scope.datatable.length; //Initially for no filter  
                $scope.totalItems = $scope.datatable.length;
            });
        }

        $scope.searchData=function(){  

            var station = $scope.station;
            var type = $scope.type;
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
            $http.post("modules/table/selectdata.php",data).then(function(response){
               
                //console.log(response.data);
                $scope.datatable = response.data;

                $scope.currentPage = 1; //current page
				$scope.entryLimit = 20; //max no of items to display in a page
				$scope.maxSize = 5;
				$scope.filteredItems = $scope.datatable.length; //Initially for no filter  
                $scope.totalItems = $scope.datatable.length;
                /*if (response.data.textdata == "200") 
                {
                    $scope.message = 'แก้ไขข้อมูลสำเร็จ';
                }
                else
                { 
                    $scope.message = 'ไม่สามารถแก้ไขข้อมูลได้';
                }*/

            });
        }

}]);