DROP DATABASE IF EXISTS db_restaurante_v2;
CREATE DATABASE db_restaurante_v2;

USE db_restaurante_v2;

-- Tabla de roles
CREATE TABLE tbl_roles (
    id_rol INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_rol VARCHAR(20) NOT NULL
);

-- Tabla de usuarios
CREATE TABLE tbl_usuarios (
    id_usuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(30) NOT NULL,
    apellido_usuario VARCHAR(30) NOT NULL,
    username VARCHAR(30) NOT NULL,
    password CHAR(64) NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES tbl_roles(id_rol)
);

-- Tabla de salas
CREATE TABLE tbl_salas (
    id_sala INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_sala VARCHAR(15) NOT NULL,
    tipo_sala ENUM("Comedor", "Terraza", "VIP") NOT NULL
);

-- Tabla de recursos
CREATE TABLE tbl_recursos (
    id_recurso INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_recurso VARCHAR(40) NOT NULL UNIQUE,
    tipo_recurso ENUM("Mesa", "Silla") NOT NULL,
    id_padre INT NULL, 
    id_sala INT NULL,
    FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_sala),
    FOREIGN KEY (id_padre) REFERENCES tbl_recursos(id_recurso)
);


-- Tabla de historial
CREATE TABLE tbl_historial (
    id_historial INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha_asignacion DATETIME NOT NULL,
    fecha_no_asignacion DATETIME NULL,
    asignado_por INT NOT NULL,
    asignado_a VARCHAR(30) NOT NULL,
    id_recurso INT NOT NULL,
    FOREIGN KEY (id_recurso) REFERENCES tbl_recursos(id_recurso),
    FOREIGN KEY (asignado_por) REFERENCES tbl_usuarios(id_usuario)
);

INSERT INTO tbl_roles (nombre_rol)
VALUES
('Administrador'),
('Camarero'),
('Recepcionista');

INSERT INTO tbl_usuarios (nombre_usuario, apellido_usuario, username, password, id_rol)
VALUES
('Carlos', 'García', 'cgarcia', SHA2('Camarero123.', 256), 2),
('Laura', 'Martínez', 'lmartinez', SHA2('Recepcionista123.', 256), 3),
('Ana', 'Sánchez', 'asanchez', SHA2('Camarero123.', 256), 2),
('Jorge', 'Hernández', 'jhernandez', SHA2('Admin123.', 256), 1),
('Elena', 'López', 'elopez', SHA2('Camarero123.', 256), 2);

INSERT INTO tbl_salas (nombre_sala, tipo_sala)
VALUES
('Comedor_1', 'Comedor'),
('Terraza_1', 'Terraza'),
('Salon_VIP', 'VIP'),
('Comedor_2', 'Comedor'),
('Jardin', 'Terraza'),
('Terraza_2', 'Terraza'),
('Salon_VIP_2', 'VIP'),
('Salon_Romantico', 'VIP'),
('Naturaleza', 'VIP');

INSERT INTO tbl_recursos (nombre_recurso, tipo_recurso, id_padre, id_sala)
VALUES
-- Mesas en Comedor_1 (Sala 1)
('Mesa1', 'Mesa', NULL, 1),
('Mesa2', 'Mesa', NULL, 1),
('Mesa3', 'Mesa', NULL, 1),

-- Mesas en Terraza_1 (Sala 2)
('Mesa4', 'Mesa', NULL, 2),
('Mesa5', 'Mesa', NULL, 2),


-- Mesas en Salon_VIP (Sala 3)
('Mesa6', 'Mesa', NULL, 3),
('Mesa7', 'Mesa', NULL, 3),


-- Mesas en Naturaleza (Sala 9)
('Mesa8', 'Mesa', NULL, 9),
('Mesa9', 'Mesa', NULL, 9),


-- Mesas en Jardin (Sala 5)
('Mesa10', 'Mesa', NULL, 5),
('Mesa11', 'Mesa', NULL, 5),

-- Mesas en Salon_Romantico (Sala 8)
('Mesa12', 'Mesa', NULL, 8),

-- Sillas en Comedor_1 (Sala 1)
('Silla1', 'Silla', 1, 1),
('Silla2', 'Silla', 1, 1),
('Silla3', 'Silla', 2, 1),
('Silla4', 'Silla', 2, 1),
('Silla5', 'Silla', 3, 1),
('Silla6', 'Silla', 3, 1),

-- Sillas en Terraza_1 (Sala 2)
('Silla_Comoda1', 'Silla', 4, 2),
('Silla_Comoda2', 'Silla', 4, 2),
('Silla_Comoda3', 'Silla', 5, 2),
('Silla_Comoda4', 'Silla', 5, 2),

-- Sillas en Salon_VIP (Sala 3)
('SillaVIP1', 'Silla', 6, 3),
('SillaVIP2', 'Silla', 6, 3),
('SillaVIP3', 'Silla', 7, 3),
('SillaVIP4', 'Silla', 7, 3),

-- Sillas en Naturaleza (Sala 9)
('SillaVIP5', 'Silla', 8, 9),
('SillaVIP6', 'Silla', 8, 9),
('SillaVIP7', 'Silla', 9, 9),
('SillaVIP8', 'Silla', 9, 9),

-- Sillas en Jardin (Sala 5)
('Silla_Comoda5', 'Silla', 10, 5),
('Silla_Comoda6', 'Silla', 10, 5),
('Silla_Comoda7', 'Silla', 11, 5),
('Silla_Comoda8', 'Silla', 11, 5),

-- Sillas en Salon_Romantico (Sala 8)
('SillaVIP9', 'Silla', 12, 8),
('SillaVIP10', 'Silla', 12, 8);

INSERT INTO tbl_historial (fecha_asignacion, fecha_no_asignacion, asignado_por, asignado_a, id_recurso)
VALUES
(NOW(), NULL, 1, 'Hugo González', 1),
(NOW(), NULL, 3, 'Alex Muñoz', 2),
(NOW(), NULL, 2, 'Erik Pérez', 5),
(NOW(), NULL, 4, 'Ming Zhao', 8),
(NOW(), NOW(), 3, 'Dylan Smith', 11);