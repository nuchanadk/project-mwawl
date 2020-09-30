'use strict';
 
angular.module('Map', ['ngRoute','leaflet-directive'])
 
/*----------ค่าธรรมเนียม-----------*/
.controller('MapCtrl',
['$scope', '$http','leafletData',
function ($scope,$http, leafletData) {

    //console.log("MapCtrl");
    angular.extend($scope, {
        london: {
          lat: 51.505,
          lng: -0.09,
          zoom: 8
        }
      });
    
      $http.get("modules/map/test.geojson").then(function(data, status){
        console.log(data.data);
        angular.extend($scope, {
            geojson: {
                pointToLayer: function(feature,latlng){
                   var ratIcon = L.icon({
                iconUrl: 'http://andywoodruff.com/maptime-leaflet/rat.png',
                iconSize: [60,50]
              });
                    return L.marker(latlng,{icon: ratIcon});
                  },
              data: addGeoJsonLayerWithClustering(data.data)
              }
            });
      });
    
      function addGeoJsonLayerWithClustering(data) {
          var markers = L.markerClusterGroup();
          var geoJsonLayer = L.geoJson(data, {
              onEachFeature: function (feature, layer) {
                  layer.bindPopup(feature.properties.name);
              }
          });
          markers.addLayer(geoJsonLayer);
          leafletData.getMap().then(function(map) {
            map.addLayer(markers);
            //map.fitBounds(markers.getBounds());
          });
      }

}]);