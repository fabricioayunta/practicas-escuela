# Sistema de Gestión Escolar

Trabajo práctico. Sistema web en PHP + MySQL (XAMPP) formado por tres módulos
independientes que comparten una misma base de datos y una misma tabla de usuarios.

---

## Cómo levantarlo

1. Copiar la carpeta `practicas-escuela` dentro de `xampp/htdocs`.
2. Iniciar **Apache** y **MySQL** desde el panel de XAMPP.
3. Entrar a phpMyAdmin, crear la base `inventariocomputadoras` e importar
   `inventariocomputadoras.sql`.
4. Abrir `http://localhost/practicas-escuela/`.

La conexión se configura en un solo lugar:
`sistemalaboratorios/conexion/conexion.php` (usuario `root`, sin contraseña).

## Cómo se usa

```
practicas-escuela/index.php          ← selector de módulo (pantalla pública)
        │
        ├─→ sistemalaboratorios/index.php      → login → inicio.php
        ├─→ sistemapanolinformatico/index.php  → login → inicio.php
        └─→ sistemapanol/index.php             → login → inicio.php
```

Se elige primero el módulo y recién después se inicia sesión, **dentro de ese módulo**.

### Usuarios de prueba

Hoy los tres módulos comparten los mismos emails y contraseñas, pero el acceso se
controla por módulo: que un usuario entre a Laboratorios no significa que pueda entrar
a Pañol.

| Email | Contraseña | Rol en Laboratorios | Módulos a los que entra |
|---|---|---|---|
| admin@gmail.com | 12345 | Administrativo | Laboratorios, Pañol Informático, Pañol |
| ematp@gmail.com | 12345 | EMATP | Laboratorios, Pañol Informático |
| profe@gmail.com | 12345 | Profesor | Laboratorios |
| luly@gmail.com | 12345 | Profesor | Laboratorios |

---

## Cómo era antes

El proyecto era **un solo módulo**, en la carpeta `sistemaescuela`, llamado
"Inventario De Computadoras". Servía como mesa de ayuda informática de la escuela.

### Estructura original

```
practicas-escuela/
├── inventariocomputadoras.sql
└── sistemaescuela/
    ├── index.php          ← login
    ├── login.php
    ├── logout.php
    ├── inicio.php         ← panel según el rol del usuario
    ├── conexion/conexion.php
    ├── css/estilos.css
    ├── includes/          ← menu_admin.php, menu_ematp.php, menu_profesor.php
    ├── administrativo/    ← ABM de usuarios, laboratorios y computadoras + dashboard
    ├── ematp/             ← gestión de tickets y componentes de las PC
    ├── profesor/          ← crear y ver sus propios tickets
    └── uploads/           ← fotos adjuntas a los tickets
```

### Roles

Cada usuario tenía **un solo rol**, guardado en `usuarios.id_rol`:

| id_rol | Rol | Qué podía hacer |
|---|---|---|
| 1 | Administrativo | ABM de usuarios, laboratorios y computadoras, dashboard, ver tickets |
| 2 | Profesor | Crear tickets y ver los propios |
| 3 | EMATP | Gestionar tickets, ver y editar componentes, dar de alta/baja PCs |

Después de iniciar sesión, `inicio.php` mostraba un panel distinto según ese `id_rol`.

### Tablas originales

`usuarios`, `roles`, `laboratorios`, `computadoras`, `componentes`,
`tickets`, `historialticket`, `historial_computadoras`.

### Patrón de código

Todas las pantallas siguen el mismo esquema:

```
listar.php → crear_X.php → guardar_X.php
           → editar_X.php → actualizar_X.php
           → eliminar_X.php
```

Cada archivo empieza con la guarda de sesión, incluye `conexion.php`, arma el SQL
por interpolación de strings y termina con el HTML.

---

## Qué se cambió

### 1. Renombrado del módulo original

`sistemaescuela` pasó a llamarse **`sistemalaboratorios`**, porque ahora es uno de
tres módulos y el nombre viejo era ambiguo.

No hubo que corregir includes ni rutas: todas eran relativas
(`../conexion/conexion.php`, `../css/estilos.css`, `../includes/menu_*.php`), así que
el renombrado no rompió nada.

**La lógica interna del módulo no se modificó.**

### 2. Módulo nuevo: `sistemapanolinformatico`

Gestión del pañol informático. Dos funciones:

- **Préstamos de insumos**: registrar la entrega de un insumo, registrar su
  devolución, y ver el estado (Pendiente / Devuelto).
- **Stock de repuestos**: ABM de repuestos con su cantidad disponible.

### 3. Módulo nuevo: `sistemapanol`

Gestión del pañol general. Cuatro funciones:

- **Alta de artículos** (y edición).
- **Préstamo de artículos** a una persona.
- **Devolución** del artículo prestado.
- **Baja de artículos**: el artículo pasa a estado `Baja` y deja de aparecer entre
  los prestables, igual que hacen las computadoras en el módulo de Laboratorios.
  También se puede eliminar del todo, salvo que tenga préstamos registrados.

> Las carpetas se llaman `sistemapanol` y `sistemapanolinformatico`, **sin la ñ**,
> para evitar problemas de codificación en las URLs. En pantalla siempre se lee
> "Pañol" con ñ.

### 4. Base de datos compartida

Los tres módulos usan la **misma base** (`inventariocomputadoras`) y la **misma tabla
`usuarios`**. No hay bases ni usuarios duplicados.

Tablas nuevas:

