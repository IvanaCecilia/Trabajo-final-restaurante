# Trabajo-final-restaurante
La idea principal es crear una pagina con buen diseño, que sea responsive adaptable a cualquier dispositivo donde se vea.
En principio creamos carpetas y archivos por separado para trabajar mejor, luego eso se modificara.
Se realizo el index.html (el archivo que abre el navegador) y dentro enlazamos el css en el head (los estilos de todos los elementos de la pagina) y el archivo script.js (lenguaje de comportamiento de interaccion en la pagina) al final del body.
En una carpeta llamada img se cargaron las imagenes de los platos del restaurante. 
Se creo un formulario de reservas, este sirve para que se reserve una o varias mesas (segun cantidad de personas) en determinado dia y horario. En este formulario, al llenar los campos y enviarlo se mostrara un cartel con la fecha de la reserva y tiempo de tolerancia, o un cartel que comunique si no hay mesas disponibles para reservar. 
Se creo un catalogo en php con un foreach (un array mulidimensional, un array general de platos y dentro un array de cada plato, lo mismo para entradas y postres) 
Se agrego menu y platos en php. Y diseños de los mismos.
Se sumaron botones solo diseño en menu y platos. 
Se hicieron validaciones de campos del formulario. 
Se agrego base de datos para guardar reservas.
Se agrego diseño responsive.
Se creo portada inicio con imagen.
Se creo una nueva seccion empleados con roles definidos. 
Se mejoro resolucion de imagenes, se elimino las anteriores y se agrego estilo en platos para que se adapten a todas las pantallas. 
Se elimino carpeta catalogo php duplicada.
Se agregaron botones en menu.js.
Páginas a PHP 
Archivos index.html, login.html y admin.html ahora son index.php, login.php y admin.php, y usan el header y el footer compartidos de includes/.
Se agrego formulario de cancelacion de reservas. 
El header arranca la sesión y cambia el menú: sin login muestra “Ingresar”, y logueado muestra “Panel” y “Salir”.
Login con sesiones
El login verifica usuario y contraseña contra la base (password_verify, contraseñas encriptadas con password_hash).logout.php cierra la sesión.
El panel quedó protegido: sin login te manda al login.
Tablas usuarios (con rol) y mesas en la base SQLite (conexion.php). La base se crea sola la primera vez, con un usuario administrador y 12 mesas (4 de 2 personas, 4 de 4, 2 de 6 y 2 de 8).
Roles y permisos: el sistema contempla dos roles de usuarios; uno el empleado que consulta el estado de las reservas y cambia el estado de las mesas; otro el superadministrador que tiene los permisos del empleado y ademas gestiona al empleado (agrega nuevos, elimina despedidos por ejemplo).
Para probar: admin / admin123. Creen un empleado desde el panel y entren con él para ver la diferencia de roles.

## Cómo probar el sistema

1. Iniciar el servidor de PHP en la carpeta del proyecto
   (por ejemplo: C:\xampp\php\php.exe -S localhost:8000) o copiar la carpeta en htdocs de XAMPP.
2. Abrir http://localhost:8000/index.php.
3. La base de datos (reservas.db) se crea sola al abrir la primera página.
4. Para entrar al panel: *Ingresar* → usuario admin, contraseña admin123.
5. Desde el panel se pueden crear empleados y entrar con ellos para ver la diferencia de roles. 

