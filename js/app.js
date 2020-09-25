'use strict';

angular.module('Myapp', [
    'ngRoute',
    'ngCookies',
    'ui.bootstrap',
    'Home',
    'Setting',
    'Chart',
    'Map',
    'Reportgauge',
    'Table',
    'Tablelive'
    //'angularjs-dropdown-multiselect'
])

.config(['$locationProvider', '$routeProvider',function($locationProvider, $routeProvider) {
	$locationProvider.hashPrefix('');
    $routeProvider
    /*.when('/login', {
        templateUrl : 'modules/authentication/views/login.html',
        controller : 'LoginController'
    })*/
    .when('/tablelive', {
        templateUrl : 'modules/tablelive/views/Main.html',
        controller :  'LevelCtrl'
    })
    .when('/map', {
        templateUrl : 'modules/map/views/Main.html',
        controller :  'LevelCtrl'
    })
    .when('/table', {
        templateUrl : 'modules/table/views/Main.html',
        controller :  'LevelCtrl'
    })
    .when('/chart', {
        templateUrl : 'modules/chart/views/Main.html',
        controller :  'LevelCtrl'
    })
    .when('/reportgauge', {
        templateUrl : 'modules/reportgauge/views/Main.html',
        controller :  'LevelCtrl'
    })
    .when('/', {
        templateUrl : 'modules/home/views/Home.html',
        controller :  'HomeCtrl'
    })
    .when('/level', {
        templateUrl : 'modules/setting/views/Mainlevel.html',
        controller :  'LevelCtrl'
    })
    .when('/leveladd', {
        templateUrl : 'modules/setting/views/leveladd.html',
        controller :  'LevelCtrl'
    })
    .when('/leveledit', {
        templateUrl : 'modules/setting/views/leveledit.html',
        controller :  'LevelCtrl'
    })
    .when('/leveldelete', {
        templateUrl : 'modules/setting/views/Mainlevel.html',
        controller :  'LevelCtrl'
    })
    .when('/user', {
        templateUrl : 'modules/setting/views/Mainuser.html',
        controller :  'UserCtrl'
    })
    .when('/useradd', {
        templateUrl : 'modules/setting/views/useradd.html',
        controller :  'UserCtrl'
    })
    .when('/useredit', {
        templateUrl : 'modules/setting/views/useredit.html',
        controller :  'UserCtrl'
    })
    .when('/userdelete', {
        templateUrl : 'modules/setting/views/Mainuser.html',
        controller :  'UserCtrl'
    })
    .when('/alarm', {
        templateUrl : 'modules/setting/views/Mainalarm.html',
        controller :  'AlarmCtrl'
    })
    .when('/alarmadd', {
        templateUrl : 'modules/setting/views/alarmadd.html',
        controller :  'AlarmCtrl'
    })
    .when('/alarmedit', {
        templateUrl : 'modules/setting/views/alarmedit.html',
        controller :  'AlarmCtrl'
    })
    .when('/alarmdelete', {
        templateUrl : 'modules/setting/views/Mainalarm.html',
        controller :  'AlarmCtrl'
    })
    .when('/token', {
        templateUrl : 'modules/setting/views/Maintoken.html',
        controller :  'TokenCtrl'
    })
    .when('/tokenadd', {
        templateUrl : 'modules/setting/views/tokenadd.html',
        controller :  'TokenCtrl'
    })
    .when('/tokenedit', {
        templateUrl : 'modules/setting/views/tokenedit.html',
        controller :  'TokenCtrl'
    })
    .when('/tokendelete', {
        templateUrl : 'modules/setting/views/Maintoken.html',
        controller :  'TokenCtrl'
    });
    $routeProvider.otherwise('/');
    /*
    .when('/user', {
        templateUrl : 'modules/users/views/MainUser.html',
        controller : 'AdminController'
    })
    .when('/adduser', {
        templateUrl : 'modules/users/views/addUser.html',
        controller : 'AdminController'
    })*/
}]);
/*.run(['$rootScope', '$location', '$cookieStore', '$http',
    function ($rootScope, $location, $cookieStore, $http) {
		$rootScope.location = $location;
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }
        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in
            if ($location.path() !== '/' && !$rootScope.globals.currentUser) {
                $location.path('/');
            }
        });
    }
]);*/