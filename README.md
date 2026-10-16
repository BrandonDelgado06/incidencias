# Sistema de Incidencias Tecnológicas — Laboratorios de Computación

Aplicación web en **PHP + MySQL (mysqli)** para registrar y dar seguimiento a las fallas tecnológicas de los laboratorios de una universidad.

Proyecto de examen — UISIL | Facultad de Ingeniería de Sistemas.

## Funcionalidades
- Registro de incidencias (reportante, laboratorio, equipo, descripción y prioridad).
- Listado en tabla con la fecha de registro.
- Filtros por laboratorio, estado y prioridad.
- Edición, cambio de estado (Pendiente / En proceso / Resuelta) y eliminación con confirmación (solo administrador).
- Inicio de sesión para administradores, con contraseñas encriptadas (`password_hash`).
- Validación de campos obligatorios y consultas preparadas.

## Tecnologías
PHP 8, MySQL/MariaDB (mysqli), HTML, CSS y Bootstrap 5.

## Requisitos
- XAMPP (Apache + MySQL + PHP 8.x)
- Navegador web con conexión a Internet (Bootstrap se carga por CDN)

## Instalación y ejecución
1. Copia la carpeta del proyecto en `C:\xampp\htdocs\incidencias`.
2. Abre el **XAMPP Control Panel** y pulsa **Start** en **Apache** y **MySQL**.
3. Entra a `http://localhost/phpmyadmin`, abre la pestaña **Importar**, selecciona `sql/database.sql` y pulsa **Importar**. Esto crea la base `incidencias_db` con sus tablas y el usuario administrador.
4. Verifica las credenciales de conexión en `config/conexion.php` (por defecto: host `localhost`, usuario `root`, sin contraseña, base `incidencias_db`).
5. Abre `http://localhost/incidencias/`.

## Acceso de administrador
- Usuario: `admin`
- Contraseña: `admin123`

Solo los usuarios autenticados pueden editar, eliminar o cambiar el estado de las incidencias.

## Estructura del proyecto
```
incidencias/
├── config/conexion.php    # Conexión a MySQL y listas de valores
├── includes/              # auth.php, header.php, footer.php
├── css/estilos.css        # Estilos (tema azul y blanco)
├── sql/database.sql       # Estructura y datos de la base de datos
├── index.php              # Listado y filtros
├── registrar.php          # Nueva incidencia
├── editar.php             # Editar incidencia (admin)
├── cambiar_estado.php     # Cambiar estado (admin)
├── eliminar.php           # Eliminar incidencia (admin)
└── login.php / logout.php # Inicio y cierre de sesión
```

## Autor
Brandon Delgado Molina - UISIL