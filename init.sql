CREATE DATABASE IF NOT EXISTS sesiones_db;

USE sesiones_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL
);

INSERT INTO usuarios (nombre, correo, password, rol) VALUES
(
    'Ian',
    'ian@test.com',
    '$2y$10$5M7sQ9Sx9KxS1W5F0J6T5uT7j6dP7I7Jm6j3iKz7c8Y1Jq8v2bQxS',
    'Administrador'
),
(
    'Maria',
    'maria@test.com',
    '$2y$10$5M7sQ9Sx9KxS1W5F0J6T5uT7j6dP7I7Jm6j3iKz7c8Y1Jq8v2bQxS',
    'Editor'
),
(
    'Carlos',
    'carlos@test.com',
    '$2y$10$5M7sQ9Sx9KxS1W5F0J6T5uT7j6dP7I7Jm6j3iKz7c8Y1Jq8v2bQxS',
    'Usuario'
);