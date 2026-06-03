-- Usar la base de datos
USE sistema_inventario;

-- Tabla de usuarios
CREATE TABLE usuarios (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre_completo VARCHAR(100) NOT NULL,
 usuario VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 rol VARCHAR(20) NOT NULL
);

-- Tabla de categorías
CREATE TABLE categorias (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre_categoria VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de productos relacionada
CREATE TABLE productos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre_producto VARCHAR(100) NOT NULL,
 categoria_id INT NOT NULL,
 stock INT NOT NULL,
 precio DECIMAL(10,2) NOT NULL,
 FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Insertar categorías
INSERT INTO categorias (nombre_categoria) VALUES
('Computadoras'),
('Accesorios'),
('Oficina');

-- Insertar productos
INSERT INTO productos (nombre_producto, categoria_id, stock, precio) VALUES
('Laptop Dell Inspiron 15', 1, 15, 720.00),
('Mouse Inalámbrico Logitech', 2, 25, 12.00);

-- REPORTES RELACIONALES AVANZADOS (Guía 11)

-- 1. Vista completa del inventario con categorías legibles para administración:

SELECT 
    p.id,
    p.nombre_producto,
    c.nombre_categoria,
    p.stock,
    p.precio
FROM productos p
INNER JOIN categorias c 
ON p.categoria_id = c.id;

-- 2. Vista filtrada exclusivamente para el departamento de 'Accesorios':

SELECT 
    p.id,
    p.nombre_producto,
    c.nombre_categoria,
    p.stock,
    p.precio
FROM productos p
INNER JOIN categorias c 
ON p.categoria_id = c.id
WHERE c.nombre_categoria = 'Accesorios';

-- ====================================================================
-- CONSULTAS DE ESTADÍSTICAS Y MÉTRICAS PARA EL DASHBOARD (Guía 12)
-- ====================================================================

-- Tarjeta 1: Total de artículos distintos en el catálogo
SELECT COUNT(id) AS total_productos_catalogo
FROM productos;

-- Tarjeta 2: Valor económico total del inventario
SELECT SUM(precio * stock) AS valor_monetario_inventario
FROM productos;

-- Tarjeta 3: Precio del producto estrella o de mayor gama del inventario
SELECT MAX(precio) AS producto_mas_caro
FROM productos;

-- Tarjeta 4: Reporte de unidades físicas totales en existencia agrupadas por categoría
SELECT c.nombre_categoria, SUM(p.stock) AS existencias_totales
FROM productos p
INNER JOIN categorias c ON p.categoria_id = c.id
GROUP BY c.nombre_categoria;