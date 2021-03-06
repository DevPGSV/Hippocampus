// Ads the hashCode() method to strings
String.prototype.hashCode = function() {
  var hash = 0;
  if (this.length == 0) return hash;
  for (i = 0; i < this.length; i++) {
    char = this.charCodeAt(i);
    hash = ((hash << 5) - hash) + char;
    hash = hash & hash; // Convert to 32bit integer
  }
  return hash;
}

// Once called, the dynamic body background will start
function setupBackgroundGradient() {

  var colors = new Array(
    [17, 240, 159], [17, 240, 203], [17, 211, 240], [17, 129, 240], [17, 55, 240], [84, 240, 94], [13, 1, 175], [0, 27, 162], [23, 153, 209], [23, 209, 178], [23, 209, 135]
  );

  var step = 0;
  // Color table indices for:
  // - current color left
  // - next color left
  // - current color right
  // - next color right
  var colorIndices = [0, 1, 2, 3];

  // Transition speed
  var gradientSpeed = 0.010;

  function updateGradient() {
    if ($ === undefined) return;

    var c0_0 = colors[colorIndices[0]];
    var c0_1 = colors[colorIndices[1]];
    var c1_0 = colors[colorIndices[2]];
    var c1_1 = colors[colorIndices[3]];

    var istep = 1 - step;
    var r1 = Math.round(istep * c0_0[0] + step * c0_1[0]);
    var g1 = Math.round(istep * c0_0[1] + step * c0_1[1]);
    var b1 = Math.round(istep * c0_0[2] + step * c0_1[2]);
    var color1 = "rgb(" + r1 + "," + g1 + "," + b1 + ")";

    var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]);
    var g2 = Math.round(istep * c1_0[1] + step * c1_1[1]);
    var b2 = Math.round(istep * c1_0[2] + step * c1_1[2]);
    var color2 = "rgb(" + r2 + "," + g2 + "," + b2 + ")";

    $('body').css({
      background: "-webkit-gradient(linear, left top, right top, from(" + color1 + "), to(" + color2 + "))"
    }).css({
      background: "-moz-linear-gradient(left, " + color1 + " 0%, " + color2 + " 100%)"
    });

    step += gradientSpeed;
    if (step >= 1) {
      step %= 1;
      colorIndices[0] = colorIndices[1];
      colorIndices[2] = colorIndices[3];

      // Pick two new target color indices
      // Do not pick the same as the current one
      colorIndices[1] = (colorIndices[1] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
      colorIndices[3] = (colorIndices[3] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
    }
  }
  setInterval(updateGradient, 40);
}

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
  if (!validuser.test(form.usuario.value)) {
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

    /*
    // Crear un campo nuevo, este será un hash de la contraseña.
    var passwordhash = document.createElement("input");

    // Enviar un nuevo campo oculto con la clave encriptada.
    form.appendChild(p);
    passwordhash.name = "pass";
    passwordhash.id = "pass";
    passwordhash.type = "hidden";
    passwordhash.value = hex_sha512(password.value);

    // Limpiar los campos contraseña y confirmación de la pagina de registro.
    form.password.value = "";
    form.confirmpassword.value = "";
    */
  }

  // form.submit();
  return true;
}

function showModal(title, body) {
  $("#dummyModal-title").html(title);
  $("#dummyModal-body").html(body);
  $("#dummyModal").modal();
}

// Function called when the register from is submitted
function formRegister(e) {
  var url = "api.php";
  $.ajax({
    type: "POST",
    url: url + "?action=getSalt",
    dataType: 'json',
    data: [],
    success: function(data) {
      if (data['status'] == 'ok') {
        if ($(".g-recaptcha").length) {
          var gresponse = grecaptcha.getResponse();
        } else {
          var gresponse = '';
        }

        SHA256_init();
        SHA256_write(data['csalt'] + $("#form-register input#password").val());
        var hashedPasswordDigest = SHA256_finalize();
        var hashedPassword = array_to_hex_string(hashedPasswordDigest);
        var formData = {
          'nombre': $("#form-register input#nombre").val(),
          'email': $("#form-register input#email").val(),
          'usuario': $("#form-register input#usuario").val(),
          'password': hashedPassword,
          'g-recaptcha-response': gresponse,
        };
        $.ajax({
          type: "POST",
          url: url + "?action=register",
          dataType: 'json',
          data: formData,
          success: function(data2) {
            if (data2['status'] == 'ok') { // User was created
              showModal("Welcome!", "You are now a member of Hippocampus!");
              window.location.replace("home");
            } else if (data2['status'] == 'error') {
              var errorMessage = "";
              data2['msg'].forEach(function(element, index, array) {
                errorMessage += element + "\n";
                if (element == 'error_recaptcha') {
                  grecaptcha.reset();
                }
              });
              showModal("Error registering...", errorMessage.replace(/(?:\r\n|\r|\n)/g, '<br />'));
            } else {
              console.log('Status: ' + data2['status']);
            }
          }
        });
      } else if (data['status'] == 'error') {
        showModal("Error requesting data...", errorMessage.replace(/(?:\r\n|\r|\n)/g, '<br />'));
      } else {
        console.log('Status: ' + data['status']);
      }
    }
  });
}

