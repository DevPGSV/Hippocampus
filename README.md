<p align="center">
	<img src="img/yJuHskd.png" width="550px" />
</p>

# HIPPOCAMPUS #

Hippocampus es un proyecto desarrollado dentro del marco de la asignatura **Aplicaciones Web** del Grado en Ingeniería Informática por la Universidad Complutense de Madrid.

## Índice ##
- [Objetivo](#objective)
- [Servicios conectados](#services)
- [Guía de instalación](#install)
- [Contacto](#contact)

## <a name="objective"></a>Objetivo ##
Nuestra aplicación web busca conectar todos los servicios online ofertados por la universidad como pueden ser la plataforma Moodle, el correo electrónico académico, los srevicios de préstamo de la biblioteca o toda la información referente a los servicios disponibles en la facultad.

A través de Hippocampus, tanto alumnos como profesores podrán acceder a todos esos servicios sin necesidad de andar cambiando de pestaña en el navegador e iniciando sesión tan solo una vez.

Nuestra aplicación tiene como finalidad ofrecerle al usuario un servicio cómodo y sencillo, mostrando toda la información relevante de un vistazo, así como notificaciones actualizadas de los servicios a los que se encuentre conectado, para no perderse ninguna novedad.

## <a name="services"></a>Servicios conectados ##
Nuestra aplicación permite al usuario conectar con su cuenta de Hippocampus tantos servicios como quiera de la siguiente relación:

### Gestión académica ###

* gestión de identidad (gestión de datos personales, constraseñas y cuentas bancarias)
* geanet (gestión de notas y calificaciones)
* campus virtual (Moodle)
* biblioteca

### Google ###

* Gmail
* Google Drive
* Google Calendar
* Google Clasroom

### Mensajería ###

* Bolotweet
* Chat de mensajería instantánea

### Redes sociales ###

* Facebook
* Twitter

### Aplicaciones informáticas ###

* Github
* Software (enlaces de descarga de programas con licencia ofrecida por la universidad)

### Servicios de la Facultad ###

* Asociaciones
* Cafetería
* Secretaría

### Otros ###

* Ajustes
* Sobre nosotros

## <a name="install"></a>Guía de instalación ##

Se requiere de apache con mod_rewrite activado, con permiso al uso de .htaccess. En el archivo .htaccess, modificar en

	RewriteBase /AW/Hippocampus/

la ruta por el lugar de instalación en el sistema. Lo anterior es un ejemplo para:

[http://localhost/AW/Hippocampus/](http://localhost/AW/Hippocampus/)

Crear una base de datos llamada "hippocampus" y un usuario "hippocampus" con contraseña "hippocampus". El usuario creado debe tener acceso completo a la base de datos. Durante la primera carga (que tardará más) se crearán las tablas en la base de datos.

## <a name="contact"></a>Contacto ##

La relación de los estudiantes que trabajan en el desarrollo de este proyecto es la siguiente:

* [Pablo García de los Salmones Valencia](https://github.com/devpgsv/)
* Iván Gulyk
* [Guillermo Monserrate Sánchez](https://github.com/RamzaFFT/)
* [Marta Pastor Puente](https://github.com/martapastor/)
* [Blanca de la Torre Fuertes](https://github.com/bldelato/)
