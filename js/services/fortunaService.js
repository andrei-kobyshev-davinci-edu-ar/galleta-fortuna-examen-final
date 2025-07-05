angular.module('galletaFortunaApp')
.service('FortunaService', ['$http', 'API_URL', function($http, API_URL) {
    var servicio = this;
    
    servicio.obtenerFortuna = function() {
        return $http.get(API_URL + '/fortuna');
    };
}]);