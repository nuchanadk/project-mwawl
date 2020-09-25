'use strict';
 
angular.module('Setting', ['ngRoute','ui.bootstrap'])

/*----------ระดับ-----------*/
.controller('LevelCtrl',
['$scope', '$http','$rootScope','$location','$modal', '$window',
function ($scope,$http,$rootScope,$location,$modal, $window ) {

    function Alertadd(alart,type,mess) {

        $scope.ResponseModel = {};
        $scope.ResponseModel.ResponseAlert = alart;
        $scope.ResponseModel.ResponseType = type;
        $scope.ResponseModel.ResponseMessage = mess  ;
        /*$("#alertdiv").fadeTo(2000, 500).slideUp(500, function(){
            $("#alertdiv").slideUp(500);
        });*/
    
        return $scope.ResponseModel;
        
    }

    $scope.loadData=function(){  

        $http.get("modules/setting/selectdatalevel.php").then(function(response){
            
        //console.log(response.data);
        $scope.datalevel = response.data;

        /*$('#Tablelevel').DataTable({
        });*/

        /*var oTable = $('#Tablelevel').dataTable();
		 
        var data = oTable._('tr:first');
        
        // Do something useful with the data
        alert( "First cell is: "+data[0] );*/

        /*$scope.viewby = 20;
        $scope.totalItems = $scope.listuser.length;
        $scope.currentPage = 1;
        $scope.itemsPerPage = $scope.viewby;
        $scope.maxSize = 5; //Number of pager buttons to show
    
        $scope.setPage = function(pageNo) {
            $scope.currentPage = pageNo;
        };
    
        $scope.pageChanged = function() {
            console.log('Page changed to: ' + $scope.currentPage);
        };
    
        $scope.setItemsPerPage = function(num) {
            $scope.itemsPerPage = num;
            $scope.currentPage = 1; //reset to first page
        }*/
        });
    }

    $scope.insert = function() {

        var data = {
            'deviceID': $scope.deviceID,
            'levelUp': $scope.levelUp,
            'levelDown': $scope.levelDown,
            'type': 'Insert'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedatalevel.php",JSON.stringify(data)).then(function(response){
            //console.log(response);
            if (response.data.textdata == "200") 
            {
                alert("บันทึกข้อมูลสำเร็จ");
                $window.location.href = "#/level";
                //$location.replace();
            }
            else
            { alert("ไม่สามารถบันทึกข้อมูลได้"); }
        });
    }

    $scope.update_data = function(info) {

        $rootScope.dataud = info;
        //console.log($rootScope.dataud);
    }

    $scope.update = function(info) {
        //console.log(info);
        var data = {
            'id': info.id,
            'deviceID': info.deviceID,
            'levelUp': info.levelUp,
            'levelDown': info.levelDown,
            'type': 'Update'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedatalevel.php",JSON.stringify(data)).then(function(response){
            //console.log(response);
            if (response.data.textdata == "200") 
            {
                alert("แก้ไขข้อมูลสำเร็จ");
                $window.location.href = "#/level";
                //$location.path('#/level');
                //$location.replace();
            }
            else
            { alert("ไม่สามารถแก้ไขข้อมูลได้"); }
        });
    }

    $scope.delete_data = function(info) {
        if (confirm("คุณต้องการลบข้อมูล?")) {

            var data = {
                'id': info.id,
                'type': 'Delete'
            }
            $http.post("modules/setting/inupdedatalevel.php",JSON.stringify(data)).then(function(response){
                //console.log(response);
            if (response.data.textdata == "200") 
            {
                //alert("ลบข้อมูลสำเร็จ");
                //$scope.mess = "ลบข้อมูลสำเร็จ";
                alert("ลบข้อมูลสำเร็จ");
                $window.location.href = "#/level";
                //$scope.dataalart = Alertadd(true,"success",$scope.mess);
                //console.log($scope.dataalart);
                /*$("#alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#alert").slideUp(500);
                });*/
                //$window.location.href = "#/level";
            }
            else
            { 
                alert("ไม่สามารถลบข้อมูลได้"); 
                //$scope.mess = "ไม่สามารถลบข้อมูลได้";
                //$scope.dataalart = Alertadd(true,"danger",$scope.mess);
                //$("#alert-danger").fadeTo(2000, 500).slideUp(500, function(){
                //    $("#alert-danger").slideUp(500);
               // });
            }
            });
        } else {
            return false;
        }
    }
}])


/*----------ผู้ใช้งาน-----------*/
.controller('UserCtrl',
['$scope', '$http','$rootScope','$location','$modal', '$window',
function ($scope,$http,$rootScope,$location,$modal, $window) {

    $scope.loadData=function(){  

        $http.get("modules/setting/selectdatauser.php").then(function(response){
    
        $scope.datauser = response.data;

        //console.log($scope.datauser);

        /*$('#Tableuser').DataTable({
        });*/

        });
    }

    $scope.insert = function() {

        var data = {
            'uEmail': $scope.uEmail,
            'uPassword': $scope.uPassword,
            'uSurname': $scope.uSurname,
            'uLastname': $scope.uLastname,
            'type': 'Insert'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedatauser.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("บันทึกข้อมูลสำเร็จ");
                $window.location.href = "#/user";
                //$location.replace();
            }
            else
            { alert("ไม่สามารถบันทึกข้อมูลได้"); }
        });
    }

    $scope.update_data = function(info) {

        $rootScope.dataud = info;
        //console.log($rootScope.dataud);
    }

    $scope.update = function(info) {
        //console.log(info);
        var data = {
            'id': info.id,
            'uEmail': info.uEmail,
            'uPassword': info.uPassword,
            'uSurname': info.uSurname,
            'uLastname': info.uLastname,
            'uStatus': info.uStatus,
            'active': info.active,
            'type': 'Update'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedatauser.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("แก้ไขข้อมูลสำเร็จ");
                $window.location.href = "#/user";
                //$location.path('#/level');
                //$location.replace();
            }
            else
            { alert("ไม่สามารถแก้ไขข้อมูลได้"); }
        });
    }

    $scope.delete_data = function(info) {
        if (confirm("คุณต้องการลบข้อมูล?")) {

            var data = {
                'id': info.id,
                'type': 'Delete'
            }
            $http.post("modules/setting/inupdedatauser.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("ลบข้อมูลสำเร็จ");
                $window.location.href = "#/user";
                //$location.path('#/level');
                //$location.replace();
            }
            else
            { alert("ไม่สามารถลบข้อมูลได้"); }
            });
        } else {
            return false;
        }
    }
}])

