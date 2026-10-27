# Mesa de Ayuda Informática

Trabajo práctico. Sistema web en PHP + MySQL (XAMPP) formado por tres módulos
independientes que comparten una misma base de datos y una misma tabla de usuarios.

---

## Cómo levantarlo

1. Copiar la carpeta `practicas-escuela` dentro de `xampp/htdocs`.
2. Iniciar **Apache** y **MySQL** desde el panel de XAMPP.
3. Entrar a phpMyAdmin, crear la base `inventariocomputadorasA` e importar
   `inventariocomputadorasA.sql` (incluye las tablas de los tres módulos).
4. Abrir `http://localhost/practicas-escuela/`.

La conexión se configura en un solo lugar:
`laboratorios/conexion/conexion.php` (usuario `root`, sin contraseña).

## Cómo se usa

```
practicas-escuela/index.php          ← selector de módulo (pantalla pública)
        │
        ├─→ laboratorios/index.php  → login → inicio.php
        ├─→ impresion3d/index.php   → login → inicio.php
        └─→ pañol/index.php         → login → inicio.php
```

Se elige primero el módulo y recién después se inicia sesión, **dentro de ese módulo**.

### Usuarios de prueba

Los tres módulos comparten los mismos emails, contraseñas y roles, pero el acceso se
controla por módulo: que un usuario entre a Laboratorios no significa que pueda entrar
a Herramientas.

| Email | Contraseña | Rol | Módulos a los que entra |
|---|---|---|---|
| admin@gmail.com | 12345678 | Administrativo | Laboratorios, Máquinas de Impresión 3D, Herramientas |
| ematp@gmail.com | 123456 | EMATP | Laboratorios, Máquinas de Impresión 3D |
| profe@gmail.com | 123456 | Profesor | Laboratorios |
| profe2@gmail.com | 12345678 | Profesor | Laboratorios |

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

`sistemaescuela` pasó a llamarse **`laboratorios`**, porque ahora es uno de
tres módulos y el nombre viejo era ambiguo.

No hubo que corregir includes ni rutas: todas eran relativas
(`../conexion/conexion.php`, `../css/estilos.css`, `../includes/menu_*.php`), así que
el renombrado no rompió nada.

**La lógica interna del módulo no se modificó.**

### 2. Módulo nuevo: `impresion3d` (antes `sistemapanolinformatico`)

**Mesa de Ayuda de Máquinas de Impresión 3D** (antes "Pañol Informático"). Dos funciones:

- **Préstamos de insumos**: registrar la entrega de un insumo, registrar su
  devolución, y ver el estado (Pendiente / Devuelto).
- **Stock de repuestos**: ABM de repuestos con su cantidad disponible.

### 3. Módulo nuevo: `pañol` (antes `sistemapanol`)

**Mesa de Ayuda de Herramientas** (antes "Pañol"). Cuatro funciones:

- **Alta de artículos** (y edición).
- **Préstamo de artículos** a una persona.
- **Devolución** del artículo prestado.
- **Baja de artículos**: el artículo pasa a estado `Baja` y deja de aparecer entre
  los prestables, igual que hacen las computadoras en el módulo de Laboratorios.
  También se puede eliminar del todo, salvo que tenga préstamos registrados.

> Las carpetas se llaman `laboratorios`, `impresion3d` y `pañol`, que son también las
> rutas públicas: `mesadeayuda.tec4.ar/laboratorios`, `/impresion3d` y `/pañol`.
> En pantalla los módulos se llaman **Mesa de Ayuda de Laboratorios**,
> **Mesa de Ayuda de Máquinas de Impresión 3D** y **Mesa de Ayuda de Herramientas**.

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
| `articulos` | Herramientas y artículos que se prestan |
| `prestamos_articulos` | Préstamos de artículos y su devolución |

Todas con el mismo estilo del dump original: motor InnoDB, charset `utf8mb4`,
claves primarias y foráneas declaradas con `ALTER TABLE` al final del archivo.

#### Una sola conexión

Para no repetir las credenciales de la base en tres lugares, los módulos nuevos tienen
su propio `conexion/conexion.php`, pero adentro solo incluyen el del módulo original:

```php
include(__DIR__ . "/../../laboratorios/conexion/conexion.php");
```

Lo mismo con los estilos: el `css/estilos.css` de cada módulo nuevo importa la hoja de
Laboratorios, así los tres se ven igual y hay una sola hoja para mantener.

