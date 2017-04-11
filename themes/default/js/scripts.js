var colors = new Array(
  [17,240,159],
  [17,240,203],
  [17,211,240],
  [17,129,240],
  [17,55,240],
  [84,240,94],
  [13,1,175],
  [0,27,162],
  [23,153,209],
  [23,209,178],
  [23,209,135]);

var step = 0;
// Color table indices for:
// - current color left
// - next color left
// - current color right
// - next color right
var colorIndices = [0,1,2,3];

// Transition speed
var gradientSpeed = 0.002;

function updateGradient() {
	if ( $===undefined ) return;

	var c0_0 = colors[colorIndices[0]];
	var c0_1 = colors[colorIndices[1]];
	var c1_0 = colors[colorIndices[2]];
	var c1_1 = colors[colorIndices[3]];

	var istep = 1 - step;
	var r1 = Math.round(istep * c0_0[0] + step * c0_1[0]);
	var g1 = Math.round(istep * c0_0[1] + step * c0_1[1]);
	var b1 = Math.round(istep * c0_0[2] + step * c0_1[2]);
	var color1 = "rgb("+r1+","+g1+","+b1+")";

	var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]);
	var g2 = Math.round(istep * c1_0[1] + step * c1_1[1]);
	var b2 = Math.round(istep * c1_0[2] + step * c1_1[2]);
	var color2 = "rgb("+r2+","+g2+","+b2+")";

	$('body2').css({
		background: "-webkit-gradient(linear, left top, right top, from("+color1+"), to("+color2+"))"}).css({
			background: "-moz-linear-gradient(left, "+color1+" 0%, "+color2+" 100%)"});

	step += gradientSpeed;
	if ( step >= 1 ) {
		step %= 1;
		colorIndices[0] = colorIndices[1];
		colorIndices[2] = colorIndices[3];

		// Pick two new target color indices
		// Do not pick the same as the current one
		colorIndices[1] = ( colorIndices[1] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
		colorIndices[3] = ( colorIndices[3] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
	}
}

setInterval(updateGradient,10);

String.prototype.hashCode = function(){
	var hash = 0;
	if (this.length == 0) return hash;
	for (i = 0; i < this.length; i++) {
		char = this.charCodeAt(i);
		hash = ((hash<<5)-hash)+char;
		hash = hash & hash; // Convert to 32bit integer
	}
	return hash;
}

$( document ).ready(function() {

  $("#form-register").submit(function(e) {


    if (false && !validaRegistro(document.getElementById("form-register"))) {
      e.preventDefault();
      return false;
    }

    var url = "api.php"; // the script where you handle the form input.

    $.ajax({
      type: "POST",
      url: url + "?action=getSalt",
      dataType: 'json',
      data: [],
      success: function(data)
      {
        SHA256_init();
        SHA256_write(data['csalt']+$("#form-register input#password").val());
        var hashedPasswordDigest = SHA256_finalize();
        var hashedPassword = array_to_hex_string(hashedPasswordDigest);
        var formData = {
          'nombre': $("#form-register input#nombre").val(),
          'email': $("#form-register input#email").val(),
          'usuario': $("#form-register input#usuario").val(),
          'password': hashedPassword, // hash & salt
          // 'confirmpassword': $("#form-register input#confirmpassword").val(), // dont send
        };
        console.log(formData);
        $.ajax({
          type: "POST",
          url: url + "?action=register",
          dataType: 'json',
          data: formData,
          success: function(data2)
          {
            console.log(data2['status']);
            console.log(data2['msg']); // show response from the php script.
          }
        });
      }
    });

    e.preventDefault();
  });
});


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
    return true;
    // form.submit();
}

$("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
});
