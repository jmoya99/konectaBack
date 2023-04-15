CREATE DATABASE konecta;
USE konecta

CREATE TABLE IF NOT EXISTS usuario(
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(60) NOT NULL,
	correo_electronico VARCHAR(76) NOT NULL UNIQUE,
	contrasena VARCHAR(100) NOT NULL,
	numero_movil VARCHAR(20),
	tipo_usuario VARCHAR(16) NOT NULL,
	fecha_creacion DATETIME NOT NULL,
	fecha_actualizacion DATETIME NOT NULL,
	CHECK(tipo_usuario="Administrador" OR tipo_usuario="Usuario"),
	CHECK(correo_electronico REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]+$')
);

CREATE TABLE IF NOT EXISTS categoria(
    id INT PRIMARY KEY AUTO_INCREMENT,
	titulo VARCHAR(30) NOT NULL,
	descripcion VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS articulo(
    id_unico INT PRIMARY KEY AUTO_INCREMENT,
	id_categoria INT NOT NULL,
    titulo VARCHAR(30) NOT NULL,
    slug VARCHAR(50),
    texto_corto VARCHAR(100) NOT NULL,
    texto_largo VARCHAR(500) NOT NULL,
    imagen VARCHAR(200) NOT NULL,
    fecha_creacion DATETIME NOT NULL,
    fecha_actualizacion DATETIME NOT NULL,
	FOREIGN KEY (id_categoria) REFERENCES categoria(id)
);

CREATE TABLE IF NOT EXISTS likes(
	id_articulo INT NOT NULL,
	id_usuario BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (id_articulo) REFERENCES articulo(id_unico) ON DELETE CASCADE,
	FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE,
	PRIMARY KEY (id_articulo,id_usuario)
);

CREATE TABLE IF NOT EXISTS comentario(
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_articulo INT NOT NULL,
	id_usuario BIGINT UNSIGNED NOT NULL,
	texto VARCHAR(500) NOT NULL,
	FOREIGN KEY (id_articulo) REFERENCES articulo(id_unico) ON DELETE CASCADE,
	FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE
);