-- Estructura de base de datos MySQL para Galleta Fortuna

CREATE DATABASE IF NOT EXISTS galleta_fortuna;
USE galleta_fortuna;

-- Tabla de usuarios con rol
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de fortunas con trazabilidad
CREATE TABLE IF NOT EXISTS fortunas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mensaje TEXT NOT NULL,
    agregado_por INT DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agregado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario administrador por defecto
INSERT INTO usuarios (nombre, email, password, rol) 
VALUES ('Administrador', 'admin@galletafortuna.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE nombre=nombre;

-- Fortunas iniciales
INSERT INTO fortunas (mensaje) VALUES 
('El éxito es la suma de pequeños esfuerzos repetidos día tras día.'),
('La paciencia es la clave del éxito.'),
('Tu futuro depende de lo que hagas hoy.'),
('Las oportunidades no pasan, las creas tú.'),
('La perseverancia convierte los sueños en realidad.'),
('Cada día es una nueva oportunidad para cambiar tu vida.'),
('El mejor momento para plantar un árbol fue hace 20 años. El segundo mejor momento es ahora.'),
('No esperes el momento perfecto, toma el momento y hazlo perfecto.'),
('La vida es 10% lo que te sucede y 90% cómo reaccionas ante ello.'),
('El único modo de hacer un gran trabajo es amar lo que haces.'),
('No cuentes los días, haz que los días cuenten.'),
('El cambio es la ley de la vida.'),
('La creatividad es la inteligencia divirtiéndose.'),
('Si puedes soñarlo, puedes hacerlo.'),
('La mejor forma de predecir el futuro es crearlo.'),
('No es la carga la que te rompe, es la forma en que la llevas.'),
('La vida comienza al final de tu zona de confort.'),
('Sé el cambio que quieres ver en el mundo.'),
('Un viaje de mil millas comienza con un solo paso.'),
('La única forma de hacer un trabajo genial es amar lo que haces.')
ON DUPLICATE KEY UPDATE mensaje=mensaje;