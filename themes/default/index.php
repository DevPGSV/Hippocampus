<html lang="es">

	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<link rel="stylesheet" type="text/css" href="themes/default/css/style.css">
		<script src="themes/default/js/scripts.js"></script>

		<title>Formulario de login</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

	</head>

	<body id="gradient">

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
			<p align="center"> ¿Aún no tienes tu cuenta? <a id="register" href="themes/default/signup.php">Regístrate</a> </p>

		</form>

	</body>

	<script>
	var colors = new Array(
		[0,105,224],
		[0,146,224],
		[0,166,255],
		[0,191,255],
		[115,220,255],
		[154,228,255]);

		var step = 0;
		// Color table indices for:
		// current color left
		// next color left
		// current color right
		// next color right
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

			$('#gradient').css({
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

		setInterval(updateGradient, 5);
	</script>

</html>
