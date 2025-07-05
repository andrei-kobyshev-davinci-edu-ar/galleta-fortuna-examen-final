angular.module('galletaFortunaApp')
.service('AdminService', ['$http', 'API_URL', function($http, API_URL) {
    var servicio = this;
    
    servicio.listarFrases = function() {
        return $http.get(API_URL + '/admin/frases');
    };
    
    servicio.agregarFrase = function(mensaje) {
        return $http.post(API_URL + '/admin/frases', { mensaje: mensaje });
    };
    
    servicio.eliminarFrase = function(id) {
        return $http.delete(API_URL + '/admin/frases/' + id);
    };
}]);