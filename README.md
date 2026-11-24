# Sabores de siempre - Sistema web de restaurante

Trabajo Práctico Final de **Desarrollo Web**. Universidad Nacional de Lomas de Zamora (UNLZ).

- **Profesora:** Tec. Agustina Aguirre
- **Integrantes:** Emmanuel · Ivana · Bruno

Sistema web para un restaurante: los clientes ven el menú, consultan el detalle de cada plato y reservan una mesa online. El personal ingresa a un panel protegido para ver las reservas y manejar el estado de las mesas. Además, un superadministrador gestiona a los empleados.

---

## Cómo correr el proyecto

**Requisitos:** PHP 8 con la extensión `pdo_sqlite` (viene incluida en XAMPP). No hace falta instalar ni configurar una base de datos.

**Opción 1: servidor de PHP (recomendada)**

1. Abrir una terminal en la carpeta del proyecto.
2. Ejecutar:
   ```
   C:\xampp\php\php.exe -S localhost:8000
   ```
3. Abrir en el navegador: <http://localhost:8000/index.php>

**Opción 2: XAMPP con Apache**

1. Copiar la carpeta del proyecto dentro de `C:\xampp\htdocs\`.
2. Iniciar **Apache** desde el panel de XAMPP.
3. Abrir <http://localhost/Trabajo-final-restaurante/index.php>

**La base de datos se crea sola.** La primera vez que se abre una página que usa la base, se genera el archivo `reservas.db` con todas las tablas, 12 mesas y un usuario administrador. Este archivo no se sube al repositorio (está en `.gitignore`).

### Usuario de prueba

| Usuario | Contraseña | Rol |
|---|---|---|
| `admin` | `admin123` | Superadministrador |

Para probar el rol de empleado: entrar como `admin`, crear un empleado desde la sección **Empleados** del panel, cerrar sesión y entrar con ese usuario.

---

## Funcionalidades

| Requisito | Dónde está | Descripción |
|---|---|---|
| Página de inicio | `index.php` | Portada con foto, presentación, horarios, ubicación y accesos a reservar y al menú. |
| Catálogo | `menu.php` | 13 platos en tres categorías, cargados desde un array de PHP y recorridos con `foreach`. |
| Detalle | `plato.php?cat=...&id=...` | Foto, descripción y precio de cada plato. Si el plato no existe, muestra "Plato no encontrado" (404). |
| Buscador y filtro | `js/menu.js` | Filtro por categoría y buscador por nombre que ignora mayúsculas y tildes, sin recargar la página. |
| Formulario | `reserva.php` | Reserva con nombre, email, teléfono (de 7 a 10 números), fecha, hora, personas y comentarios. Se valida con JavaScript y otra vez con PHP. Al confirmar, se entrega un **número de reserva**. |
| Cancelación | `reserva.php` → `cancelar_reserva.php` | El cliente cancela su reserva ingresando el número que recibió. |
| Login | `login.php` | Usuario y contraseña verificados contra la base, con sesiones de PHP. |
| Zona protegida | `admin.php` | Panel del personal: mesas, reservas del día y empleados. Sin sesión iniciada, redirige al login. |
| Diseño responsive | `css/` | Se adapta a celular, tablet y computadora. |

### Reglas del negocio

- **Mesas:** 12 en total (4 de 2 personas, 4 de 4, 2 de 6 y 2 de 8).
- **Estado de las mesas:** libre (verde), reservada (amarillo) u ocupada (rojo). Lo cambia el personal a mano desde el panel.
- **Reservas:** de 1 a 5 personas se reservan online. Para grupos de 6 o más, el sistema pide comunicarse con el restaurante.
- **Tolerancia:** la reserva confirmada informa 20 minutos de tolerancia.
- **No se puede reservar** para una fecha o una hora que ya pasaron.

### Roles

| Acción | Empleado | Superadministrador |
|---|:---:|:---:|
| Ver las reservas del día (o de otro día) | ✅ | ✅ |
| Cambiar el estado de las mesas | ✅ | ✅ |
| Ver la lista de empleados | ❌ | ✅ |
| Agregar empleados (con contraseña inicial) | ❌ | ✅ |
| Eliminar empleados | ❌ | ✅ (no a sí mismo) |

Los permisos se controlan en el servidor (PHP), no solo ocultando botones. Además, el panel verifica en cada carga que el usuario siga existiendo: si un empleado es eliminado con la sesión abierta, se lo saca del panel.

---

## Estructura del proyecto (MVC)

El código está organizado en **Modelo, Vista y Controlador**:

- **Modelo** (`models/`): lo único que habla con la base de datos o con los datos. Ningún otro archivo escribe SQL.
- **Vista** (`views/`): solo HTML. Recibe los datos ya preparados y los muestra.
- **Controlador** (los `.php` de la raíz): recibe la petición del navegador, le pide los datos al modelo y elige qué vista mostrar.

```
├── index.php               Controlador: inicio
├── menu.php                Controlador: catálogo
├── plato.php               Controlador: detalle de un plato
├── reserva.php             Controlador: formulario de reserva
├── guardar_reserva.php     Controlador: valida y guarda la reserva
├── cancelar_reserva.php    Controlador: cancela una reserva
├── login.php               Controlador: inicio de sesión
├── logout.php              Controlador: cierre de sesión
├── admin.php               Controlador: panel del personal (zona protegida)
│
├── models/
│   ├── conexion.php        Conexión a SQLite y creación de tablas y datos iniciales
│   ├── Plato.php           Catálogo de platos
│   ├── Reserva.php         Validación, alta, cancelación y listado de reservas
│   ├── Usuario.php         Búsqueda, alta y baja de usuarios
│   └── Mesa.php            Listado y cambio de estado de mesas
│
├── views/
│   ├── layout/
│   │   ├── header.php      Cabecera y navegación comunes a todas las páginas
│   │   └── footer.php      Pie de página común
│   ├── inicio.php
│   ├── login.php
│   ├── admin.php
│   ├── reserva.php
│   ├── reserva_confirmada.php
│   └── reserva_mensaje.php Mensajes de error, grupos grandes y cancelación
│
├── css/
│   ├── estilos.css         Estilos generales, inicio y menú
│   ├── reserva.css         Formulario de reserva
│   └── admin.css           Login y panel
├── js/
│   ├── menu.js             Filtro y buscador del menú
│   ├── reserva.js          Validación del formulario de reserva
│   └── admin.js            Contador de mesas y confirmación al eliminar
└── img/                    Fotos de los platos y del restaurante
```

**Ejemplo del recorrido de una petición (login):**

```
navegador → login.php (controlador)
              → models/Usuario.php: buscarUsuarioPorNombre()   (consulta a la base)
              → password_verify() y sesión
              → views/layout/header.php + views/login.php + views/layout/footer.php
          ← página HTML
