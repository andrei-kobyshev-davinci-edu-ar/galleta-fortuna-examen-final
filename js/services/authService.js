angular.module('galletaFortunaApp')
.service('AuthService', ['$http', 'API_URL', function($http, API_URL) {
    var servicio = this;
    
    servicio.login = function(email, password) {
        return $http.post(API_URL + '/login', {
            email: email,
            password: password
        }).then(function(response) {
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('usuario', JSON.stringify(response.data.usuario));
            return response;
        });
    };
    
    servicio.registro = function(nombre, email, password) {
        return $http.post(API_URL + '/register', {
            nombre: nombre,
            email: email,
            password: password
        });
    };
    
    servicio.estaAutenticado = function() {
        return localStorage.getItem('token') !== null;
    };
    
    servicio.obtenerToken = function() {
        return localStorage.getItem('token');
    };
    
    servicio.cerrarSesion = function() {
        localStorage.removeItem('token');
        localStorage.removeItem('usuario');
    };
    
    servicio.obtenerUsuario = function() {
        var usuario = localStorage.getItem('usuario');
        return usuario ? JSON.parse(usuario) : null;
    };
    
    servicio.esAdmin = function() {
        var usuario = servicio.obtenerUsuario();
        return usuario && usuario.rol === 'admin';
    };
}]);