# Tests para Galleta Fortuna

## Instalación

### Opción 1: Playwright Global (Recomendado)
```bash
npm install -g playwright
```

### Opción 2: Instalación Local
```bash
cd test
npm install
```

## Ejecutar Tests

### Con navegador visible:
```bash
node test-completo.js
```

### Modo headless (sin navegador visible):
```bash
HEADLESS=true node test-completo.js
```

### Con npm (si instalaste localmente):
```bash
npm test
npm run test:headless
```

## Screenshots

Los screenshots se guardan en la carpeta `screenshots/`:
- 1-registro.png - Formulario de registro
- 2-login.png - Formulario de login
- 3-galleta-principal.png - Página principal con galleta
- 4-fortuna.png - Primera fortuna
- 5-nueva-fortuna.png - Segunda fortuna
- 6-logout.png - Vuelta al login después de cerrar sesión

## Requisitos

- DDEV ejecutándose con el proyecto
- O la aplicación corriendo en http://galleta-fortuna.ddev.site