| Tabla | Para qué |
|---|---|
| `modulos` | Los tres módulos del sistema (id, nombre, carpeta) |
| `usuarios_modulos` | A qué módulos puede entrar cada usuario |
| `prestamos_insumos` | Entregas de insumos informáticos y su devolución |
| `repuestos` | Stock de repuestos informáticos |
| `articulos` | Artículos del pañol |
| `prestamos_articulos` | Préstamos de artículos y su devolución |

Todas con el mismo estilo del dump original: motor InnoDB, charset `utf8mb4`,
claves primarias y foráneas declaradas con `ALTER TABLE` al final del archivo.

#### Una sola conexión

Para no repetir las credenciales de la base en tres lugares, los módulos nuevos tienen
su propio `conexion/conexion.php`, pero adentro solo incluyen el del módulo original:

```php
include(__DIR__ . "/../../sistemalaboratorios/conexion/conexion.php");
```

Lo mismo con los estilos: el `css/estilos.css` de cada módulo nuevo importa la hoja de
Laboratorios, así los tres se ven igual y hay una sola hoja para mantener.

```css
@import url("../../sistemalaboratorios/css/estilos.css");
```

### 5. Un login por módulo

Cada módulo tiene su propio `index.php` (formulario), `login.php` y `logout.php`.

`login.php` valida el email y la contraseña contra la tabla `usuarios` y después
verifica en `usuarios_modulos` que ese usuario tenga acceso a **ese** módulo:

```php
$sqlAcceso = "SELECT id_modulo
              FROM usuarios_modulos
              WHERE id_usuario = '".$usuario["id_usuario"]."'
              AND id_modulo = 3";
```

Si no tiene ese acceso, el login falla aunque el email y la contraseña sean correctos.

#### Las sesiones son independientes

Ser administrativo o profesor en un módulo **no** te convierte en administrativo ni
profesor de otro. Por eso cada módulo guarda su sesión con sus propias claves:

| Módulo | Claves de sesión |
|---|---|
| Laboratorios | `id_usuario`, `nombre`, `apellido`, `email`, `id_rol` |
| Pañol Informático | `pi_id_usuario`, `pi_nombre`, `pi_apellido`, `pi_email` |
| Pañol | `panol_id_usuario`, `panol_nombre`, `panol_apellido`, `panol_email` |

Consecuencias:

- Estar logueado en Laboratorios no te deja entrar a Pañol: hay que hacer el login de
  Pañol.
- Cerrar sesión en un módulo **no** cierra la de los otros: cada `logout.php` borra
  solo sus propias claves.

#### Roles y accesos

`usuarios.id_rol` **quedó igual que antes** (1 Administrativo, 2 Profesor, 3 EMATP) y
sigue definiendo qué ve el usuario *dentro* del módulo de Laboratorios, que es el único
que tiene roles internos.

El acceso a los módulos se resolvió aparte, con la tabla `usuarios_modulos`, porque un
solo `id_rol` no alcanza para decir "este usuario entra a Laboratorios **y** a Pañol":
habría hecho falta inventar un rol por cada combinación posible.

Los accesos se asignan desde el panel administrativo de Laboratorios, en
**Usuarios → Crear / Editar**, con casillas de verificación. El listado de usuarios
muestra una columna "Módulos" con lo que tiene asignado cada uno.

> Un usuario sin filas en `usuarios_modulos` no puede entrar a ningún módulo.
> Si creaste usuarios antes de este cambio, hay que editarlos y marcarles los módulos.

### 6. Selector de módulo en la raíz

Se agregó `practicas-escuela/index.php`. Es una pantalla **pública** (no pide sesión)
que muestra los tres módulos y lleva al login de cada uno.

Desde adentro de cualquier módulo se vuelve al selector con el link
**"🔁 Cambiar de Módulo"**, que está en todos los menús.

---

## Estructura actual

```
practicas-escuela/
├── index.php                        ← selector de módulo (público)
├── inventariocomputadoras.sql
├── README.md
│
├── sistemalaboratorios/             ← antes "sistemaescuela"
│   ├── index.php  login.php  logout.php
│   ├── inicio.php                   ← panel según el id_rol
│   ├── conexion/  css/  includes/
│   └── administrativo/  ematp/  profesor/  uploads/
│
├── sistemapanolinformatico/
│   ├── index.php  login.php  logout.php
│   ├── inicio.php
│   ├── prestamos.php  crear_prestamo.php  guardar_prestamo.php
│   │   devolver_prestamo.php  eliminar_prestamo.php
│   ├── repuestos.php  crear_repuesto.php  guardar_repuesto.php
│   │   editar_repuesto.php  actualizar_repuesto.php  eliminar_repuesto.php
│   └── conexion/  css/  includes/
│
└── sistemapanol/
    ├── index.php  login.php  logout.php
    ├── inicio.php
    ├── articulos.php  crear_articulo.php  guardar_articulo.php
    │   editar_articulo.php  actualizar_articulo.php
    │   cambiar_estado_articulo.php  eliminar_articulo.php
    ├── prestamos.php  crear_prestamo.php  guardar_prestamo.php
    │   devolver_prestamo.php
    └── conexion/  css/  includes/
```

---

## Nota sobre la seguridad

**Este sistema es intencionalmente inseguro.** Es un trabajo práctico y los módulos
nuevos replican a propósito las mismas debilidades que tenía el original:

- Las contraseñas se guardan y se comparan en **texto plano**, sin hash.
- Las consultas se arman **interpolando strings** en el SQL, salvo la búsqueda del
  usuario en los `login.php`, que ya usaba una sentencia preparada.
- Las validaciones son mínimas y casi todas del lado del HTML.
- La salida no se escapa, salvo donde ya se escapaba.

No usar este código como base de un sistema real sin corregir todo eso.
