'use strict';
 
angular.module('Map', ['ngRoute'])
 
/*----------แผนที่-----------*/
.controller('MapCtrl',
['$scope', '$http','$interval',
function ($scope,$http,$interval) {

    //loadData();
    //$interval(loadData, 60000);

    //$scope.$on('leafletDirectiveMarker.mouseover', function(e){
      //console.log(e.targetScope);
      //console.log(e.targetScope.markers);
      //$scope.eventDetected = event.name;
      //e.layer.popupopen();
   // });

    //$scope.$on('leafletDirectiveMarker.click', function(e){
      //console.log(e);
      //$scope.eventDetected = event.name;
     //});

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

      var osm = L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
      
      //var map = new L.Map('map'); 
      var map = new L.map('map', {
        center: {
            lat: 13.9,
            lng: 100
        },
        zoom: 10,
        layers: [osm]
      });

      var markerIcon = 
      L.icon({
        iconUrl: 'vendor/img/home-128.png',
        iconSize: [60, 60]
        //shadowUrl: "assets/marker-shadow.png"
      })

      var markerConnlost = 
      L.icon({
        iconUrl: 'vendor/img/home-connlost.png',
        iconSize: [60, 60]
        //shadowUrl: "assets/marker-shadow.png"
      })

      var oms = new OverlappingMarkerSpiderfier(map, { keepSpiderfied: true });

      //$scope.data = [];
      $http.get('modules/map/selectdata.php').then(function(data, status){
          $scope.data = data.data.features ;

            $scope.data.forEach(function (itemData) {
            //console.log(itemData);

              var popupContent = "";
              //for (var key in itemData.properties) {

              if(itemData.properties.alarmlevel == "LL"){ $scope.alarmcolor = "#996600"; }
              else if(itemData.properties.alarmlevel == "L"){ $scope.alarmcolor = "#e6e600"; }
              else if(itemData.properties.alarmlevel == "HH"){ $scope.alarmcolor = "#FF0000"; }
              else if(itemData.properties.alarmlevel == "H"){ $scope.alarmcolor = "#ff9900"; }
              else{ $scope.alarmcolor = "none";}

              if( itemData.properties.datetime == null )
              {
                  popupContent = "<span style='color:"+ $scope.alarmcolor +"'><b> "+ itemData.properties.name +" </b> <BR>ระดับน้ำ : <b> " + itemData.properties.value + "</b> <BR>วัน-เวลา : <b> " + itemData.properties.datetime + " </b></span>";
              }
              else
              {
                  popupContent = "<span style='color:"+ $scope.alarmcolor +"'><b> "+ itemData.properties.name +" </b> <BR>ระดับน้ำ : <b> " + itemData.properties.value + "</b> ม.(รทก.)<BR>วัน-เวลา : <b> " + itemData.properties.datetime + " น.</b></span>"; 
              }       
              //};

              var latLng = L.latLng(itemData.geometry.coordinates[1],itemData.geometry.coordinates[0]);
              if(itemData.properties.alarmtime == true)
              {
                  var myMarker = L.marker(latLng,{icon: markerConnlost }).bindLabel(popupContent, { noHide: false , direction: 'top' , opacity: 0.8}).addTo(map);
              }
              else
              {
                  var myMarker = L.marker(latLng,{icon: markerIcon }).bindLabel(popupContent, { noHide: true , direction: 'top' , opacity: 0.8}).addTo(map);
              }
              //myMarker.bindPopup(popupContent);
              
              //myMarker.bindLabel(popupContent, { noHide: true , direction: 'top', opacity: 0.8});

              //map.addLayer(myMarker);
              oms.addMarker(myMarker);
          });
      });    
      
      oms.addListener('spiderfy', (markers) => {
        // console.log('spiderfy');
        //console.log(markers);
        //map.closePopup();
        //map.showLabel(markers);
        //markers.forEach(marker => marker.setIcon(createIcon()));
      });
      
      oms.addListener('unspiderfy', (markers) => {
        //console.log('unspiderfy');
        // map.closePopup();
        //markers.forEach(marker => marker.setIcon(createIcon()));
      });
       
      // lengthen the spider's leg by 4x
      oms.circleFootSeparation = 80;
      
      forceUnspiderfy();
      
      function createIcon() {
        return new L.Icon({
          iconUrl: 'vendor/img/home-128.png',
          iconSize: [60, 60]
          // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          //shadowUrl: 'https://googlemaps.github.io/js-marker-clusterer/images/m5.png',
          //shadowUrl: 'https://image.ibb.co/nQrYGc/overlapping_markers.png',
          //shadowUrlRetina: 'https://image.ibb.co/h8jnbc/overlapping_markers_2x.png',
          //shadowAnchor: [20.5, 41],
          //popupAnchor: [1, -34]
        });
      }
      
      function forceUnspiderfy() {
        // workaround for https://github.com/jawj/OverlappingMarkerSpiderfier-Leaflet/issues/32
        oms.getMarkers().forEach(marker => oms.spiderListener(marker));
        oms.unspiderfy();
      }
    
}]);