# Galleta China de la Fortuna 🥠

Aplicación web de galletas de la fortuna desarrollada con PHP (backend) y AngularJS (frontend).

## Requisitos

- XAMPP (o cualquier servidor web con PHP 7.3+ y MySQL)
- Navegador web moderno
- PHP con PDO habilitado para MySQL

## Instalación en XAMPP (Windows)

### 1. Preparar el entorno

1. Instalar XAMPP si aún no está instalado
2. Iniciar los servicios de **Apache** y **MySQL** desde el panel de control de XAMPP

### 2. Copiar los archivos

1. Copiar toda la carpeta `galleta-fortuna` a `C:\xampp\htdocs\`
2. La estructura debe quedar: `C:\xampp\htdocs\galleta-fortuna\`

### 3. Configurar la base de datos

1. Abrir phpMyAdmin: http://localhost/phpmyadmin
2. Crear una nueva base de datos llamada `galleta_fortuna`
3. Seleccionar la base de datos `galleta_fortuna`
4. Importar el archivo `backend/database/schema.sql`

### 4. Verificar la configuración de Apache

Asegurarse de que el módulo `mod_rewrite` esté habilitado en Apache:

1. Abrir el archivo `C:\xampp\apache\conf\httpd.conf`
2. Buscar la línea: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Quitar el `#` del inicio si está comentada
4. Reiniciar Apache desde el panel de control de XAMPP

### 5. Acceder a la aplicación

Abrir el navegador y visitar: http://localhost/galleta-fortuna

## Uso de la aplicación

### Usuario Regular
1. **Registro**: En la pantalla inicial, hacer clic en "Regístrate aquí" para crear una cuenta
2. **Login**: Ingresar con el email y contraseña registrados
3. **Abrir galleta**: Hacer clic en el botón "ABRE TU GALLETA"
4. **Ver fortuna**: Se mostrará un mensaje aleatorio de sabiduría china
5. **Nueva galleta**: Hacer clic en "Abrir Otra Galleta" para obtener un nuevo mensaje

### Administrador
1. **Login como admin**: Usar las credenciales del usuario administrador (ver abajo)
2. **Gestión de frases**: Hacer clic en "Gestión de Frases" en la pantalla principal
3. **Agregar frases**: Escribir nuevas frases y hacer clic en "Agregar Frase"
4. **Eliminar frases**: Hacer clic en "Eliminar" junto a la frase que deseas borrar

## Estructura del proyecto

```
galleta-fortuna/
├── backend/                 # API en PHP
│   ├── app/
│   │   ├── controllers/    # Controladores (Auth, Fortuna)
│   │   └── Models/         # Modelos (Usuario, Fortuna)
│   ├── config/             # Configuración de BD
│   ├── database/           # Script SQL inicial
│   └── public/             # Punto de entrada API
├── css/                    # Estilos
├── img/                    # Imágenes (SVG de galletas)
├── js/                     # JavaScript/AngularJS
│   ├── controllers/        # Controladores Angular
│   └── services/           # Servicios Angular
├── templates/              # Vistas HTML de Angular
├── index.html              # Página principal
└── .htaccess              # Configuración de rutas
```

## Características técnicas

- **Backend**: PHP puro con arquitectura MVC simple
- **Frontend**: AngularJS 1.8.2 con enrutamiento
- **Base de datos**: MySQL con PDO
- **Autenticación**: Sistema de tokens simple
- **API REST**: Endpoints para registro, login, obtener fortunas y gestión de frases
- **Roles**: Sistema de roles (usuario/admin)
- **Excepciones**: Manejo personalizado de excepciones para frases duplicadas
- **Diseño**: Responsive, compatible con dispositivos móviles

## Nota sobre el desarrollo

Este proyecto fue desarrollado usando DDEV en macOS. La configuración está optimizada para funcionar tanto en DDEV como en XAMPP Windows sin cambios.

## Endpoints de la API

### Autenticación
- `POST /api/register` - Registro de usuarios
- `POST /api/login` - Inicio de sesión

### Fortunas
- `GET /api/fortuna` - Obtener mensaje aleatorio (requiere autenticación)

### Admin
- `GET /api/admin/frases` - Listar todas las frases (requiere rol admin)
- `POST /api/admin/frases` - Agregar nueva frase (requiere rol admin)
- `DELETE /api/admin/frases/{id}` - Eliminar frase (requiere rol admin)

## Solución de problemas

### Error 404 en las rutas
- Verificar que `mod_rewrite` esté habilitado en Apache
- Verificar que el archivo `.htaccess` esté presente en la raíz del proyecto

### Error de conexión a la base de datos
- Verificar que MySQL esté ejecutándose en XAMPP
- Verificar que la base de datos `galleta_fortuna` exista
- Verificar las credenciales en `backend/config/database.php`
- Por defecto usa root sin contraseña (configuración estándar de XAMPP)

### La fortuna no se muestra
- Abrir la consola del navegador (F12) y verificar errores
- Verificar que las tablas tengan datos ejecutando el script SQL

## Credenciales de prueba

Después de ejecutar el script SQL inicial (`backend/database/schema.sql`), se crea automáticamente:

### Usuario Administrador
- Email: admin@galletafortuna.com
- Contraseña: admin123

### Usuario Regular
- Puedes registrar nuevos usuarios desde la pantalla de login

## Estructura de Base de Datos

### Tabla `usuarios`
- `id`: INT AUTO_INCREMENT PRIMARY KEY
- `nombre`: VARCHAR(100) NOT NULL
- `email`: VARCHAR(100) UNIQUE NOT NULL
- `password`: VARCHAR(255) NOT NULL (hash bcrypt)
- `rol`: ENUM('usuario', 'admin') DEFAULT 'usuario'
- `fecha_registro`: TIMESTAMP DEFAULT CURRENT_TIMESTAMP

### Tabla `fortunas`
- `id`: INT AUTO_INCREMENT PRIMARY KEY
- `mensaje`: TEXT NOT NULL
- `agregado_por`: INT DEFAULT NULL (FK a usuarios.id)
- `fecha_creacion`: TIMESTAMP DEFAULT CURRENT_TIMESTAMP

## Tests Automatizados

El proyecto incluye tests automatizados con Playwright:

```bash
cd test
npm install
npm test
```

Los tests verifican:
- Registro y login de usuarios
- Roles y permisos
- Gestión de frases (admin)
- Validación de duplicados
- Eliminación de frases

## Mejoras respecto al Parcial 2

- Migración de SQLite a MySQL con PDO
- Implementación completa del registro de usuarios
- Sistema de roles (usuario/admin)
- Vista de administración para gestión de frases
- Validación de frases duplicadas con excepciones personalizadas
- Manejo centralizado de tokens en el frontend
- Tests automatizados con Playwright
- Feedback visual mejorado en todas las operaciones

## Autor

Desarrollado por Andrei Kobyshev para la materia de Programación Web 3 - Instituto DaVinci