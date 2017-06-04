<html lang="es">
<head>
  <title>Formulario de registro</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Actor'>
  <script src='//www.google.com/recaptcha/api.js'></script>

  <script src="lib/jssha256/jssha256.js"></script>
  <script src="scripts.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <form action="" method="POST" id="form-register">
    <h2>Bienvenido a Hippocampus</h2>

    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-font"></i></span>
      <input type="text" placeholder="Nombre" name="nombre" id="nombre">
    </div>
    <br>

    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
      <input type="text" placeholder="Email" name="email" id="email">
    </div>
    <br>

    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
      <input type="text" placeholder="Usuario"  name="usuario" id="usuario"  title="Entre 4 y 20 caracteres" >
    </div>
    <br>

    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
      <input type="password" placeholder="Contraseña" name="password" id="password" title="La contraseña debe contener al menos un número, una letra minúscula, una mayúscula y un caracter espcecial y tener entre 8 y 20 caracteres">
    </div>
    <br>

    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
      <input type="password" placeholder="Confirme su contraseña" name="confirmpassword" id="confirmpassword">
    </div>
    <br>

    <?php
    if ($hc->getDB()->getConfigValue('module.RecaptchaModule.enable') == 'true') {
        echo '<div class="g-recaptcha" align="center" data-sitekey="'.$hc->getDB()->getConfigValue('module.RecaptchaModule.token_public').'"></div>';
    }
    ?>
    <br>
    <input type="submit" value="Crear cuenta">
    <br>
    <p align="center"> ¿Ya tienes cuenta?<br><a id="home" href="home">Inicia sesión</a> </p>
  </form>

  <!-- Modal -->
  <div class="modal fade" id="dummyModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="dummyModal-title">Modal Header</h4>
        </div>
        <div class="modal-body" id="dummyModal-body">
          <p>Some text in the modal.</p>
        </div>
        <!--
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        -->
        </div>
      </div>
    </div>
  </div>

</body>

	<script type="application/javascript">
		validemail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		document.getElementById('email').addEventListener("change", function () {
			if (!validemail.test(this.value)) {
				document.getElementById('email').style.border = "2px solid red";
			} else {
				document.getElementById('email').style.border = "2px solid green";
			}
		}, false);
	</script>

</html>
