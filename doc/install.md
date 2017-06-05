# INSTALACIÓN

Se requiere de un servidor web, PHP7, y un servidor MySql (o MariaDB). El servidor web deberá tener activada la opción `RewriteEngine`.

Se tendrá que crear una base de datos **hippocampus**, y un usuario **hippocampus** con contraseña **hippocampus** que tenga permisos para la base de datos anterior. Estos datos están en `core/config.php` en caso de querer ser cambiados.

Primero, clonar el repositorio en una carpeta accesible públicamente.
Modificar en el archivo *.htaccess*, la linea

```
	RewriteBase /AW/Hippocampus/
```

Cambiando `/AW/Hippocampus/` por la ruta sobre la que funcione la web (sin el dominio).

Por ejemplo, para la ruta: `localhost/HC`, RewriteBase tendría que tener el valor `/HC/`

Si en vez de Apache se usa otro navegador, se tendría que configurar una regla similar.
Por ejemplo, en el caso de Caddyserver, el código a usar con la misma funcionalidad que el *.htaccess* sería:

```
	rewrite {
	  to {path} {path}/ /index.php?p={uri}
	}
```

Tras tener todo configurado, accedemos con el navegador a la página. La primera vez que se acceda se verá un mensaje *"Database updated, please refresh"*. La base de datos tendrá las tablas y las entradas necesarias para funcionar.
Tras volver a entrar en la página, se verá la página principal de la web.

Inicialmente hay 3 usuarios con los que poder acceder:
	* root
	* admin
	* user

Los tres acceden con la misma contraseña: `Qwerty1!`

Se pueden registrar más usuarios siguiendo el botón para registrase.
