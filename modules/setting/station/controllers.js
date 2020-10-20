'use strict';
angular.module('Station',['ngRoute','ui.bootstrap']).controller('StationCtrl',
['$scope', '$http','$rootScope','$location','$modal','$timeout','$route', '$window',
function ($scope,$http,$rootScope,$location,$modal,$timeout,$route, $window ) {
    // โหลดข้อมูลตอนเริ่มต้น
    $scope.loadData = function()
    {
        $http.get('modules/setting/station/api.php?r=get').then(function(res){
            $scope.datastation = res.data;

        });

    }
    // โหลด select box device
    $http.get("modules/setting/station/selectdevice.php").then(function(response){
        
        $scope.selectdevice = response.data;
        
    });

    //ส่งข้อมูล เมื่อกด edit
    $scope.update_data = function(info) {

        $rootScope.dataud = info;
        
    }

    // Insert data station
    $scope.Insert = function()
    {
        var data ={
            'stationName':$scope.stationName,
            'deviceID':$scope.deviceID,
            'stationLat':$scope.stationLat,
            'stationLng':$scope.stationLng,
            'stationAddress':$scope.stationAddress,
            'type':'Insert'
           
        };
        $http.post('modules/setting/station/api.php',JSON.stringify(data)).then(function(response){

            if (response.data.textdata == "200") 
            {
                $scope.show = true;
                $scope.message = 'เพิ่มข้อมูลสำเร็จ';
                $timeout(function () {$window.location.href = "#/station";},4000);
               
            }
            else
            { 
                
                $scope.show = true;
                $scope.message = 'ไม่สามารถเพิ่มข้อมูลได้';
                 
               
             }


        });
    }

    $scope.update = function(info)
    {
        var data ={
            'id': info.id,
            'stationID': info.stationID,
            'stationName':info.stationName,
            'deviceID':info.deviceID,
            'stationLat':info.stationLat,
            'stationLng':info.stationLng,
            'stationAddress':info.stationAddress,
            'stationStatus':info.stationStatus,
            'type':'Update'
           
        };
        $http.post('modules/setting/station/api.php',JSON.stringify(data)).then(function(response){

            if (response.data.textdata == "200") 
            {
                $scope.show = true;
                $scope.message = 'เพิ่มข้อมูลสำเร็จ';
                $timeout(function () {$window.location.href = "#/station";},4000);
               
            }
            else
            { 
                
                $scope.show = true;
                $scope.message = 'ไม่สามารถเพิ่มข้อมูลได้';
                 
               
             }


        });
    }

    
    $scope.delete_data = function(info) {
        if (confirm("คุณต้องการลบข้อมูล?")) {

            var data = {
                'id': info.id,
                'type': 'Delete'
            }
            $http.post("modules/setting/station/api.php",JSON.stringify(data)).then(function(response){
                
            if (response.data.textdata == "200") 
            {
                
                alert("ลบข้อมูลสำเร็จ");
                $window.location.reload();
                
            }
            else
            { 
                alert("ไม่สามารถลบข้อมูลได้"); 
               
            }
            });
        } else {
            return false;
        }
    }

    
}]);