/*----------แจ้งเตือน-----------*/
.controller('AlarmCtrl',
['$scope', '$http','$rootScope','$location','$modal', '$window',
function ($scope,$http,$rootScope,$location,$modal, $window) {

    $scope.loadData=function(){  

        $http.get("modules/setting/selectdataalarm.php").then(function(response){

            if (response.message == "") 
            {
                $scope.dataalarm = response.data;
                console.log($scope.dataalarm);
            }
            else
            { 
                $scope.message = response.message; 
            }
        });
    }

    $scope.insert = function() {

        var data = {
            'deviceID': $scope.deviceID,
            'alarmLL': $scope.alarmLL,
            'alarmL': $scope.alarmL,
            'alarmH': $scope.alarmH,
            'alarmHH': $scope.alarmHH,
            'alarmStatus': '1',
            'type': 'Insert'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedataalarm.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("บันทึกข้อมูลสำเร็จ");
                $window.location.href = "#/alarm";
                //$location.replace();
            }
            else
            { alert("ไม่สามารถบันทึกข้อมูลได้"); }
        });
    }

    $scope.update_data = function(info) {

        $rootScope.dataud = info;
        //console.log($rootScope.dataud);
    }

    $scope.update = function(info) {
        //console.log(info);
        var data = {
            'id': info.id,
            'deviceID': info.deviceID,
            'alarmLL': info.alarmLL,
            'alarmL': info.alarmL,
            'alarmH': info.alarmH,
            'alarmHH': info.alarmHH,
            'alarmStatus': info.alarmStatus,
            'type': 'Update'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedataalarm.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("แก้ไขข้อมูลสำเร็จ");
                $window.location.href = "#/alarm";
                //$location.path('#/level');
                //$location.replace();
            }
            else
            { alert("ไม่สามารถแก้ไขข้อมูลได้"); }
        });
    }

    $scope.delete_data = function(info) {
        if (confirm("คุณต้องการลบข้อมูล?")) {

            var data = {
                'id': info.id,
                'type': 'Delete'
            }
            $http.post("modules/setting/inupdedataalarm.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("ลบข้อมูลสำเร็จ");
                $window.location.href = "#/alarm";
                //$location.path('#/level');
                //$location.replace();
            }
            else
            { alert("ไม่สามารถลบข้อมูลได้"); }
            });
        } else {
            return false;
        }
    }
}])

/*----------token-----------*/
.controller('TokenCtrl',
['$scope', '$http','$rootScope','$location','$modal', '$window',
function ($scope,$http,$rootScope,$location,$modal, $window) {

    $scope.loadData=function(){  

        $http.get("modules/setting/selectdatatoken.php").then(function(response){
    
        $scope.datatoken = response.data;

        //console.log($scope.datalevel);

        });
    }

    $scope.insert = function() {

        var data = {
            'Token': $scope.Token,
            'tokenStatus': '1',
            'type': 'Insert'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedatatoken.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("บันทึกข้อมูลสำเร็จ");
                $window.location.href = "#/token";
                //$location.replace();
            }
            else
            { alert("ไม่สามารถบันทึกข้อมูลได้"); }
        });
    }

    $scope.update_data = function(info) {

        $rootScope.dataud = info;
        //console.log($rootScope.dataud);
    }

    $scope.update = function(info) {
        //console.log(info);
        var data = {
            'id': info.id,
            'Token': info.Token,
            'tokenStatus': info.tokenStatus,
            'type': 'Update'
        }
        //console.log(data);
        $http.post("modules/setting/inupdedatatoken.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("แก้ไขข้อมูลสำเร็จ");
                $window.location.href = "#/token";
                //$location.path('#/level');
                //$location.replace();
            }
            else
            { alert("ไม่สามารถแก้ไขข้อมูลได้"); }
        });
    }

    $scope.delete_data = function(info) {
        if (confirm("คุณต้องการลบข้อมูล?")) {

            var data = {
                'id': info.id,
                'type': 'Delete'
            }
            $http.post("modules/setting/inupdedatatoken.php",JSON.stringify(data)).then(function(response){
            //console.log(response.data);
            if (response.data.textdata == "200") 
            {
                alert("ลบข้อมูลสำเร็จ");
                $window.location.href = "#/token";
                //$location.path('#/level');
                //$location.replace();
            }
            else
            { alert("ไม่สามารถลบข้อมูลได้"); }
            });
        } else {
            return false;
        }
    }
}]);