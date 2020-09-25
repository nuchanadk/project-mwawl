'use strict';
 
angular.module('Map', ['ngRoute'])
 
/*----------ค่าธรรมเนียม-----------*/
.controller('MapCtrl',
['$scope', '$http',
function ($scope,$http) {

    console.log("MapCtrl");
    /*function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }*/
        
        //$scope.today = new Date();
        //$scope.mydate = {};
        //$scope.mydate.when = null;

        /*$http.get("modules/setting/selecfishspec.php").then(function(response){
        
            $scope.fishspecies = response.data;
            //console.log($scope.fishspecies);
        });*/

}]);