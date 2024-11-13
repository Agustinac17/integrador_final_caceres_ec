CREATE DATABASE lafournier_bd;
USE lafournier_bd;


/*CREACIÓN DE TABLAS*/
CREATE TABLE novedades (
    id_novedad INT PRIMARY KEY AUTO_INCREMENT,
    encabezado VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from nosotros;

CREATE TABLE imagenes_novedades (
    id_imagen INT PRIMARY KEY AUTO_INCREMENT,
    imagen BLOB NOT NULL,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

delete from nosotros where id_nos=4;

/***********************************************/
CREATE TABLE nosotros (
    id_nos INT PRIMARY KEY AUTO_INCREMENT,
    texto TEXT NOT NULL,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from producto;


CREATE TABLE imagenes_nosotros (
    id_img_nos INT PRIMARY KEY AUTO_INCREMENT,
    imagen BLOB NOT NULL,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/*********HASTA ACA TODO BIEN*********/

/***********************************************/

select * from pedidos;
drop table carrito;

UPDATE producto
SET imagen_path = REPLACE(imagen_path, '../imagenes/productos/', '');


/************************************************/

CREATE TABLE producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    imagen_path VARCHAR(255) NOT NULL,
    id_categoria INT,  -- Clave foránea para vincular con la tabla de categorías
    stock INT NOT NULL DEFAULT 0,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE SET NULL
);

CREATE TABLE categorias (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre_categoria VARCHAR(100) NOT NULL UNIQUE
);


/*********************** crep que todo ok******************************/
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    direccion VARCHAR(255),
    telefono VARCHAR(15),
    email VARCHAR(100),
    pago VARCHAR(50),
    productos TEXT,
    fecha_entrega DATE,
    horario_entrega TIME,
    notas TEXT,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/****************************/

show tables;
drop table recuerdos;

