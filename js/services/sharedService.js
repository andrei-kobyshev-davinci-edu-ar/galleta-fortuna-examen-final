angular.module('galletaFortunaApp')
.service('SharedService', function() {
    var servicio = this;
    servicio.fortunaMensaje = '';
    
    servicio.setFortuna = function(mensaje) {
        servicio.fortunaMensaje = mensaje;
    };
    
    servicio.getFortuna = function() {
        return servicio.fortunaMensaje;
    };
});