angular.module('galletaFortunaApp')
.controller('FortunaController', ['$scope', '$location', 'FortunaService', 'AuthService', 'SharedService',
function($scope, $location, FortunaService, AuthService, SharedService) {
    // Inicializar fortunaMensaje cuando se carga el controlador
    $scope.$on('$routeChangeSuccess', function() {
        $scope.fortunaMensaje = SharedService.getFortuna();
    });
    
    $scope.fortunaMensaje = SharedService.getFortuna();
    $scope.mostrandoFortuna = false;
    $scope.esAdmin = AuthService.esAdmin();
    
    $scope.abrirGalleta = function() {
        FortunaService.obtenerFortuna()
            .then(function(response) {
                SharedService.setFortuna(response.data.fortuna);
                $location.path('/fortuna');
            })
            .catch(function(error) {
                alert('Error al obtener tu fortuna');
            });
    };
    
    $scope.nuevaGalleta = function() {
        $location.path('/galleta');
    };
    
    $scope.cerrarSesion = function() {
        AuthService.cerrarSesion();
        $location.path('/login');
    };
    
    $scope.irAdminFrases = function() {
        $location.path('/admin/frases');
    };
}]);