```

### Base de datos (SQLite)

| Tabla | Columnas principales |
|---|---|
| `reservas` | id (número de reserva), nombre, email, telefono, fecha, hora, personas, comentarios |
| `usuarios` | id, nombre, usuario (único), contrasenia_hash, rol (`superadmin` o `empleado`) |
| `mesas` | id, numero, capacidad, estado (`libre`, `reservada` u `ocupada`) |

Los nombres técnicos (tablas, columnas, variables y archivos) no llevan tildes ni eñes.

---

## Seguridad

- **Contraseñas encriptadas** con `password_hash` y verificadas con `password_verify`. Nunca se guardan ni se muestran en texto plano.
- **Consultas preparadas** (PDO con `?` o `:nombre`) en todas las consultas, para evitar inyección SQL.
- **`htmlspecialchars`** al mostrar cualquier dato ingresado por usuarios, para evitar que se ejecute código en la página.
- **Validación doble:** en el navegador con JavaScript (rápida, para el usuario) y en el servidor con PHP (la que no se puede saltear).
- **Sesión:** se regenera el identificador al iniciar sesión, y en la sesión solo se guardan id, nombre, usuario y rol.
- **Mismo mensaje** si falla el usuario o la contraseña, para no revelar qué usuarios existen.
- **Patrón POST → redirección:** después de cada acción del panel se redirige, para que recargar la página no repita la acción.

## Accesibilidad y diseño

- HTML semántico: `header`, `nav`, `main`, `section`, `article`, `footer` y `address`, con un solo `h1` por página.
- Todos los campos tienen su `label`, y todas las imágenes, texto alternativo.
- Enlace "Saltar al contenido" para quien navega con teclado.
- El menú marca la página actual con `aria-current`.
- Los estados de las mesas se indican con color **y** con texto.
- Tablas con encabezados `scope="col"`, que en celular se desplazan de costado.
- Diseño responsive con media queries, flexbox y grid.

---

## Organización del trabajo

- **Repositorio:** una rama por integrante (`emma`, `ivana`, `bruno`) y `main` como versión estable. Cada cambio se integró a `main` mediante Pull Requests.
- **Reparto por funcionalidad:** cada integrante se encargó de una parte completa del sistema (HTML, CSS, JavaScript y PHP).

| Integrante | Parte del sistema |
|---|---|
| **Emmanuel Balzi** | Inicio, header y footer comunes, login, panel del personal, roles, base de datos de usuarios y mesas, organización MVC |
| **Ivana** | Menú, detalle de plato, catálogo, buscador y filtro, imágenes, estilos generales, README |
| **Bruno** | Formulario de reserva, validaciones, base de datos de reservas, número de reserva y cancelación |

### Avance por semana

**Semana 1 – Maquetado**
- Estructura de carpetas y repositorio con ramas por integrante.
- Páginas en HTML semántico: inicio, menú, detalle, reserva, login y panel.

**Semana 2 – Diseño**
- Estilos generales, portada con imagen y diseño responsive.
- Navegación común con la página actual resaltada.
- Estilos del login y del panel; mesas con colores según su estado.
- Sección de empleados con roles.
- Imágenes de los platos en mejor resolución.

**Semana 3 – JavaScript y PHP**
- Catálogo dinámico con `foreach` y detalle de cada plato.
- Filtro por categoría y buscador con JavaScript.
- Formulario de reserva validado con JavaScript y PHP, guardado en la base, con número de reserva y cancelación.
- Base de datos SQLite con reservas, usuarios y mesas.
- Login con sesiones, logout y zona protegida.
- Panel conectado a la base: estados de mesa manuales, reservas por día y gestión de empleados según el rol.

**Semana 4 – Organización y cierre**
- Proyecto organizado en Modelo, Vista y Controlador.
- Header y footer en un único lugar (`views/layout/`).
- Correcciones de las devoluciones: nombres técnicos sin tildes ni eñes, catálogo unificado, validaciones de fecha y de cantidad de personas, confirmación antes de eliminar un empleado, control de usuarios eliminados con sesión abierta.
- Nombre del restaurante: **Sabores de siempre** (en el header, la portada y el título de cada página).
- Validación del teléfono más precisa (entre 7 y 10 números), con un mensaje distinto para cada error.
- Corrección del botón "Reservar mesa" en el detalle de cada plato.

---

## Mejoras futuras

- **Protección CSRF:** un token en la sesión, validado en cada formulario.
- **Asignación automática de mesas:** al reservar, asignar la mesa más chica disponible según la cantidad de personas y el horario.
- **Vistas del menú:** pasar el HTML de `menu.php` y `plato.php` a `views/`, como el resto de las páginas.
- **Catálogo en la base de datos:** guardar los platos en una tabla para poder editarlos desde el panel.
- **Cambio de contraseña:** que cada usuario pueda cambiar la contraseña inicial que le asignó el superadministrador.