```css
@import url("../../laboratorios/css/estilos.css");
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
| Laboratorios | `id_usuario`, `nombre`, `apellido`, `email`, `id_rol`, `csrf_token` |
| Máquinas de Impresión 3D | `pi_id_usuario`, `pi_nombre`, `pi_apellido`, `pi_email`, `pi_id_rol`, `pi_csrf_token` |
| Herramientas | `panol_id_usuario`, `panol_nombre`, `panol_apellido`, `panol_email`, `panol_id_rol`, `panol_csrf_token` |

Consecuencias:

- Estar logueado en Laboratorios no te deja entrar a Herramientas: hay que hacer el login
  de Herramientas.
- Cerrar sesión en un módulo **no** cierra la de los otros: cada `logout.php` borra
  solo sus propias claves.

#### Roles y accesos

`usuarios.id_rol` **quedó igual que antes** (1 Administrativo, 2 Profesor, 3 EMATP) y
vale en los tres módulos. Los tres tienen la misma estructura: después del login,
`inicio.php` manda a cada uno a `administrativo/`, `profesor/` o `ematp/`.

El acceso a los módulos se resolvió aparte, con la tabla `usuarios_modulos`, porque un
solo `id_rol` no alcanza para decir "este usuario entra a Laboratorios **y** a Herramientas":
habría hecho falta inventar un rol por cada combinación posible.

El **administrativo** puede lo mismo en los tres módulos: Resumen, Usuarios (ABM con
rol y módulos de acceso, igual en los tres) y el ABM propio del módulo. Lo que cambia
entre módulos es lo que pueden hacer el EMATP y el profesor:

| | Laboratorios | Máquinas de Impresión 3D | Herramientas |
|---|---|---|---|
| **Administrativo** | Resumen, Usuarios, Laboratorios, Computadoras, Tickets | Resumen, Usuarios, Repuestos (ABM), Préstamos de insumos (ver todos, registrar, devolver, eliminar) | Resumen, Usuarios, Artículos (ABM, alta/baja), Préstamos (ver todos, registrar, devolver) |
| **EMATP** | Gestionar tickets, componentes y alta/baja de PCs | Registrar entregas y devoluciones de insumos, actualizar el stock de repuestos | Registrar préstamos y devoluciones, consultar artículos |
| **Profesor** | Crear tickets y ver los propios | Pedir insumos a su nombre y ver sus préstamos | Pedir artículos disponibles a su nombre y ver sus préstamos |

Los accesos se asignan desde **Usuarios → Crear / Editar** de cualquiera de los tres
módulos, con casillas de verificación. El listado de usuarios muestra una columna
"Módulos" con lo que tiene asignado cada uno. Un administrador no puede quitarse a sí
mismo el rol de administrador ni el acceso al módulo en el que está.

> Un usuario sin filas en `usuarios_modulos` no puede entrar a ningún módulo
> (tampoco a Laboratorios). Para bloquear a alguien sin borrarlo, quítele los módulos.

### 6. Selector de módulo en la raíz

Se agregó `practicas-escuela/index.php`. Es una pantalla **pública** (no pide sesión)
que muestra los tres módulos y lleva al login de cada uno.

Desde adentro de cualquier módulo se vuelve al selector con el link
**"Cambiar de módulo"**, que está en todos los menús.

---

## Estructura actual

```
practicas-escuela/
├── index.php                        ← selector de módulo (público)
├── inventariocomputadoras.sql
├── README.md
│
├── laboratorios/                    ← antes "sistemaescuela"
│   ├── index.php  login.php  logout.php
│   ├── inicio.php                   ← panel según el id_rol
│   ├── conexion/  css/  includes/
│   └── administrativo/  ematp/  profesor/  uploads/
│
├── impresion3d/                     ← misma estructura que Laboratorios
│   ├── index.php  login.php  logout.php
│   ├── inicio.php                   ← panel según el id_rol
│   ├── conexion/  css/
│   ├── includes/                    ← cabecera, pie, menu_admin/ematp/profesor,
│   │                                   funciones_pi.php
│   ├── administrativo/              ← resumen, usuarios, repuestos, préstamos
│   ├── ematp/                       ← préstamos, stock de repuestos
│   └── profesor/                    ← pedir insumo, mis préstamos
│
└── pañol/                           ← misma estructura que Laboratorios
    ├── index.php  login.php  logout.php
    ├── inicio.php                   ← panel según el id_rol
    ├── conexion/  css/
    ├── includes/                    ← cabecera, pie, menu_admin/ematp/profesor,
    │                                   funciones_panol.php
    ├── administrativo/              ← resumen, usuarios, artículos, préstamos
    ├── ematp/                       ← préstamos, consulta de artículos
    └── profesor/                    ← pedir artículo, mis préstamos
```

Las funciones comunes (escapar texto, mensajes de error, roles, módulos, token de los
formularios) están en `laboratorios/includes/funciones.php` y las usan los tres.

---

## Nota sobre la seguridad

Los tres módulos siguen las mismas prácticas que el módulo principal:

- Las contraseñas se guardan con `password_hash` y se comprueban con `password_verify`.
- Las consultas con datos del usuario usan **sentencias preparadas**.
- Los datos se validan también del lado del servidor y la salida se escapa con `e()`.
- Los formularios que guardan datos llevan un token (`csrf_token`) propio de cada módulo.

Sigue siendo un trabajo práctico: no tiene, por ejemplo, límite de intentos de login ni
registro de auditoría.
