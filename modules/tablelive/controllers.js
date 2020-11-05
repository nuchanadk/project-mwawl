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

        $scope.set_style = function (item) {
            if (item == "HH") 
            {
                return { 'font-weight': 'bold',float: "right",color: "#FF0000" }
            }
            else if (item == "H") 
            {
                return { 'font-weight': 'bold',float: "right",color: "#ff9900" }
            }
            else if (item == "L") 
            {
                return { 'font-weight': 'bold',float: "right",color: "#e6e600" }
            }
            else if (item == "LL") 
            {
                return { 'font-weight': 'bold',float: "right",color: "#996600" }
            }
            else{ return { float: "right",color: "#000000" } }
        }
        
        $scope.date1 = new Date();
        $scope.date2 = new Date();
        $scope.showspinner = true;
        $scope.lastdata = [];

        $scope.datetitle = formatDate(new Date());

        //var exists = 0;

        var loadData=function(){  

            $http.post("modules/tablelive/selectdata.php").then(function(response){
               
                //console.log(response.data);
                if (response) { 
                    $scope.showspinner = false;
                } 
                $scope.datatable = response.data;
                
                //console.log($scope.lastdata);
                if($scope.lastdata.length === 0)
                    $scope.lastdata = $scope.datatable;

                for (var i=0; i< $scope.datatable.length; i++) {
                
                    //$scope.lastdata.push($scope.datatable[i]);
                    //console.log($scope.lastdata[i].stationID + '___' +$scope.datatable[i].stationID);
                    if ($scope.lastdata[i].stationID == $scope.datatable[i].stationID) {
                        //$scope.lastdata[i].dataValue = $scope.datatable[i].dataValue;
                        //console.log($scope.datatable[i].dataValue + '___' +$scope.lastdata[i].dataValue);
                        if($scope.datatable[i].dataValue > $scope.lastdata[i].dataValue)
                        {
                            $scope.datatable[i].dataup = true ;
                            $scope.datatable[i].datadown = false ;
                        
                        }
                        else if($scope.datatable[i].dataValue < $scope.lastdata[i].dataValue)
                        {
                            $scope.datatable[i].dataup = false ;
                            $scope.datatable[i].datadown = true ;
                        }
                        else
                        {
                            $scope.datatable[i].dataup = false ;
                            $scope.datatable[i].datadown = false ;
                        }
                    }
                    //console.log($scope.lastdata[i].dataValue);
                    $scope.lastdata[i].dataValue = $scope.datatable[i].dataValue ;
                    //console.log($scope.datatable[i].dataValue);
                    //exists = 1;
                }
                
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                console.log("error"+' '+response);
            });

            //console.log($scope.lastdata);
        }

        //console.log($scope.lastdata);

        loadData();
        $interval(loadData, 60000);

}]);