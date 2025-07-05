angular.module('galletaFortunaApp')
.controller('AuthController', ['$scope', '$location', 'AuthService', 
function($scope, $location, AuthService) {
    $scope.usuario = {};
    $scope.modoRegistro = false;
    $scope.mensaje = '';
    $scope.error = '';
    
    $scope.cambiarModo = function() {
        $scope.modoRegistro = !$scope.modoRegistro;
        $scope.mensaje = '';
        $scope.error = '';
        $scope.usuario = {};
    };
    
    $scope.login = function() {
        AuthService.login($scope.usuario.email, $scope.usuario.password)
            .then(function(response) {
                $location.path('/galleta');
            })
            .catch(function(error) {
                $scope.error = error.data.error || 'Error al iniciar sesión';
            });
    };
    
    $scope.registro = function() {
        AuthService.registro($scope.usuario.nombre, $scope.usuario.email, $scope.usuario.password)
            .then(function(response) {
                $scope.mensaje = 'Registro exitoso. Por favor inicia sesión.';
                $scope.modoRegistro = false;
                $scope.usuario = {};
            })
            .catch(function(error) {
                $scope.error = error.data.error || 'Error al registrar usuario';
            });
    };
}]);