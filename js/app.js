angular.module('galletaFortunaApp', ['ngRoute'])
.config(['$routeProvider', function($routeProvider) {
    $routeProvider
        .when('/login', {
            templateUrl: 'templates/login.html',
            controller: 'AuthController'
        })
        .when('/galleta', {
            templateUrl: 'templates/galleta.html',
            controller: 'FortunaController',
            resolve: {
                auth: ['$location', 'AuthService', function($location, AuthService) {
                    if (!AuthService.estaAutenticado()) {
                        $location.path('/login');
                    }
                }]
            }
        })
        .when('/fortuna', {
            templateUrl: 'templates/fortuna.html',
            controller: 'FortunaController',
            resolve: {
                auth: ['$location', 'AuthService', function($location, AuthService) {
                    if (!AuthService.estaAutenticado()) {
                        $location.path('/login');
                    }
                }]
            }
        })
        .when('/admin/frases', {
            templateUrl: 'templates/admin-frases.html',
            controller: 'AdminController',
            resolve: {
                auth: ['$location', 'AuthService', function($location, AuthService) {
                    if (!AuthService.estaAutenticado() || !AuthService.esAdmin()) {
                        $location.path('/galleta');
                    }
                }]
            }
        })
        .otherwise({
            redirectTo: '/login'
        });
}])
.constant('API_URL', '/api');