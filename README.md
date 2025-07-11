# Basic PHP CRUD & AJAX - Simple ORM
#### Jhustyn Carvajal - 2025

## Ejecución 
- Mediante el uso de Docker y Docker Compose vamos a levantar un contenedor con 3 imagenes

1. php-apache
2. mysql
3. phpmyadmin

### Levantar el contenedor 
```bash
docker compose up -d
```
### Verficar que existe el .env y tenga las variables necesario
```
DB_HOST=db
DB_USER=root
DB_PASS=rootpass
DB_NAME=appdb
DB_PORT=3306
```

### Antes de ingresar al proyecto crear las tablas correspondiente en la Bade de Datos

- Se especifican más abajo, puede usar los datos de prueba.

### Revisar que todo este bien 

- project: http://localhost:8080
- phpadmin: http://localhost:8081

## DB MODEL

```SQL
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255) NOT NULL
);
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL
);
CREATE TABLE usuario_roles (
  usuario_id INT,
  rol_id INT,
  PRIMARY KEY (usuario_id, rol_id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE
);
```
## Fill data

```SQL
INSERT INTO roles (nombre, descripcion) VALUES
  ('Administrador', 'Acceso completo al sistema'),
  ('Editor', 'Puede editar contenido'),
  ('Lector', 'Solo lectura');

INSERT INTO usuarios (nombre, email, contrasena) VALUES
  ('María López', 'maria.lopez@example.com', 'contrasena123'),
  ('Carlos Pérez', 'carlos.perez@example.com', 'contrasena456');

-- Asignar el rol de 'Administrador' a María López
INSERT INTO usuario_roles (usuario_id, rol_id)
SELECT u.id, r.id
FROM usuarios u, roles r
WHERE u.nombre = 'María López' AND r.nombre = 'Administrador';

-- Asignar el rol de 'Editor' a Carlos Pérez
INSERT INTO usuario_roles (usuario_id, rol_id)
SELECT u.id, r.id
FROM usuarios u, roles r
WHERE u.nombre = 'Carlos Pérez' AND r.nombre = 'Editor';
```
## SQL QUERIES

- Para obtener los roles asignados a un usuario específico, puedes ejecutar la siguiente consulta:

```SQL
SELECT u.nombre AS usuario, r.nombre AS rol
FROM usuarios u
JOIN usuario_roles ur ON u.id = ur.usuario_id
JOIN roles r ON ur.rol_id = r.id
WHERE u.nombre = 'María López';
```

- Insertar roles


```SQL
INSERT INTO roles (nombre, descripcion) VALUES 
('Administrador', 'Acceso total al sistema'),
('Usuario', 'Acceso básico al sistema'),
('Editor', 'Puede editar contenido');
```

```SQL
-- Eliminar por ID
DELETE FROM roles WHERE id = 4;

-- Eliminar por nombre
DELETE FROM roles WHERE nombre = 'Moderador';

-- Eliminar múltiples roles
DELETE FROM roles WHERE id IN (4, 5);
```

- Si se tiene usuarios con esos roles asignados, primero :

```SQL
-- Ver qué usuarios tienen el rol que quieres eliminar
SELECT u.nombre, u.email, r.nombre as rol 
FROM usuarios u 
JOIN usuario_roles ur ON u.id = ur.usuario_id 
JOIN roles r ON ur.rol_id = r.id 
WHERE r.id = 4;

-- Eliminar las asignaciones primero
DELETE FROM usuario_roles WHERE rol_id = 4;

-- Luego eliminar el rol
DELETE FROM roles WHERE id = 4;
```
## Eliminación automática (ya configurada)
Tabla usuario_roles tiene ON DELETE CASCADE, cuando elimines un rol se eliminan automáticamente todas sus asignaciones:

```SQL
-- Esto elimina el rol Y todas sus asignaciones automáticamente
DELETE FROM roles WHERE id = 4;
```

## Instalar modulo PDO en Wordpress mediante Dockerfile

```Dockerfile
# Dockerfile.wordpress
FROM wordpress:latest

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql

# Instalar Composer si lo necesitas
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilitar módulo rewrite de Apache
RUN a2enmod rewrite
```


### Asi queda el servicio wordpress en el archivo docker-compose
```docker-compose.yml
  wordpress:
    build:
      context: .
      dockerfile: Dockerfile.wordpress
    container_name: wordpress
    restart: always
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: appdb
      WORDPRESS_DB_USER: appuser
      WORDPRESS_DB_PASSWORD: apppass
    ports:
      - "8082:80"
    volumes:
      - ./wordpress:/var/www/html
    depends_on:
      - db
    networks:
      - app-network
```

### Adicionalmente una forma de instalar el modulo PDO en wordpress es mediante el uso de exec 

```bash
docker exec -it wordpress bash

apt update && apt install -y libzip-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev unzip
docker-php-ext-install pdo pdo_mysql

exit
docker restart wordpress
```