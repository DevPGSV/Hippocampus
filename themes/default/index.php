<html lang="es">

<head>
  <title>Formulario de login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='//www.google.com/recaptcha/api.js'></script>
  <script src="//point-at-infinity.org/jssha256/jssha256.js"></script>

  <script src="scripts.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

	<body>

			<form action="">
				<h2>Bienvenido a Hippocampus</h2>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					<input type="text" placeholder="Usuario" name="usuario">
				</div>

				<br width="50%"> <!-- salto de linea -->

				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					<input type="password" placeholder="Contraseña" name="password">
				</div>

				<br width="50%">

				<input type="checkbox" name="forgotpasswd" id="squaredThree">    Olvidé mi contraseña
				<br>

				<br width="50%">

				<input type="submit" value="Ingresar">

				<br />
				<p align="center"> ¿Aún no tienes tu cuenta?<br><a id="register" href="register">Regístrate</a> </p>

			</form>

	</body>

</html>
