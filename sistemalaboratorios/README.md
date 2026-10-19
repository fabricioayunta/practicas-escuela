# Mesa de Ayuda Informática – Sistema Escuela

Sistema web hecho en **PHP + MySQL** para gestionar el inventario de computadoras de una escuela y los **tickets** de problemas técnicos.

Los profesores reportan fallas, el personal técnico (EMATP) las atiende y el administrativo gestiona usuarios, laboratorios y computadoras.

---

## Requisitos

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL/MariaDB)
- PHP 8.1 o superior
- Un navegador

---

## Instalación (paso a paso)

### 1. Copiar el proyecto

Copiá la carpeta **`sistemaescuela`** dentro de la carpeta `htdocs` de XAMPP:

```
C:\xampp\htdocs\sistemaescuela
```

### 2. Encender los servicios

Abrí el **Panel de control de XAMPP** y presioná **Start** en:

- **Apache**
- **MySQL**

### 3. Importar la base de datos

1. Entrá a <http://localhost/phpmyadmin>.
2. Andá a la pestaña **Importar**.
3. Elegí el archivo **`inventariocomputadorasA.sql`**.
4. Presioná **Importar**.

El archivo crea solo la base `inventariocomputadorasA` con todas sus tablas y datos de ejemplo, no hace falta crearla a mano.

>  Importá **solamente** este archivo. El nombre de la base debe ser exactamente `inventariocomputadorasA`, porque el sistema lo usa en `conexion/conexion.php`.

### 4. Abrir el sistema

Entrá a:

```
http://localhost/sistemaescuela/
```

---

## Usuarios de prueba

| Rol            | Email              | Contraseña |
| -------------- | ------------------ | ---------- |
| Administrativo | `admin@gmail.com`  | `12345678` |
| Profesor       | `profe@gmail.com`  | `12345678` |
| EMATP          | `ematp@gmail.com`  | `12345678` |


\* Si `12345678` no funciona para estos usuarios, entrá como administrativo y cambiá la contraseña desde **Usuarios → Editar**.

Al iniciar sesión, el sistema te lleva automáticamente al panel que corresponde a tu rol.

---

## Cómo se usa

###  Profesor
- **Crear ticket:** elegí el laboratorio y el número de computadora, marcá los componentes con problemas (Mother, Procesador, Memoria RAM, Disco, Monitor, Teclado, Mouse), escribí la descripción y, si querés, adjuntá una foto (JPG, PNG o WEBP, máximo 5 MB).
- **Mis tickets:** ver el estado de tus tickets y editarlos.

###  EMATP (soporte técnico)
- **Tickets:** ver los tickets, cambiar su estado (*Abierto*, *Pendiente*, *Cerrado*) y dejar observaciones. Cada cambio queda en el historial.
- **Computadoras:** ver el detalle de cada equipo y editar sus componentes.

###  Administrativo
- **Resumen:** panel con estadísticas generales.
- **Usuarios:** crear, editar y eliminar usuarios.
- **Laboratorios:** crear, editar y eliminar laboratorios.
- **Computadoras:** crear, editar y eliminar computadoras.
- **Tickets:** ver todos los tickets con su detalle.

---

## Estructura del proyecto

```
practicas-escuela/
├── inventariocomputadorasA.sql   ← base de datos (importar esta)
└── sistemaescuela/
    ├── index.php                 ← pantalla de login
    ├── login.php / logout.php    ← inicio y cierre de sesión
    ├── conexion/conexion.php     ← datos de conexión a MySQL
    ├── includes/                 ← menús y funciones compartidas
    ├── css/                      ← estilos
    ├── administrativo/           ← pantallas del rol Administrativo
    ├── profesor/                 ← pantallas del rol Profesor
    ├── ematp/                    ← pantallas del rol EMATP
    └── uploads/tickets/          ← fotos subidas en los tickets
```

---

## Configuración de la conexión

Los datos de conexión están en `sistemaescuela/conexion/conexion.php`. Los valores por defecto son los de XAMPP:

```php
$servidor   = "localhost";
$usuario    = "root";
$contrasena = "";
$base_datos = "inventariocomputadorasA";
```

Si tu MySQL tiene otra contraseña o usuario, cambialo ahí.

---

## Problemas frecuentes

**"No se pudo conectar con la base de datos"**
Verificá que MySQL esté encendido en XAMPP y que la base se llame exactamente `inventariocomputadorasA`.

**Error al crear un ticket (`mysqli_stmt_bind_param() ... bool given`)**
Casi siempre significa que se importó una versión vieja de la base, sin la columna `componentes_afectados` en la tabla `tickets`. Borrá la base e importá de nuevo `inventariocomputadorasA.sql`.

**La foto del ticket no se sube**
Revisá que sea JPG, PNG o WEBP y que pese menos de 5 MB. Si el problema sigue, verificá que exista la carpeta `uploads/tickets/` y que PHP pueda escribir en ella.

**Me redirige al login todo el tiempo**
La sesión expiró o no iniciaste sesión. Volvé a ingresar desde `http://localhost/sistemaescuela/`.

---

## Notas

- Las contraseñas se guardan con hash (`password_hash`), nunca en texto plano.
- Los archivos `hash.php` y `rol.php` son scripts auxiliares de desarrollo (para verificar un hash y crear usuarios iniciales). Conviene **eliminarlos** si el sistema se publica fuera de la red local.
- Proyecto de prácticas escolares, pensado para uso local.