// Function called when the login from is submitted
function formLogin(e) {
  var url = "api.php";
  $.ajax({
    type: "POST",
    url: url + "?action=getSalt",
    dataType: 'json',
    data: {
      'username': $("#form-login input#usuario").val(),
    },
    success: function(data) {
      if (data['status'] == 'ok') {
        hashedPassword = '';
        if ($("#form-login input#password").val() != '') {
          SHA256_init();
          SHA256_write(data['csalt'] + $("#form-login input#password").val());
          var hashedPasswordDigest = SHA256_finalize();
          var hashedPassword = array_to_hex_string(hashedPasswordDigest);
        }
        var formData = {
          'username': $("#form-login input#usuario").val(),
          'password': hashedPassword,
        };
        console.log(formData);
        $.ajax({
          type: "POST",
          url: url + "?action=login",
          dataType: 'json',
          data: formData,
          success: function(data2) {
            if (data2['status'] == 'ok') { // User was created
              window.location.replace("home");
            } else if (data2['status'] == 'error') {
              var errorMessage = "";
              data2['msg'].forEach(function(element, index, array) {
                errorMessage += element + "\n";
              });
              showModal("Error login...", errorMessage.replace(/(?:\r\n|\r|\n)/g, '<br />'));
            } else {
              console.log('Status: ' + data2['status']);
            }
          }
        });

      } else if (data['status'] == 'error') {
        showModal("Error!", data['msg'].replace(/(?:\r\n|\r|\n)/g, '<br />'));
      } else {
        console.log('Status: ' + data['status']);
      }
    }
  });
}

function updateBoxContents(box) {
  box.find('.userview-content-column').html('Loading...');
  $.ajax({
    type: "POST",
    url: 'window',
    dataType: 'json',
    data: {
      'row': box.find('.userview-content-column').attr('data-boxrow'),
      'col': box.find('.userview-content-column').attr('data-boxcol'),
      'content': box.find('.userview-content-column').attr('data-boxcontent'),
      'cbdata': box.data('cbdata') || '',
    },
    success: function(data) {
      box.find('.userview-content-column').html(data.html);
      //box.find('.userview-window-toolbar').find('.userview-window-toolbar-service').html(box.find('.userview-content-column').attr('data-boxcontent'));
      box.find('.userview-window-toolbar').find('.userview-window-toolbar-service').html(data.title);
      box.find('[data-updatewindowboxservice]').css('color', '#4d91de').css('text-decoration', 'underline');
      box.find('[data-updatewindowboxservice]').click(function(e) {
        var odata = $(this).data();
        var cbdata = {};
        for (var key in odata) {
          if (key.startsWith("cbdata"))
            cbdata[key.slice("cbdata".length)] = odata[key];
        }
        box.data('cbdata', cbdata);

        setBoxContents(box, $(this).attr('data-updatewindowboxservice'));
      });
    },
  });
}

function setBoxContents(box, service) {
  $.ajax({
    type: "POST",
    url: 'api.php?action=setWindowBox',
    dataType: 'json',
    data: {
      'row': box.find('.userview-content-column').attr('data-boxrow'),
      'col': box.find('.userview-content-column').attr('data-boxcol'),
      'service': service,
    },
    success: function(data) {
      if (data['status'] === 'ok') {
        box.find('.userview-content-column').attr('data-boxcontent', service);
        updateBoxContents(box);
      } else {
        alert(data['msg']);
      }
    },
  });
}

function setBoxLayout(layout) {
  $.ajax({
    type: "POST",
    url: 'api.php?action=updateWindowLayout',
    dataType: 'json',
    data: {
      'layout': layout,
    },
    success: function(data) {
      console.log(data);
      if (data['status'] === 'ok') {
        location.reload();
      } else {
        alert(data['msg']);
      }
    },
  });
}

function fullscreenBoxLayout(ui) {
  $(ui).closest('.userview-content-column-wrapper');
  var service = $(ui).closest('.userview-content-column-wrapper').find('.userview-content-column').attr('data-boxcontent');
  var box = $('.userview-content-column-wrapper').first();
  setBoxContents(box, service);
  setBoxLayout([1]);
}

