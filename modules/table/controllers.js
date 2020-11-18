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

.factory('Excel',function($window){
    var uri='data:application/vnd.ms-excel;base64,',
        template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
        format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
    return {
        tableToExcel:function(tableId,worksheetName){
            var table=$(tableId),
                ctx={worksheet:worksheetName,table:table.html()},
                href=uri+base64(format(template,ctx));
            return href;
        }
    };
})

/*----------ตาราง-----------*/
.controller('TableCtrl',
['$scope', '$http','Excel','$timeout','$location',
function ($scope,$http,Excel,$timeout,$location) {

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

        $scope.showexport = false;

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
                if (response) { 
                    $scope.showexport = true;
                } 
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

        $scope.exportData = function(){
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
            $http.post("modules/table/selectdataexport.php",data).then(function(response){

                $scope.data = response.data;
                //console.log($scope.data);
                var arrayToExport  = [$scope.data];
                var opts = [{
                            sheetid: station, 
                            headers:true, 
                            column: {
                                style:{
                                    Font:{
                                        Bold:"1",
                                        Color:"#3C3741"
                                    },
                                    Alignment:{
                                        Horizontal:"Center"
                                    },
                                    Interior:{
                                        Color:"#7CEECE",
                                        Pattern:"Solid"
                                    }
                                }
                            }
                }];
                var namesheet =  station +'.xlsx' ;
                alasql("SELECT  INTO XLSX('"+namesheet+"',?) FROM ?", [opts, arrayToExport ]);
            });
        }
    

        /*$scope.exportToExcel=function(tableId,stn){ // ex: '#my-table'
            //console.log(stn);
            var exportHref=Excel.tableToExcel(tableId,stn);
            $timeout(function()
            {
                window.location.href = exportHref;
            },100); // trigger download
        }*/

}]);