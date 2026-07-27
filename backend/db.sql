-- 1. Crear la Base de Datos
CREATE DATABASE IF NOT EXISTS sistema_canchas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_canchas;

-- 2. Tabla de Usuarios (Para identificar quién reserva)
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    correo VARCHAR(100) UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Tabla de Canchas (Para gestionar tus espacios y precios)
CREATE TABLE IF NOT EXISTS canchas (
    id_cancha INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cancha VARCHAR(50) NOT NULL,
    tipo_superficie VARCHAR(50), -- Ej. Sintético, Cemento, Parquet
    precio_hora DECIMAL(10, 2) NOT NULL,
    estado ENUM('disponible', 'mantenimiento') DEFAULT 'disponible'
) ENGINE=InnoDB;

-- 4. Tabla de Reservas (Relaciona usuarios y canchas)
CREATE TABLE IF NOT EXISTS reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_cancha INT NOT NULL,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    estado_pago ENUM('pendiente', 'pagado', 'señado') DEFAULT 'pendiente',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Restricción: No permite duplicar la misma cancha a la misma fecha y hora
    UNIQUE KEY cancha_horario_unico (id_cancha, fecha, hora_inicio),
    
    -- Relaciones (Claves Foráneas)
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_cancha) REFERENCES canchas(id_cancha) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ========================================================
-- INSERCIÓN DE DATOS DE PRUEBA (Opcional)
-- ========================================================

-- Insertar canchas iniciales
INSERT INTO canchas (nombre_cancha, tipo_superficie, precio_hora) VALUES 
('Cancha Fútbol 5 A', 'Pasto Sintético', 30.00),
('Cancha Fútbol 5 B', 'Pasto Sintético', 30.00),
('Cancha Fútbol 7', 'Pasto Sintético', 45.00),
('Cancha de Pádel 1', 'Cristal/Sintético', 20.00),
('Cancha de Tenis', 'Polvo de Ladrillo', 25.00);

-- Insertar un usuario de prueba
INSERT INTO usuarios (nombre, telefono, correo) VALUES 
('Carlos Mendoza', '099123456', 'carlos@email.com');

-- Insertar una reserva de prueba (Carlos reserva Fútbol 5 A el 15 de Octubre a las 19:00)
INSERT INTO reservas (id_usuario, id_cancha, fecha, hora_inicio, estado_pago) VALUES 
(1, 1, '2026-10-15', '19:00:00', 'pendiente');
