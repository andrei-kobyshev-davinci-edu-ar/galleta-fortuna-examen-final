angular.module('galletaFortunaApp')
.factory('HttpInterceptor', ['$injector', function($injector) {
    return {
        request: function(config) {
            // Evitar dependencia circular usando $injector
            var AuthService = $injector.get('AuthService');
            var token = AuthService.obtenerToken();
            if (token) {
                config.headers['Authorization'] = 'Bearer ' + token;
            }
            return config;
        }
    };
}])
.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('HttpInterceptor');
}]);