$(document).ready(function() {

  setTimeout(function() {
    $('#toplogo').fadeOut();
  }, 100);

  $(".admin-edit-user").click(function() {
    var popup = $('<div id="edit-popup" title="Editar"><p>Usuario: </p><input type="text" class="form-control" placeholder="Usuario" name="usuario" id="usuario" data-toggle="tooltip" data-placement="top" title="Entre 4 y 20 caracteres"><br><p>Rol: </p><input type="text" class="form-control" placeholder="Rol" name="rol" id="rol"><br><p>Email: </p><input type="email" class="form-control" placeholder="Email" name="email" id="email"></div>');
    popup.dialog({
      modal: true,
      width: 600,
      closeOnEscape: true,
      buttons: {
        "Guardar": function() {
          alert("Tus ajustes han sido guardados");
          $(this).dialog("close");
        },
      },
    });
  });

  $(".admin-unactive-theme").click(function() {
    var popup = $('<div title="Activar"><p>¿Está seguro de querer activar este tema? </p></div>');
    popup.dialog({
      modal: true,
      width: 600,
      closeOnEscape: true,
      buttons: {
        "Activar": function() {
          alert("El tema ha sido establecido.");
          $(this).dialog("close");
        },
      },
    });
  });
  $(".admin-active-theme").click(function() {
    var popup = $('<div title="Desactivar"><p>¿Está seguro de querer desactivar este tema? </p></div>');
    popup.dialog({
      modal: true,
      width: 600,
      closeOnEscape: true,
      buttons: {
        "Desactivar": function() {
          alert("El tema ha sido desactivado.");
          $(this).dialog("close");
        },
      },
    });
  });
  $(".admin-unactive-module").click(function() {
    var popup = $('<div title="Activar"><p>¿Está seguro de querer activar este módulo? </p></div>');
    popup.dialog({
      modal: true,
      width: 600,
      closeOnEscape: true,
      buttons: {
        "Activar": function() {
          alert("El módulo ha sido desactivado.");
          $(this).dialog("close");
        },
      },
    });
  });

  $(".admin-active-module").click(function() {
    var popup = $('<div title="Desactivar"><p>¿Está seguro de querer desactivar este módulo? </p></div>');
    popup.dialog({
      modal: true,
      width: 600,
      closeOnEscape: true,
      buttons: {
        "Desactivar": function() {
          alert("El módulo ha sido activado.");
          $(this).dialog("close");
        },
      },
    });
  });

  $(".admin-erase-user").click(function() {
    var user = $(this).attr('data-user');
    var popup = $('<div title="Eliminar"><p>¿Está seguro de querer eliminar este usuario? </p></div>');
    popup.dialog({
      modal: true,
      width: 600,
      closeOnEscape: true,
      buttons: {
        "Eliminar": function() {
          alert("El usuario ha sido eliminado");
          $.ajax({
            type: "POST",
            url: 'api.php?action=deleteuser',
            dataType: 'json',
            data: {
              'username': user,
            },
            success: function(data) {
              console.log(data);
              if (data['status'] === 'ok') {
                location.reload();
              } else {
                alert(data['msg']);
              }
            },
          });
          $(this).dialog("close");
        },
      },
    });
  });

  $(document).on('change', ':file', function() {
    var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });


  setupBackgroundGradient();

  $("#sidebar.sidebar-nav-items>li").draggable({
    revert: true,
    helper: "clone",
    appendTo: "#mainpage",
  });


  $(".userview-content-column-wrapper").droppable({
    accept: "#sidebar.sidebar-nav-items>li",
    classes: {
      "ui-droppable-active": "ui-droppable-active",
      "ui-droppable-hover": "ui-droppable-hover",
    },
    drop: function(event, ui) {
      console.log(ui.draggable.find('span').attr('data-service'));
      setBoxContents($(this), ui.draggable.find('span').attr('data-service'));
    }
  });

  $(".userview-content-column-wrapper").each(function(index) {
    var box = $(this);
    updateBoxContents(box);
  });



  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("active");
  });

  $("#mainsidebar-toogle").click(function(e) {
    $("#mainpage").toggleClass("sidebar-expansible");
  });

  $('[data-toggle="tooltip"]').tooltip();

  $("#form-register").submit(function(e) {
    e.preventDefault();
    if (false && !validaRegistro(document.getElementById("form-register"))) {
      return false;
    }
    return formRegister(e);
  });

  $("#form-login").submit(function(e) {
    e.preventDefault();
    return formLogin(e);
  });

  $('[data-updatewindowboxservice]').click(function(e) {
    console.log(this);
    console.log($(this));
    console.log($(this).attr('data-updatewindowboxservice'));
  });
});

//Sube el menu de editar perfil y añadir columnas
$(function() {

  var active = $('[data-toggle="pill"]').parents('.active').length;
  var tabClicked = false;

  // Closes current active tab (toggle and pane):
  var close = function() {
    $('[data-toggle="pill"]').parent().removeClass('active');
    $('.tab-pane.active').removeClass('active');
    active = null;
  }

  // Closing active tab when clicking on toggle:
  $('[data-toggle=pill]').click(function() {
    if ($(this).parent().hasClass('active')) {
      $($(this).attr("href")).toggleClass('active');
    } else {
      tabClicked = true;
      active = this;
    }
  });

  // Closing active tab when clicking outside tab context (toggle and pane):
  $(document).on('click.bs.tab.data-api', function(event) {
    if (active && !tabClicked && !$(event.target).closest('.tab-pane.active').length) {
      close();
    }

    tabClicked = false;
  });


});

// Animated sidebar menu icon
$(document).ready(function() {
  $('#menu-nav-icon').click(function() {
    $(this).toggleClass('open');
  });
});

//function for opening text of the index bottom (quiénes somos)
$(document).ready(function() {
  $("who").click(function() {
    $("info").text("Hello world!");
  });
});
