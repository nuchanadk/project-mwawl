'use strict';
angular.module('Device',['ngRoute','ui.bootstrap']).controller('DeviceCtrl',
['$scope', '$http','$rootScope','$location','$modal','$timeout','$route', '$window',
function ($scope,$http,$rootScope,$location,$modal,$timeout,$route, $window ) {

    $http.get("modules/setting/device/api.php?r=get").then(function(response){
        $scope.datadevice = response.data;

    });

    $scope.update_data = function(info)
    {
        $rootScope.dataud = info;

    }

        // Insert data station
        $scope.insert = function()
        {
            var data ={
                
                'deviceID':$scope.deviceID,
               
                'type':'Insert'
               
            };
            $http.post('modules/setting/device/api.php',JSON.stringify(data)).then(function(response){
    
                if (response.data.textdata == "200") 
                {
                    $scope.show = true;
                    $scope.message = 'เพิ่มข้อมูลสำเร็จ';
                    $timeout(function () {$window.location.href = "#/device";},4000);
                   
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
                
                'deviceID':info.deviceID,
               
                'deviceStatus':info.deviceStatus,
                'type':'Update'
               
            };
            $http.post('modules/setting/device/api.php',JSON.stringify(data)).then(function(response){
    
                if (response.data.textdata == "200") 
                {
                    $scope.show = true;
                    $scope.message = 'เพิ่มข้อมูลสำเร็จ';
                    $timeout(function () {$window.location.href = "#/device";},4000);
                   
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
                $http.post("modules/setting/device/api.php",JSON.stringify(data)).then(function(response){
                    
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