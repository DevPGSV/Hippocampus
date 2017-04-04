
// Expresión para validar email
//validemail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

/**
* Validar el formulario de registro de usuario.
*/
function validaRegistro(form) {
   // Comprobar los campos del formulario de registro
   if (form.nombre.value == '' || form.email.value == '' || form.usuario.value == '' || form.password.value == '' || form.confirmpassword.value == '') {
       alert('Debe proporcionar todos los campos solicitados. Por favor, inténtelo de nuevo.');
       return false;
   }

   // Validar el campo email
   validemail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
   if (!validemail.test(form.email.value)) {
       alert('El formato de email es incorrecto. Por favor, inténtelo de nuevo.');
       return false;
   }

   // Validar el campo nombre de usuario
    validuser = /^([a-zA-Z0-9]{4,20})+$/;
    if(!validuser.test(form.usuario.value)) {
       alert("El nombre de usuario debe contener solo caracteres alfanuméricos y tener una longitud entre 4 y 20 caracteres. Por favor, inténtelo de nuevo.");
       return false;
    }

   // Validar el campo contraseña
   if (form.password.value != "" && form.confirmpassword.value != "") {

        // Validar el tamaño de la contraseña
        if (form.password.value.length < 8) {
          alert('La contraseña debe tener al menos 8 caracteres. Por favor, inténtelo de nuevo.');
          //form.password.focus();
          return false;
        }

        if (form.password.value.length > 20) {
          alert('La contraseña debe tener como máximo 20 caracteres. Por favor, inténtelo de nuevo.');
          //form.password.focus();
          return false;
        }

        // Validar composición de la contraseña
        validpassword = /(?=.*[0-9])(?=.*[¡!¿?@#$%^&*/\+_<>])(?=.*[a-z])(?=.*[A-Z]).{8,20}/;
        if (!validpassword.test(form.password.value)) {
          alert('La contraseña debe contener al menos un número, una letra minúscula, una mayúscula y un caracter espcecial. Por favor, inténtelo de nuevo.');
          return false;
        }

        // Validar contraseña y confirmación de la contraseña
        if (form.password.value != form.confirmpassword.value) {
          alert('Su contraseña y la confirmación de la contraseña no coinciden. Por favor, inténtelo de nuevo.');
          form.password.focus();
          return false;
        }

        // Crear un campo nuevo, este será un hash de la contraseña.
        var passwordhash = document.createElement("input");

        // Enviar un nuevo campo oculto con la clave encriptada.
        form.appendChild(p);
        passwordhash .name = "pass";
        passwordhash .id = "pass";
        passwordhash .type = "hidden";
        passwordhash .value = hex_sha512(password.value);

        // Limpiar los campos contraseña y confirmación de la pagina de registro.
        form.password.value = "";
        form.confirmpassword.value = "";
    }

    // Si todo ha ido bien, enviar el formulario.
    form.submit();
}
