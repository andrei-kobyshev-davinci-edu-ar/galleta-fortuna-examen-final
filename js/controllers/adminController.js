angular.module('galletaFortunaApp')
.controller('AdminController', ['$scope', '$location', '$timeout', 'AdminService', 'AuthService',
function($scope, $location, $timeout, AdminService, AuthService) {
    $scope.frases = [];
    $scope.nuevaFrase = '';
    $scope.mensaje = '';
    $scope.error = '';
    $scope.cargando = false;
    
    // Verificar que sea admin
    $scope.$on('$routeChangeSuccess', function() {
        const usuario = AuthService.obtenerUsuario();
        if (!usuario || usuario.rol !== 'admin') {
            $location.path('/galleta');
        } else {
            cargarFrases();
        }
    });
    
    function cargarFrases() {
        $scope.cargando = true;
        AdminService.listarFrases()
            .then(function(response) {
                $scope.frases = response.data.frases;
                $scope.cargando = false;
            })
            .catch(function(error) {
                $scope.error = 'Error al cargar las frases';
                $scope.cargando = false;
            });
    }
    
    $scope.agregarFrase = function() {
        if (!$scope.nuevaFrase.trim()) {
            $scope.error = 'La frase no puede estar vacía';
            return;
        }
        
        $scope.mensaje = '';
        $scope.error = '';
        
        AdminService.agregarFrase($scope.nuevaFrase)
            .then(function(response) {
                $scope.mensaje = 'Frase agregada exitosamente';
                $scope.nuevaFrase = '';
                cargarFrases();
            })
            .catch(function(error) {
                if (error.status === 409) {
                    $scope.error = 'Esta frase ya existe en la base de datos';
                } else {
                    $scope.error = error.data.error || 'Error al agregar la frase';
                }
            });
    };
    
    $scope.marcarYEliminar = function(frase) {
        if (!confirm('¿Estás seguro de eliminar esta frase?')) {
            return;
        }
        
        $scope.mensaje = '';
        $scope.error = '';
        frase.eliminando = true;
        
        AdminService.eliminarFrase(frase.id)
            .then(function(response) {
                $scope.mensaje = 'Frase eliminada exitosamente';
                // Limpiar el mensaje después de 3 segundos
                $timeout(function() {
                    $scope.mensaje = '';
                }, 3000);
                cargarFrases();
            })
            .catch(function(error) {
                $scope.error = 'Error al eliminar la frase';
                frase.eliminando = false;
            });
    };
    
    $scope.volver = function() {
        $location.path('/galleta');
    };
    
    $scope.cerrarSesion = function() {
        AuthService.cerrarSesion();
        $location.path('/login');
    };
}]);