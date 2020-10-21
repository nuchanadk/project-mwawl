'use strict';
 
angular.module('Map', ['ngRoute','leaflet-directive'])
 
/*----------ค่าธรรมเนียม-----------*/
.controller('MapCtrl',
['$scope', '$http','leafletMarkerEvents',
function ($scope,$http, leafletMarkerEvents) {

    //console.log("MapCtrl");
    angular.extend($scope, {
      center: {
          lat: 13.9,
          lng: 100.05,
          zoom: 10
      },
      icons: local_icons,
      layers: {
        baselayers: {
            xyz: {
                name: 'OpenStreetMap (XYZ)',
                url: 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                type: 'xyz'
            }
        }
      },
      events: {
          markers: {
            enable: ['dragend', 'contextmenu', 'drag', 'click', 'mouseover','mouseout', 'popupopen','popupclose']
            //logic: 'emit'
          }
      }
  });

    var local_icons = {
      default_icon: {},
      leaf_icon: {
          iconUrl: 'vendor/img/Home_Icon_128.png',
          iconSize: [60, 60],
          iconAnchor: [30, 30],
          labelAnchor: [6, 0]
      }
  };
    $scope.markers = []
    $http.get('modules/map/selectdata.php').then(function(data, status){
        var jsondata = data.data.features ;
        console.log(jsondata);
        for (var i = 0; i < jsondata.length; i++){
            $scope.markers.push({
                   lat: jsondata[i].geometry.coordinates[1],
                   lng: jsondata[i].geometry.coordinates[0],
                   icon: local_icons.leaf_icon,
                   draggable: false,
                   focus: true,
                   title: jsondata[i].properties.name,
                   label: {
                      //message: jsondata[i].properties.name,
                      message: "<b> "+jsondata[i].properties.name +" </b> ระดับน้ำ : <b> " +jsondata[i].properties.value + "</b> ม.(รทก.)",
                      options: {
                          noHide: true,
                          permanent: true, 
                          direction: 'top',
                          revealing: true,
                          offset: [10, 10]
                      }
                  }
            })
        }
    });

    $scope.$on('leafletDirectiveMarker.mouseover', function(e){
      //console.log(e);
      //$scope.eventDetected = event.name;
    });

    $scope.$on('leafletDirectiveMarker.click', function(e){
      //console.log(e);
      //$scope.eventDetected = event.name;
     });

    //var markerEvents = leafletMarkerEvents.getAvailableEvents();
    //console.log(markerEvents);
    /*for (var k in markerEvents){
        var eventName = 'leafletDirectiveMarker.' + markerEvents[k];
        console.log(eventName);
        $scope.$on(eventName, function(event, args){
            //console.log(event);
            $scope.eventDetected = event.name;
        });
    }*/
}]);